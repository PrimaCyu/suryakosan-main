<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\ProductKamarKosan;
use App\Models\ProductKosan;
use App\Models\SosialMedia;
use App\Models\Tamu;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalKosan = ProductKosan::count();
        $totalKamar = ProductKamarKosan::count();
        $pendingBookingsCount = Tamu::where('status', 'pending')->count();
        $approvedBookingsCount = Tamu::where('status', 'approved')->count();
        $totalArtikel = Artikel::count();
        $totalTestimoni = Testimoni::count();
        $totalSosmed = SosialMedia::count();

        // Recent Bookings (Pending first, then latest)
        $recentBookings = Tamu::with(['productKamarKosan.productKosan'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        // Recent Kosan Properties with room counts
        $recentKosans = ProductKosan::withCount('productKamarKosan')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Recent Articles
        $recentArtikels = Artikel::orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('backend.dashboard.home', compact(
            'totalKosan',
            'totalKamar',
            'pendingBookingsCount',
            'approvedBookingsCount',
            'totalArtikel',
            'totalTestimoni',
            'totalSosmed',
            'recentBookings',
            'recentKosans',
            'recentArtikels'
        ));
    }
}
