<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

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

        return back()->with('success', 'Permintaan booking atas nama ' . $dataTamu->name . ' telah disetujui (Approved) oleh ' . $user->name . '. Audit trail tercatat.');
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

        return back()->with('success', 'Permintaan booking atas nama ' . $dataTamu->name . ' telah ditolak (Rejected) oleh ' . $user->name . '. Alasan penolakan telah tercatat di jejak audit.');
    }
}
