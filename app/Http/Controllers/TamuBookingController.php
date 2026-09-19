<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmationMail;
use App\Models\ProductKamarKosan;
use App\Models\Tamu;
use App\Service\ProcessBookingDate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TamuBookingController extends Controller
{
    protected $processBookingDates;

    public function __construct(ProcessBookingDate $processBookingDates)
    {
        $this->processBookingDates = $processBookingDates;
    }

    public function booking($product_kamar_kosan, Request $request)
    {
        $kamar = ProductKamarKosan::with(['priceKamar', 'productKosan'])->findOrFail($product_kamar_kosan);

        $request->validate([
            'name'              => 'required|string|max:255',
            'telp'              => 'required|string|max:25',
            'email'             => 'required|email|max:255',
            'start_date'        => 'required|date',
            'start_time'        => 'required|string',
            'payment_method'    => 'required|string|in:transfer,qris,cash,bca,mandiri,bni',
            'proof_of_transfer' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'jam'               => 'nullable|integer|min:0',
            'hari'              => 'nullable|integer|min:0',
            'minggu'            => 'nullable|integer|min:0',
            'bulan'             => 'nullable|integer|min:0',
            'tahun'             => 'nullable|integer|min:0',
        ]);

        // 1. Validasi ketersediaan jadwal kamar
        $bookingDate = $this->processBookingDates->calculateBookingRange($kamar->id, $request);
        if (isset($bookingDate['status']) && $bookingDate['status'] === false) {
            return back()->withInput()->with('failed', $bookingDate['message']);
        }

        // 2. Kalkulasi total harga resmi dari sisi backend (Anti Price Tampering)
        $serverCalculatedPrice = $this->processBookingDates->calculateBookingPrice($kamar, $request);

        $tamu = DB::transaction(function () use ($kamar, $bookingDate, $request, $serverCalculatedPrice) {
            $data = [
                'product_kamar_kosan_id' => $kamar->id,
                'name'                   => strip_tags($request->name),
                'telp'                   => strip_tags($request->telp),
                'email'                  => filter_var($request->email, FILTER_SANITIZE_EMAIL),
                'start_time'             => $request->start_time,
                'start_date'             => $bookingDate['start'],
                'end_date'               => $bookingDate['end'],
                'payment_method'         => strtolower($request->payment_method),
                'total_price'            => $serverCalculatedPrice,
                'status'                 => 'pending',
            ];

            if ($request->hasFile('proof_of_transfer') && $request->file('proof_of_transfer')->isValid()) {
                $path = $request->file('proof_of_transfer')->store('tamu/proof-of-transfer', 'public');
                $data['proof_of_transfer'] = $path;
            }

            return Tamu::create($data);
        });

        // 3. Generate PDF dan kirim email konfirmasi
        try {
            $tamu->load(['productKamarKosan.productKosan']);
            $pdf = Pdf::loadView('pdf.booking_invoice', compact('tamu'));
            $pdfOutput = $pdf->output();

            Mail::to($tamu->email)->send(new BookingConfirmationMail($tamu, $pdfOutput));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email konfirmasi booking / generate PDF: ' . $e->getMessage());
        }

        return redirect()->route('booking.success', $tamu->access_token)->with('success', 'Booking Berhasil! Simpan bukti reservasi ini.');
    }

    public function bookingSuccess(string $token)
    {
        $tamu = Tamu::where('access_token', $token)->firstOrFail();
        $tamu->load(['productKamarKosan.productKosan', 'productKamarKosan.priceKamar', 'productKamarKosan.productKamarImageKosan']);
        return view('frontend.kosan.kamar.booking-success', compact('tamu'));
    }

    public function downloadInvoice(string $token)
    {
        $tamu = Tamu::where('access_token', $token)->firstOrFail();
        $tamu->load(['productKamarKosan.productKosan']);
        $pdf = Pdf::loadView('pdf.booking_invoice', compact('tamu'));
        $filename = 'Invoice-Booking-' . str_pad($tamu->id, 5, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($filename);
    }
}


