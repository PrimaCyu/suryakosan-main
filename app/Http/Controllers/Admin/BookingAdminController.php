<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingApprovedMail;
use App\Mail\BookingRejectedMail;
use App\Models\SosialMedia;
use App\Models\Tamu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingAdminController extends Controller
{
    public function indexBooking(Request $request)
    {
        $user = auth()->user();
        $isSuper = $user->isSuperAdmin();
        $assignedIds = $isSuper ? collect() : $user->kosans()->pluck('product_kosans.id');

        // Base scope for counting and filtering according to role
        $baseScope = Tamu::query()->when(!$isSuper, function ($q) use ($assignedIds) {
            $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
        });

        // Calculate counts for tabs
        $countPending  = (clone $baseScope)->where('status', 'pending')->count();
        $countApproved = (clone $baseScope)->where('status', 'approved')->count();
        $countReject   = (clone $baseScope)->where('status', 'reject')->count();
        $countAll      = (clone $baseScope)->count();

        // Determine active status filter (defaults to pending if any pending exist, otherwise all)
        $statusFilter = $request->get('status');
        if (!$statusFilter || !in_array($statusFilter, ['pending', 'approved', 'reject', 'all'])) {
            $statusFilter = $countPending > 0 ? 'pending' : 'all';
        }

        // Build main query
        $query = (clone $baseScope)->with(['productKamarKosan.productKosan', 'processedBy']);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(telp) LIKE ?', ["%{$search}%"])
                  ->orWhereHas('productKamarKosan.productKosan', function($sub) use ($search) {
                      $sub->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
                  });
            });
        }

        $bookings = $query->orderByDesc('updated_at')->paginate(10);

        if ($request->ajax()) {
            return response()->json($bookings);
        }

        return view('backend.dashboard.permintaan-booking.index', compact(
            'bookings',
            'statusFilter',
            'countPending',
            'countApproved',
            'countReject',
            'countAll'
        ));
    }

    public function approveBooking(Request $request, $tamu)
    {
        $id = $tamu instanceof Tamu ? $tamu->id : $tamu;
        $dataTamu = Tamu::with('productKamarKosan.productKosan')->findOrFail($id);
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            $assignedIds = $user->kosans()->pluck('product_kosans.id')->toArray();
            if (!in_array($dataTamu->productKamarKosan->product_kosan_id, $assignedIds)) {
                abort(403, 'Akses ditolak. Anda tidak berwenang mengelola booking kosan ini.');
            }
        }

        $dataTamu->update([
            'status' => 'approved',
            'processed_by' => $user->id,
            'processed_at' => now(),
            'rejection_reason' => null,
        ]);

        if ($dataTamu->productKamarKosan && $dataTamu->productKamarKosan->productKosan) {
            $dataTamu->productKamarKosan->productKosan->syncAvailableCount();
        }

        // Kirim email konfirmasi persetujuan (Approved) beserta lampiran file PDF kwitansi lunas
        try {
            if (!empty($dataTamu->email)) {
                $dataTamu->load(['productKamarKosan.productKosan']);
                $pdf = Pdf::loadView('pdf.booking_invoice', ['tamu' => $dataTamu]);
                $pdfOutput = $pdf->output();

                $waSosmed = SosialMedia::where('title', 'like', '%whatsapp%')->orWhere('url', 'like', '%wa.me%')->first();
                $waNumber = '6281234567890';
                if ($waSosmed) {
                    preg_match('/[0-9]{9,15}/', $waSosmed->url, $m);
                    if (!empty($m[0])) {
                        $waNumber = $m[0];
                    }
                }

                Mail::to($dataTamu->email)->send(new BookingApprovedMail($dataTamu, $pdfOutput, $waNumber));
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email approved booking ke ' . $dataTamu->email . ': ' . $e->getMessage());
        }

        return back()->with('success', 'Permintaan booking atas nama ' . $dataTamu->name . ' telah disetujui (Approved) oleh ' . $user->name . '. Email konfirmasi resmi & kwitansi PDF telah dikirim ke penyewa.');
    }

    public function rejectBooking(Request $request, $tamu)
    {
        $id = $tamu instanceof Tamu ? $tamu->id : $tamu;
        $dataTamu = Tamu::with('productKamarKosan.productKosan')->findOrFail($id);
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            $assignedIds = $user->kosans()->pluck('product_kosans.id')->toArray();
            if (!in_array($dataTamu->productKamarKosan->product_kosan_id, $assignedIds)) {
                abort(403, 'Akses ditolak. Anda tidak berwenang mengelola booking kosan ini.');
            }
        }

        // Determine rejection reason
        $reasonPreset = $request->input('reason_preset');
        $reasonCustom = trim($request->input('rejection_reason') ?? '');

        if ($reasonCustom !== '') {
            $reason = $reasonCustom;
        } elseif ($reasonPreset && $reasonPreset !== 'other') {
            $reason = $reasonPreset;
        } else {
            $reason = 'Ditolak oleh admin pengelola.';
        }

        $dataTamu->update([
            'status' => 'reject',
            'processed_by' => $user->id,
            'processed_at' => now(),
            'rejection_reason' => $reason,
        ]);

        if ($dataTamu->productKamarKosan && $dataTamu->productKamarKosan->productKosan) {
            $dataTamu->productKamarKosan->productKosan->syncAvailableCount();
        }

        // Kirim email pemberitahuan penolakan (Rejected) tanpa lampiran PDF, disertai alasan & link bantuan WA
        try {
            if (!empty($dataTamu->email)) {
                $dataTamu->load(['productKamarKosan.productKosan']);
                $waSosmed = SosialMedia::where('title', 'like', '%whatsapp%')->orWhere('url', 'like', '%wa.me%')->first();
                $waNumber = '6281234567890';
                if ($waSosmed) {
                    preg_match('/[0-9]{9,15}/', $waSosmed->url, $m);
                    if (!empty($m[0])) {
                        $waNumber = $m[0];
                    }
                }

                Mail::to($dataTamu->email)->send(new BookingRejectedMail($dataTamu, $reason, $waNumber));
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email rejected booking ke ' . $dataTamu->email . ': ' . $e->getMessage());
        }

        return back()->with('success', 'Permintaan booking atas nama ' . $dataTamu->name . ' telah ditolak (Rejected) oleh ' . $user->name . '. Email pemberitahuan alasan penolakan telah dikirim ke penyewa.');
    }
}
