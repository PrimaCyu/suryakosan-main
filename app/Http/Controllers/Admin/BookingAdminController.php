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

        $query = Tamu::with(['productKamarKosan.productKosan'])
            ->where('status', 'pending')
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            });

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(telp) LIKE ?', ["%{$search}%"]);
            });
        }

        $bookings = $query->orderByDesc('created_at')->paginate(10);

        if ($request->ajax()) {
            return response()->json($bookings);
        }

        return view('backend.dashboard.permintaan-booking.index', compact('bookings'));
    }

    public function approveBooking($tamu)
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
            'status' => 'approved'
        ]);

        if ($dataTamu->productKamarKosan && $dataTamu->productKamarKosan->productKosan) {
            $dataTamu->productKamarKosan->productKosan->syncAvailableCount();
        }

        return back()->with('success', 'Permintaan booking atas nama ' . $dataTamu->name . ' telah disetujui (Approved).');
    }

    public function rejectBooking($tamu)
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
            'status' => 'reject'
        ]);

        if ($dataTamu->productKamarKosan && $dataTamu->productKamarKosan->productKosan) {
            $dataTamu->productKamarKosan->productKosan->syncAvailableCount();
        }

        return back()->with('success', 'Permintaan booking atas nama ' . $dataTamu->name . ' telah ditolak (Rejected).');
    }
}
