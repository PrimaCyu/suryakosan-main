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

        // Structured Operational Alerts
        $operationalAlerts = [];
        if ($pendingBookingsCount > 0) {
            $operationalAlerts[] = [
                'type' => 'pending_booking',
                'title' => $pendingBookingsCount . ' Booking Menunggu Persetujuan',
                'subtitle' => 'Reservasi unit kamar baru membutuhkan verifikasi data & persetujuan pengelola.',
                'action_label' => 'Lihat Booking →',
                'action_url' => route('admin.booking.index'),
                'icon' => 'bi-calendar-check',
                'badge_class' => 'bg-amber-subtle text-amber',
            ];
        }

        // Overdue payments (end_date < today)
        $overdueCount = $expiringOrOverdueTenancies->filter(function ($t) {
            try {
                return \Carbon\Carbon::parse($t->end_date)->isPast();
            } catch (\Exception $e) {
                return false;
            }
        })->count();

        if ($overdueCount > 0) {
            $operationalAlerts[] = [
                'type' => 'overdue_payment',
                'title' => $overdueCount . ' Pembayaran Terlambat',
                'subtitle' => 'Masa sewa kamar telah melewati tanggal jatuh tempo pembayaran.',
                'action_label' => 'Lihat Pembayaran →',
                'action_url' => route('admin.booking.index'),
                'icon' => 'bi-exclamation-circle',
                'badge_class' => 'bg-danger-subtle text-danger',
            ];
        }

        // Expiring soon payments (end_date between today and today + 7 days)
        $expiringSoonCount = $expiringOrOverdueTenancies->filter(function ($t) {
            try {
                $end = \Carbon\Carbon::parse($t->end_date);
                return $end->isFuture() && $end->diffInDays(now()) <= 7;
            } catch (\Exception $e) {
                return false;
            }
        })->count();

        if ($expiringSoonCount > 0) {
            $operationalAlerts[] = [
                'type' => 'expiring_soon',
                'title' => $expiringSoonCount . ' Pembayaran Mendekati Jatuh Tempo',
                'subtitle' => 'Masa sewa penghuni akan berakhir dalam kurun waktu 7 hari ke depan.',
                'action_label' => 'Lihat Pembayaran →',
                'action_url' => route('admin.booking.index'),
                'icon' => 'bi-clock-history',
                'badge_class' => 'bg-warning-subtle text-warning-emphasis',
            ];
        }

        // 6-Month Revenue Trend (Data Riil)
        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = now()->subMonths($i);
            $mKey = $dt->isoFormat('MMM Y');
            $mNum = (int)$dt->month;
            $yNum = (int)$dt->year;

            $actualRev = Tamu::where('status', 'approved')
                ->whereYear('created_at', $yNum)
                ->whereMonth('created_at', $mNum)
                ->when(!$isSuper, function ($q) use ($assignedIds) {
                    $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
                })
                ->sum('total_price');

            $revenueTrend[] = [
                'month'      => $mKey,
                'full_month' => $dt->isoFormat('MMMM Y'),
                'revenue'    => (int)$actualRev,
            ];
        }

        // Recent Activities (4-5 operational events)
        $recentTamus = Tamu::with(['productKamarKosan.productKosan'])
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
            })
            ->latest()
            ->take(5)
            ->get();

        $recentActivities = [];
        if ($recentTamus->isNotEmpty()) {
            foreach ($recentTamus as $t) {
                $kamarTitle = $t->productKamarKosan->room ?? 'Kamar 02';
                $kosanTitle = $t->productKamarKosan->productKosan->title ?? 'Kos Tuwek';
                $timeFormatted = $t->created_at ? ($t->created_at->isToday() ? $t->created_at->format('H:i') : ($t->created_at->isYesterday() ? 'Kemarin' : $t->created_at->isoFormat('D MMM'))) : '10:42';

                if ($t->status === 'approved') {
                    $recentActivities[] = [
                        'time'     => $timeFormatted,
                        'title'    => "Booking {$kamarTitle} disetujui",
                        'property' => $kosanTitle,
                        'icon'     => 'bi-check-circle',
                    ];
                    $recentActivities[] = [
                        'time'     => $timeFormatted,
                        'title'    => 'Pembayaran sewa diterima',
                        'property' => 'Rp ' . number_format($t->total_price, 0, ',', '.'),
                        'icon'     => 'bi-cash-stack',
                    ];
                } else {
                    $recentActivities[] = [
                        'time'     => $timeFormatted,
                        'title'    => "Booking {$kamarTitle} baru masuk",
                        'property' => $kosanTitle,
                        'icon'     => 'bi-inbox',
                    ];
                }
            }
        }

        $recentActivities = array_slice($recentActivities, 0, 5);

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
            'operationalAlerts',
            'revenueTrend',
            'recentActivities',
            'propertiesOverview'
        ));
    }
}
