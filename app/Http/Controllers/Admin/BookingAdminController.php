<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    public function indexBooking(Request $request)
    {
        $query = Tamu::with(['productKamarKosan.productKosan'])
            ->where('status', 'pending');

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
        $dataTamu = Tamu::findOrFail($tamu);

        $dataTamu->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Permintaan booking atas nama ' . $dataTamu->name . ' telah disetujui (Approved).');
    }

    public function rejectBooking($tamu)
    {
        $dataTamu = Tamu::findOrFail($tamu);
        $dataTamu->delete();

        return back()->with('success', 'Permintaan booking atas nama ' . $dataTamu->name . ' telah ditolak (Rejected).');
    }
}
