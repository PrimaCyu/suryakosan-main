<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingApprovedMail;
use App\Mail\BookingRejectedMail;
use App\Models\SosialMedia;
use App\Models\Tamu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        // Cek apakah jadwal bertabrakan dengan booking lain yang sudah APPROVED pada kamar ini
        $overlappingApproved = Tamu::where('product_kamar_kosan_id', $dataTamu->product_kamar_kosan_id)
            ->where('status', 'approved')
            ->where('id', '!=', $dataTamu->id)
            ->where(function ($query) use ($dataTamu) {
                $query->where('start_date', '<', $dataTamu->end_date)
                      ->where('end_date', '>', $dataTamu->start_date);
            })
            ->first();

        if ($overlappingApproved) {
            $existingStart = \Carbon\Carbon::parse($overlappingApproved->start_date)->format('d M Y');
            $existingEnd = \Carbon\Carbon::parse($overlappingApproved->end_date)->format('d M Y');
            return back()->with('failed', "Tidak dapat menyetujui: Kamar sudah terisi oleh penyewa '{$overlappingApproved->name}' pada periode {$existingStart} s/d {$existingEnd}.");
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($dataTamu, $user) {
            $dataTamu->update([
                'status' => 'approved',
                'processed_by' => $user->id,
                'processed_at' => now(),
                'rejection_reason' => null,
            ]);

            // Otomatis batalkan booking pending lain yang bertabrakan jadwalnya
            Tamu::where('product_kamar_kosan_id', $dataTamu->product_kamar_kosan_id)
                ->where('status', 'pending')
                ->where('id', '!=', $dataTamu->id)
                ->where(function ($query) use ($dataTamu) {
                    $query->where('start_date', '<', $dataTamu->end_date)
                          ->where('end_date', '>', $dataTamu->start_date);
                })
                ->update([
                    'status' => 'reject',
                    'processed_by' => $user->id,
                    'processed_at' => now(),
                    'rejection_reason' => 'Kamar telah disetujui untuk pemohon booking lain pada periode tanggal yang sama.',
                ]);
        });

        if ($dataTamu->productKamarKosan && $dataTamu->productKamarKosan->productKosan) {
            $dataTamu->productKamarKosan->productKosan->syncAvailableCount();
        }

        Cache::forget('home_kamar_list');
        Cache::forget('home_kosan_list');

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

        Cache::forget('home_kamar_list');
        Cache::forget('home_kosan_list');

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

    public function viewProof($tamu)
    {
        $id = $tamu instanceof Tamu ? $tamu->id : $tamu;
        $dataTamu = Tamu::with('productKamarKosan.productKosan')->findOrFail($id);
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            $assignedIds = $user->kosans()->pluck('product_kosans.id')->toArray();
            if (!in_array($dataTamu->productKamarKosan->product_kosan_id, $assignedIds)) {
                abort(403, 'Akses ditolak.');
            }
        }

        if (empty($dataTamu->proof_of_transfer)) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($dataTamu->proof_of_transfer)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->response($dataTamu->proof_of_transfer);
        }

        if (\Illuminate\Support\Facades\Storage::disk('local')->exists($dataTamu->proof_of_transfer)) {
            return \Illuminate\Support\Facades\Storage::disk('local')->response($dataTamu->proof_of_transfer);
        }

        abort(404, 'File bukti transfer tidak ditemukan di storage.');
    }
}
