<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductKamarKosan;
use App\Models\ProductKosan;
use App\Models\Tamu;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuper = $user->isSuperAdmin();
        $assignedIds = $isSuper ? collect() : $user->kosans()->pluck('product_kosans.id');

        // 1. Cakupan Properti & Kamar
        $totalKosan = $isSuper ? ProductKosan::count() : $assignedIds->count();
        $totalKamar = ProductKamarKosan::query()
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereIn('product_kosan_id', $assignedIds);
            })->count();

        // Kamar Terisi (memiliki penyewa aktif berstatus approved dengan end_date >= hari ini)
        $occupiedKamar = ProductKamarKosan::whereHas('tamu', function ($q) {
                $q->where('status', 'approved')
                  ->whereDate('end_date', '>=', now()->toDateString());
            })
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereIn('product_kosan_id', $assignedIds);
            })
            ->count();

        // Kamar Kosong & Okupansi
        $availableKamar = max(0, $totalKamar - $occupiedKamar);
        $occupancyRate = $totalKamar > 0 ? round(($occupiedKamar / $totalKamar) * 100, 1) : 0;

        // 2. Pendapatan (Bulan ini & Total Akumulasi Approved)
        $currentMonthRevenue = Tamu::where('status', 'approved')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            })
            ->sum('total_price');

        $totalRevenue = Tamu::where('status', 'approved')
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            })
            ->sum('total_price');

        // 3. Tindakan Hari Ini Bagian 1: Booking Baru Menunggu Konfirmasi (Pending)
        $pendingBookings = Tamu::with(['productKamarKosan.productKosan'])
            ->where('status', 'pending')
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $pendingBookingsCount = $pendingBookings->count();

        // 4. Tindakan Hari Ini Bagian 2: Sewa & Pembayaran Jatuh Tempo (H-7 s/d H+30 Overdue)
        $expiringOrOverdueTenancies = Tamu::with(['productKamarKosan.productKosan'])
            ->where('status', 'approved')
            ->whereDate('end_date', '<=', now()->addDays(7)->toDateString())
            ->whereDate('end_date', '>=', now()->subDays(30)->toDateString())
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            })
            ->orderBy('end_date', 'asc')
            ->get();

        $expiringOrOverdueCount = $expiringOrOverdueTenancies->count();
        $totalUrgentActions = $pendingBookingsCount + $expiringOrOverdueCount;

        // 5. Ringkasan Performa Seluruh Properti Cabang
        $propertiesOverview = ProductKosan::with(['productKamarKosan' => function ($q) {
                $q->withCount(['tamu as active_tenants_count' => function ($sub) {
                    $sub->where('status', 'approved')
                        ->whereDate('end_date', '>=', now()->toDateString());
                }]);
            }])
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereIn('id', $assignedIds);
            })
            ->orderBy('title', 'asc')
            ->get()
            ->map(function ($kosan) {
                $roomsCount = $kosan->productKamarKosan->count();
                $occupiedCount = $kosan->productKamarKosan->filter(fn($r) => $r->active_tenants_count > 0)->count();
                $availableCount = max(0, $roomsCount - $occupiedCount);
                $rate = $roomsCount > 0 ? round(($occupiedCount / $roomsCount) * 100) : 0;

                return [
                    'id'              => $kosan->id,
                    'title'           => $kosan->title,
                    'slug'            => $kosan->slug,
                    'wilayah'         => $kosan->wilayah ?? 'Bali',
                    'rooms_count'     => $roomsCount,
                    'occupied_count'  => $occupiedCount,
                    'available_count' => $availableCount,
                    'occupancy_rate'  => $rate,
                ];
            });

        return view('backend.dashboard.home', compact(
            'totalKosan',
            'totalKamar',
            'occupiedKamar',
            'availableKamar',
            'occupancyRate',
            'currentMonthRevenue',
            'totalRevenue',
            'pendingBookings',
            'pendingBookingsCount',
            'expiringOrOverdueTenancies',
            'expiringOrOverdueCount',
            'totalUrgentActions',
            'propertiesOverview'
        ));
    }
}
