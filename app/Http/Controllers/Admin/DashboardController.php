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
        $user = auth()->user();
        $isSuper = $user->isSuperAdmin();
        $assignedIds = $isSuper ? collect() : $user->kosans()->pluck('product_kosans.id');

        // Statistics
        $totalKosan = $isSuper ? ProductKosan::count() : $assignedIds->count();
        $totalKamar = $isSuper ? ProductKamarKosan::count() : ProductKamarKosan::whereIn('product_kosan_id', $assignedIds)->count();

        $pendingBookingsCount = Tamu::where('status', 'pending')
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            })->count();

        $approvedBookingsCount = Tamu::where('status', 'approved')
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            })->count();

        $totalArtikel = Artikel::count();
        $totalTestimoni = Testimoni::count();
        $totalSosmed = SosialMedia::count();

        // Recent Bookings (Pending first, then latest)
        $recentBookings = Tamu::with(['productKamarKosan.productKosan'])
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            })
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        // Recent Kosan Properties with room counts
        $recentKosans = ProductKosan::withCount('productKamarKosan')
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereIn('id', $assignedIds);
            })
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Recent Articles
        $recentArtikels = Artikel::orderByDesc('created_at')
            ->take(4)
            ->get();

        // Expiring & Overdue Tenancies (Masa sewa mau habis <= 7 hari ke depan, atau jatuh tempo s/d 14 hari lalu)
        $expiringTenancies = Tamu::with(['productKamarKosan.productKosan'])
            ->where('status', 'approved')
            ->whereDate('end_date', '<=', now()->addDays(7)->toDateString())
            ->whereDate('end_date', '>=', now()->subDays(14)->toDateString())
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            })
            ->orderBy('end_date', 'asc')
            ->take(6)
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
            'recentArtikels',
            'expiringTenancies'
        ));
    }
}
