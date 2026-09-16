@extends('backend.dashboard.main')

@section('content')

@php
    $hour = (int)date('H');
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $totalAllBookings = $pendingBookingsCount + $approvedBookingsCount;
    $approvalPercent = $totalAllBookings > 0 ? round(($approvedBookingsCount / $totalAllBookings) * 100) : 100;
    $pendingPercent = 100 - $approvalPercent;
@endphp

<!-- Header & Welcome Banner -->
<div class="app-content-header pb-2 pt-3">
    <div class="container-fluid">
        <div class="welcome-banner mb-3">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-lg-7 mb-3 mb-lg-0">
                    <div class="banner-pill mb-2.5">
                        <span class="pulse-dot"></span>
                        <span class="fw-semibold text-white">
                            {{ Auth::user()->isSuperAdmin() ? 'Super Admin Workspace' : 'Admin Cabang Workspace' }}
                        </span>
                        <span class="text-white opacity-50">•</span>
                        <span class="text-white">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>

                    <h2 class="fw-extrabold mb-1.5 text-white tracking-tight" style="font-size: clamp(1.35rem, 2.4vw, 1.85rem);">
                        {{ $greeting }}, {{ Auth::user()->name ?? 'Administrator' }} 👋
                    </h2>
                    <p class="text-white text-opacity-80 mb-0 fs-7" style="max-width: 620px; line-height: 1.55;">
                        @if(Auth::user()->isSuperAdmin())
                            Akses kendali penuh: pantau seluruh unit cabang properti kos, tinjau permintaan booking sewa, dan kelola penugasan admin cabang.
                        @else
                            Panel operasional cabang: Anda mengelola <strong>{{ $totalKosan }} properti kos</strong> dengan kapasitas <strong>{{ $totalKamar }} unit kamar</strong>.
                        @endif
                    </p>
                </div>

                <div class="col-lg-5 text-lg-end">
                    <div class="d-flex flex-wrap justify-content-lg-end align-items-center gap-2">
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.users.index') }}" class="btn-banner btn-banner-glass">
                                <i class="bi bi-shield-lock-fill text-warning"></i>
                                <span>Kelola Admin</span>
                            </a>
                        @endif

                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.product.kosan.index') }}" class="btn-banner btn-banner-primary">
                                <i class="bi bi-plus-circle-fill"></i>
                                <span>Tambah Kos</span>
                            </a>
                        @else
                            <a href="{{ route('admin.product.kosan.index') }}" class="btn-banner btn-banner-primary">
                                <i class="bi bi-houses-fill"></i>
                                <span>Properti Ditugaskan</span>
                            </a>
                        @endif

                        <a href="{{ route('admin.booking.index') }}" class="btn-banner btn-banner-warning">
                            <i class="bi bi-bell-fill"></i>
                            <span>Review Booking</span>
                            @if($pendingBookingsCount > 0)
                                <span class="badge bg-danger text-white rounded-pill px-1.5 py-0.5 ms-0.5" style="font-size: 0.68rem; line-height: 1;">{{ $pendingBookingsCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Content -->
<div class="app-content">
    <div class="container-fluid">

        <!-- 4 Primary KPI Cards (Real Data Only) -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Kosan Aktif -->
            <div class="col-6 col-lg-3">
                <div class="stat-card stat-teal h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="stat-label">Properti Kos</span>
                            <div class="stat-value">{{ number_format($totalKosan) }}</div>
                            <div class="mt-2">
                                <span class="badge badge-subtle-primary fs-8">
                                    <i class="bi bi-building-check me-1"></i> {{ $totalKosan > 0 ? 'Aktif Beroperasi' : 'Belum Ada' }}
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-houses-fill"></i>
                        </div>
                    </div>
                    <div class="border-top pt-2 mt-3">
                        <a href="{{ route('admin.product.kosan.index') }}" class="text-decoration-none fs-8 fw-bold text-primary d-inline-flex align-items-center gap-1">
                            <span>Kelola Properti</span>
                            <i class="bi bi-chevron-right fs-9"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Total Unit Kamar -->
            <div class="col-6 col-lg-3">
                <div class="stat-card stat-emerald h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="stat-label">Total Unit Kamar</span>
                            <div class="stat-value">{{ number_format($totalKamar) }}</div>
                            <div class="mt-2">
                                <span class="badge badge-subtle-success fs-8">
                                    <i class="bi bi-door-closed me-1"></i> Kamar Terdaftar
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                    </div>
                    <div class="border-top pt-2 mt-3">
                        <a href="{{ route('admin.product.kosan.index') }}" class="text-decoration-none fs-8 fw-bold text-success d-inline-flex align-items-center gap-1">
                            <span>Lihat Unit Kamar</span>
                            <i class="bi bi-chevron-right fs-9"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Permintaan Booking Pending -->
            <div class="col-6 col-lg-3">
                <div class="stat-card stat-amber h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="stat-label">Booking Pending</span>
                            <div class="stat-value text-warning">{{ number_format($pendingBookingsCount) }}</div>
                            <div class="mt-2">
                                @if($pendingBookingsCount > 0)
                                    <span class="badge badge-subtle-danger fs-8">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i> Perlu Review
                                    </span>
                                @else
                                    <span class="badge badge-subtle-success fs-8">
                                        <i class="bi bi-check-circle-fill me-1"></i> Terproses Semua
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="border-top pt-2 mt-3">
                        <a href="{{ route('admin.booking.index') }}" class="text-decoration-none fs-8 fw-bold text-warning d-inline-flex align-items-center gap-1">
                            <span>Review Booking</span>
                            <i class="bi bi-chevron-right fs-9"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Booking Disetujui / Terkonfirmasi -->
            <div class="col-6 col-lg-3">
                <div class="stat-card stat-cyan h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="stat-label">Booking Disetujui</span>
                            <div class="stat-value text-info">{{ number_format($approvedBookingsCount) }}</div>
                            <div class="mt-2">
                                <span class="badge badge-subtle-info fs-8">
                                    <i class="bi bi-patch-check-fill me-1"></i> Terkonfirmasi
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                    <div class="border-top pt-2 mt-3">
                        <a href="{{ route('admin.booking.index') }}" class="text-decoration-none fs-8 fw-bold text-info d-inline-flex align-items-center gap-1">
                            <span>Lihat Riwayat Sewa</span>
                            <i class="bi bi-chevron-right fs-9"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operational Analytics & Distribution Row (Real Data) -->
        <div class="row g-3 mb-4">
            <!-- Booking Status Distribution -->
            <div class="col-lg-7">
                <div class="operational-metric-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-pie-chart-fill text-primary"></i>
                                <span class="fw-bold fs-7 text-body-emphasis">Tingkat Penyelesaian Reservasi</span>
                            </div>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 fs-8 fw-bold">
                                {{ $totalAllBookings }} Total Reservasi
                            </span>
                        </div>
                        <p class="text-muted fs-8 mb-2">Perbandingan antara booking sewa yang telah disetujui terhadap booking yang sedang menunggu konfirmasi.</p>

                        <!-- Minimalist Stacked Progress Bar -->
                        <div class="status-pill-bar">
                            <div style="width: {{ $approvalPercent }}%; background: #0d9488;" title="Disetujui: {{ $approvedBookingsCount }} ({{ $approvalPercent }}%)"></div>
                            <div style="width: {{ $pendingPercent }}%; background: #e11d48;" title="Pending: {{ $pendingBookingsCount }} ({{ $pendingPercent }}%)"></div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-2 border-top">
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #0d9488;"></span>
                            <span class="fs-8 text-secondary">Terkonfirmasi: <strong>{{ $approvedBookingsCount }}</strong> ({{ $approvalPercent }}%)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #e11d48;"></span>
                            <span class="fs-8 text-secondary">Menunggu Review: <strong>{{ $pendingBookingsCount }}</strong> ({{ $pendingPercent }}%)</span>
                        </div>
                        <div>
                            <a href="{{ route('admin.booking.index') }}" class="text-decoration-none fs-8 text-primary fw-bold d-inline-flex align-items-center gap-1">
                                <span>Buka Manajemen Booking</span>
                                <i class="bi bi-arrow-right fs-9"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property & Room Capacity Summary -->
            <div class="col-lg-5">
                <div class="operational-metric-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-bar-chart-steps text-success"></i>
                                <span class="fw-bold fs-7 text-body-emphasis">Ringkasan Kapasitas Hunian</span>
                            </div>
                            <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 fs-8 fw-bold">
                                {{ $totalKosan }} Properti Kos
                            </span>
                        </div>
                        <p class="text-muted fs-8 mb-3">Rata-rata kapasitas per cabang dan total saluran konten publik yang aktif.</p>

                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-body-tertiary border">
                                    <div class="fs-9 text-muted text-uppercase fw-bold">Rata-rata</div>
                                    <div class="fs-7 fw-extrabold text-body-emphasis mt-1">
                                        {{ $totalKosan > 0 ? round($totalKamar / $totalKosan, 1) : 0 }}
                                    </div>
                                    <div class="fs-9 text-muted">Kamar/Kos</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-body-tertiary border">
                                    <div class="fs-9 text-muted text-uppercase fw-bold">Artikel Blog</div>
                                    <div class="fs-7 fw-extrabold text-body-emphasis mt-1">{{ $totalArtikel }}</div>
                                    <div class="fs-9 text-muted">Publikasi</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-body-tertiary border">
                                    <div class="fs-9 text-muted text-uppercase fw-bold">Ulasan Tamu</div>
                                    <div class="fs-7 fw-extrabold text-body-emphasis mt-1">{{ $totalTestimoni }}</div>
                                    <div class="fs-9 text-muted">Review</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-top mt-2">
                        <small class="text-muted fs-9 d-flex align-items-center justify-content-between">
                            <span>Sistem Sinar Citra Lestari</span>
                            <span class="text-success"><i class="bi bi-shield-fill-check me-1"></i>Operasional Normal</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Layout (2 Columns) -->
        <div class="row g-3 mb-4">

            <!-- LEFT COLUMN: Transactions & Managed Properties -->
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-3">

                    <!-- SECTION 1: PERMINTAAN BOOKING TERBARU -->
                    <div class="card shadow-sm border-0 mb-0" style="border-radius: 16px;">
                        <div class="card-header py-3 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stat-icon-wrapper bg-primary-subtle text-primary shrink-0" style="width: 38px; height: 38px; font-size: 1.15rem; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-calendar-event-fill"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h5 class="fs-6 fw-bold text-body-emphasis mb-0.5">Permintaan Booking Sewa Terbaru</h5>
                                    <span class="text-muted fs-8">Transaksi sewa kamar yang masuk dari calon penghuni</span>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('admin.booking.index') }}" class="btn btn-xs btn-outline-primary fw-semibold px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5">
                                    <span>Lihat Semua</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 table-min-dashboard">
                                    <thead>
                                        <tr>
                                            <th style="width: 28%;">Pemesan & Kontak</th>
                                            <th style="width: 25%;">Properti & Kamar</th>
                                            <th style="width: 20%;">Periode Sewa</th>
                                            <th style="width: 15%;">Total Biaya</th>
                                            <th class="text-center" style="width: 12%;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentBookings as $b)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2.5">
                                                        <div class="shrink-0" style="width: 36px; height: 36px; font-size: 0.825rem; background: rgba(13, 148, 136, 0.15); color: var(--nk-primary); font-weight: 700; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(13, 148, 136, 0.25);">
                                                            {{ strtoupper(substr($b->name, 0, 1)) }}
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <div class="table-cell-title text-body-emphasis text-truncate" style="max-width: 150px;">{{ $b->name }}</div>
                                                            <a href="https://wa.me/{{ $b->whatsapp_number }}" target="_blank" class="table-cell-sub text-decoration-none text-success fw-semibold d-inline-flex align-items-center gap-1" title="Hubungi via WhatsApp">
                                                                <i class="bi bi-whatsapp"></i>
                                                                <span>{{ $b->telp }}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-cell-title text-body-emphasis text-truncate" style="max-width: 180px;" title="{{ $b->productKamarKosan->productKosan->title ?? '-' }}">
                                                        {{ $b->productKamarKosan->productKosan->title ?? '-' }}
                                                    </div>
                                                    <div class="mt-0.5">
                                                        <span class="badge badge-subtle-secondary fs-8 fw-semibold px-2 py-0.5 rounded-pill">
                                                            <i class="bi bi-door-closed text-primary me-1"></i>Kamar: {{ $b->productKamarKosan->room ?? '-' }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-body-emphasis fs-8">
                                                        {{ \Carbon\Carbon::parse($b->start_date)->isoFormat('D MMM Y') }}
                                                    </div>
                                                    <div class="text-muted fs-9">
                                                        s/d {{ \Carbon\Carbon::parse($b->end_date)->isoFormat('D MMM Y') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-body-emphasis fs-8">
                                                        Rp {{ number_format((float)$b->total_price, 0, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-column align-items-center gap-1">
                                                        @if($b->status === 'pending')
                                                            <span class="badge badge-subtle-warning fs-8 px-2 py-1">
                                                                <i class="bi bi-clock-history me-1"></i> Pending
                                                            </span>
                                                            <a href="{{ route('admin.booking.index') }}" class="btn btn-xs btn-primary fw-semibold px-2.5 py-0.5 rounded-pill mt-0.5 d-inline-flex align-items-center gap-1">
                                                                <i class="bi bi-eye-fill"></i> Review
                                                            </a>
                                                        @elseif($b->status === 'approved')
                                                            <span class="badge badge-subtle-success fs-8 px-2 py-1">
                                                                <i class="bi bi-check-circle-fill me-1"></i> Approved
                                                            </span>
                                                        @else
                                                            <span class="badge badge-subtle-danger fs-8 px-2 py-1">
                                                                {{ ucfirst($b->status) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    <div class="empty-state-box">
                                                        <div class="empty-state-icon">
                                                            <i class="bi bi-inbox"></i>
                                                        </div>
                                                        <h6 class="fw-bold text-body-emphasis mb-1">Belum Ada Permintaan Booking</h6>
                                                        <p class="text-muted fs-8 mb-3">Permintaan booking dari calon penghuni kos akan langsung muncul di sini.</p>
                                                        <a href="{{ route('admin.product.kosan.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                            <i class="bi bi-building me-1"></i> Periksa Daftar Kosan
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 1.5: PERINGATAN SEWA JATUH TEMPO (EXPIRING TENANCIES & RENEWAL WARNING) -->
                    <div class="card shadow-sm border-0 mb-0" style="border-radius: 16px;">
                        <div class="card-header py-3 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stat-icon-wrapper bg-warning-subtle text-warning shrink-0" style="width: 38px; height: 38px; font-size: 1.15rem; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-alarm-fill"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center gap-2">
                                        <h5 class="fs-6 fw-bold text-body-emphasis mb-0.5">Peringatan Sewa Jatuh Tempo</h5>
                                        @if(isset($expiringTenancies) && $expiringTenancies->count() > 0)
                                            <span class="badge bg-danger rounded-pill px-2 py-0.5 fs-9 fw-bold">
                                                {{ $expiringTenancies->count() }} Perlu Ditagih
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-muted fs-8">Kamar dengan masa sewa segera berakhir (&le; 7 hari) atau melewati batas jatuh tempo</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-subtle-secondary fs-8">
                                    <i class="bi bi-arrow-repeat me-1 text-success"></i>Siap Diperpanjang
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 table-min-dashboard">
                                    <thead>
                                        <tr>
                                            <th style="width: 28%;">Penyewa & Kontak</th>
                                            <th style="width: 26%;">Kosan & Kamar</th>
                                            <th style="width: 18%;">Jatuh Tempo</th>
                                            <th style="width: 13%;">Status Sisa</th>
                                            <th class="text-center" style="width: 15%;">Aksi Tagihan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expiringTenancies ?? [] as $exp)
                                            @php
                                                $endCarbon = \Carbon\Carbon::parse($exp->end_date)->startOfDay();
                                                $today = now()->startOfDay();
                                                $diffDays = $today->diffInDays($endCarbon, false);

                                                $cleanTelp = preg_replace('/[^0-9]/', '', $exp->telp);
                                                if (str_starts_with($cleanTelp, '0')) {
                                                    $cleanTelp = '62' . substr($cleanTelp, 1);
                                                }
                                                $kosanName = $exp->productKamarKosan->productKosan->title ?? 'Sinar Citra Lestari';
                                                $roomName = $exp->productKamarKosan->room ?? '-';
                                                $endDateFormatted = \Carbon\Carbon::parse($exp->end_date)->isoFormat('D MMMM Y');
                                                $nominalFormatted = number_format((float)$exp->total_price, 0, ',', '.');
                                                
                                                $textMsg = "Halo Kak *{$exp->name}*,\n\n"
                                                         . "Kami dari pengelola *{$kosanName}* (Sinar Citra Lestari).\n"
                                                         . "Mengingatkan bahwa masa sewa kamar *{$roomName}* akan berakhir pada *{$endDateFormatted}*.\n\n"
                                                         . "Apakah Kakak berencana untuk memperpanjang sewa untuk periode berikutnya?\n"
                                                         . "Perkiraan biaya perpanjangan: *Rp {$nominalFormatted}*.\n"
                                                         . "Pembayaran dapat dilakukan melalui transfer rekening atau tunai ke pengelola kos di lokasi.\n\n"
                                                         . "Mohon konfirmasinya ya Kak. Terima kasih! 🙏";
                                                $waLink = "https://wa.me/{$exp->whatsapp_number}?text=" . rawurlencode($textMsg);
                                                $kamarUrl = route('admin.product.kosan.kamar.index', [
                                                    'product_kosan' => $exp->productKamarKosan->product_kosan_id,
                                                    'open_tamu_kamar' => $exp->product_kamar_kosan_id
                                                ]);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="user-avatar-badge" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                            {{ strtoupper(substr($exp->name, 0, 1)) }}
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <div class="fw-bold fs-8 text-body-emphasis text-truncate" style="max-width: 140px;">
                                                                {{ $exp->name }}
                                                            </div>
                                                            <small class="text-muted fs-9 d-block text-truncate">
                                                                <i class="bi bi-telephone me-0.5"></i>{{ $exp->telp }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold fs-8 text-body-emphasis text-truncate" style="max-width: 160px;">
                                                        {{ $kosanName }}
                                                    </div>
                                                    <span class="badge badge-subtle-primary fs-9 px-2 py-0.5 rounded-pill">
                                                        <i class="bi bi-door-closed me-0.5"></i>Kamar {{ $roomName }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold fs-8 text-body-emphasis">
                                                        {{ \Carbon\Carbon::parse($exp->end_date)->isoFormat('D MMM Y') }}
                                                    </div>
                                                    <small class="text-muted fs-9">Rp {{ $nominalFormatted }}</small>
                                                </td>
                                                <td>
                                                    @if($diffDays < 0)
                                                        <span class="badge bg-danger text-white rounded-pill px-2 py-1 fs-9 fw-bold">
                                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Lewat {{ abs($diffDays) }} Hari
                                                        </span>
                                                    @elseif($diffDays === 0)
                                                        <span class="badge bg-danger text-white rounded-pill px-2 py-1 fs-9 fw-bold animate-pulse">
                                                            <i class="bi bi-bell-fill me-1"></i>Hari Ini
                                                        </span>
                                                    @elseif($diffDays === 1)
                                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1 fs-9 fw-bold">
                                                            <i class="bi bi-clock-history me-1"></i>Besok Habis
                                                        </span>
                                                    @else
                                                        <span class="badge badge-subtle-warning rounded-pill px-2 py-1 fs-9 fw-bold text-amber">
                                                            <i class="bi bi-hourglass-split me-1"></i>{{ $diffDays }} Hari Lagi
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-1.5 flex-nowrap">
                                                        <a href="{{ $waLink }}" target="_blank" class="btn btn-xs btn-outline-success fw-semibold px-2 py-1 rounded-pill d-inline-flex align-items-center gap-1" title="Kirim Tagihan via WhatsApp">
                                                            <i class="bi bi-whatsapp"></i>
                                                            <span class="d-none d-xl-inline">Tagih WA</span>
                                                        </a>
                                                        <a href="{{ $kamarUrl }}" class="btn btn-xs btn-primary fw-semibold px-2 py-1 rounded-pill d-inline-flex align-items-center gap-1" title="Buka Detail Kamar untuk Perpanjang">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                            <span class="d-none d-xl-inline">Perpanjang</span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    <div class="empty-state-box py-3">
                                                        <div class="empty-state-icon text-success">
                                                            <i class="bi bi-shield-check"></i>
                                                        </div>
                                                        <h6 class="fw-bold text-body-emphasis mb-1">Semua Masa Sewa Terkendali</h6>
                                                        <p class="text-muted fs-8 mb-0">Tidak ada masa sewa penghuni yang jatuh tempo dalam 7 hari ke depan.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: PROPERTI KOSAN YANG DIKELOLA (RECENT KOSANS) -->
                    <div class="card shadow-sm border-0 mb-0" style="border-radius: 16px;">
                        <div class="card-header py-3 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stat-icon-wrapper bg-success-subtle text-success shrink-0" style="width: 38px; height: 38px; font-size: 1.15rem; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-building-fill"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h5 class="fs-6 fw-bold text-body-emphasis mb-0.5">Daftar Properti Kos Aktif</h5>
                                    <span class="text-muted fs-8">Cabang kosan dan ketersediaan unit kamar yang Anda kelola</span>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('admin.product.kosan.index') }}" class="btn btn-xs btn-outline-success fw-semibold px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5">
                                    <span>Lihat Semua Kos</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 table-min-dashboard">
                                    <thead>
                                        <tr>
                                            <th style="width: 44%;">Nama Properti Kos</th>
                                            <th style="width: 24%;">Wilayah / Lokasi</th>
                                            <th style="width: 18%;">Kapasitas</th>
                                            <th class="text-center" style="width: 14%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentKosans as $k)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2.5">
                                                        <div class="property-icon-box shrink-0" style="width: 40px; height: 40px; border-radius: 10px; background: rgba(13, 148, 136, 0.08); border: 1px solid rgba(13, 148, 136, 0.18); display: flex; align-items: center; justify-content: center; color: var(--nk-primary); font-size: 1.15rem;">
                                                            <i class="bi bi-house-door-fill"></i>
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <a href="{{ route('admin.product.kosan.kamar.index', $k->id) }}" class="table-cell-title text-decoration-none text-body-emphasis hover-primary fw-bold d-block text-truncate" style="max-width: 240px;">
                                                                {{ $k->title }}
                                                            </a>
                                                            <div class="table-cell-sub text-muted text-truncate mt-0.5" style="max-width: 240px;">
                                                                <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $k->alamat ?? 'Alamat belum diatur' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-inline-flex align-items-center gap-1 text-secondary fw-semibold fs-8">
                                                        <i class="bi bi-pin-map-fill text-primary"></i>
                                                        <span>{{ $k->wilayah ?? 'Bali' }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($k->product_kamar_kosan_count > 0)
                                                        <span class="badge badge-subtle-success fs-8 fw-semibold px-2 py-1 rounded-pill">
                                                            <i class="bi bi-door-open-fill me-1"></i>{{ $k->product_kamar_kosan_count }} Kamar
                                                        </span>
                                                    @else
                                                        <span class="badge badge-subtle-secondary fs-8 fw-normal px-2 py-1 rounded-pill">
                                                            <i class="bi bi-door-closed me-1"></i>0 Kamar
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.product.kosan.kamar.index', $k->id) }}" class="btn btn-xs btn-outline-primary fw-semibold px-2.5 py-1 rounded-pill d-inline-flex align-items-center gap-1">
                                                        <i class="bi bi-door-open"></i>
                                                        <span>Kelola</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <div class="empty-state-box py-3">
                                                        <div class="empty-state-icon">
                                                            <i class="bi bi-houses"></i>
                                                        </div>
                                                        <h6 class="fw-bold text-body-emphasis mb-1">Belum Ada Properti Kos</h6>
                                                        <p class="text-muted fs-8 mb-2">Belum ada properti kos yang ditugaskan kepada akun ini.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT COLUMN: Quick Shortcuts, Articles & System Status -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-3">

                    <!-- WIDGET 1: PINTASAN MENU CEPAT -->
                    <div class="card shadow-sm border-0 mb-0" style="border-radius: 16px;">
                        <div class="card-header py-3 border-bottom">
                            <h5 class="fs-6 fw-bold text-body-emphasis mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-lightning-charge-fill text-warning"></i>
                                <span>Pintasan Akses Cepat</span>
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">

                                @if(Auth::user()->isSuperAdmin())
                                    <a href="{{ route('admin.users.index') }}" class="quick-action-item">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="quick-action-icon bg-danger-subtle text-danger">
                                                <i class="bi bi-person-gear"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold fs-8 text-body-emphasis">Kelola Admin & Cabang</div>
                                                <small class="text-muted fs-9">Atur hak akses & penugasan</small>
                                            </div>
                                        </div>
                                        <i class="bi bi-arrow-right text-muted fs-8"></i>
                                    </a>
                                @endif

                                @if(Auth::user()->isSuperAdmin())
                                    <a href="{{ route('admin.product.kosan.index') }}" class="quick-action-item">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="quick-action-icon bg-primary-subtle text-primary">
                                                <i class="bi bi-plus-circle-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold fs-8 text-body-emphasis">Tambah Properti Kos</div>
                                                <small class="text-muted fs-9">Input data cabang kos baru</small>
                                            </div>
                                        </div>
                                        <i class="bi bi-arrow-right text-muted fs-8"></i>
                                    </a>
                                @else
                                    <a href="{{ route('admin.product.kosan.index') }}" class="quick-action-item">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="quick-action-icon bg-primary-subtle text-primary">
                                                <i class="bi bi-houses-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold fs-8 text-body-emphasis">Kelola Properti Saya</div>
                                                <small class="text-muted fs-9">Kelola kamar, galeri & tarif sewa</small>
                                            </div>
                                        </div>
                                        <i class="bi bi-arrow-right text-muted fs-8"></i>
                                    </a>
                                @endif

                                <a href="{{ route('admin.booking.index') }}" class="quick-action-item">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="quick-action-icon bg-warning-subtle text-warning">
                                            <i class="bi bi-check2-circle"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-8 text-body-emphasis">Verifikasi Pembayaran</div>
                                            <small class="text-muted fs-9">Cek bukti transfer booking</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-arrow-right text-muted fs-8"></i>
                                </a>

                                <a href="{{ route('admin.artikel.index') }}" class="quick-action-item">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="quick-action-icon bg-info-subtle text-info">
                                            <i class="bi bi-pencil-square"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-8 text-body-emphasis">Tulis Artikel / Promo</div>
                                            <small class="text-muted fs-9">Publikasikan informasi sewa</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-arrow-right text-muted fs-8"></i>
                                </a>

                                <a href="{{ route('admin.testimoni.index') }}" class="quick-action-item">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="quick-action-icon bg-success-subtle text-success">
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-8 text-body-emphasis">Testimoni & Review</div>
                                            <small class="text-muted fs-9">Ulasan penghuni kos</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-arrow-right text-muted fs-8"></i>
                                </a>

                            </div>
                        </div>
                    </div>

                    <!-- WIDGET 2: ARTIKEL & BERITA TERKINI -->
                    <div class="card shadow-sm border-0 mb-0" style="border-radius: 16px;">
                        <div class="card-header py-3 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="fs-6 fw-bold text-body-emphasis mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-newspaper text-danger"></i>
                                <span>Artikel & Berita Terkini</span>
                            </h5>
                            <a href="{{ route('admin.artikel.index') }}" class="btn btn-xs btn-outline-danger fw-semibold px-2.5 py-1 rounded-pill">
                                Kelola
                            </a>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-column gap-2.5">
                                @forelse($recentArtikels as $art)
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 border border-light-subtle article-item-hover">
                                        <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                            <div class="shrink-0" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; background: #e2e8f0;">
                                                @if($art->image)
                                                    <img src="{{ asset('storage/' . $art->image) }}" alt="{{ $art->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="font-size: 0.8rem;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="overflow-hidden">
                                                <a href="{{ route('admin.artikel.index') }}" class="fw-bold fs-8 text-decoration-none text-body-emphasis d-block text-truncate hover-primary" style="max-width: 180px;">
                                                    {{ $art->title }}
                                                </a>
                                                <small class="text-muted fs-9">
                                                    <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($art->created_at)->isoFormat('D MMM Y') }}
                                                </small>
                                            </div>
                                        </div>
                                        <span class="badge badge-subtle-secondary fs-9 fw-semibold px-2 py-0.5 rounded-pill shrink-0">
                                            {{ $art->view ?? 0 }} Views
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        <i class="bi bi-journal-x display-6 d-block mb-1 text-secondary opacity-50"></i>
                                        <span class="fs-8">Belum ada artikel yang dipublikasikan.</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- WIDGET 3: STATUS SISTEM & SALURAN KOMUNIKASI -->
                    <div class="card shadow-sm border-0 mb-0" style="border-radius: 16px;">
                        <div class="card-header py-3 border-bottom">
                            <h5 class="fs-6 fw-bold text-body-emphasis mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-hdd-network-fill text-info"></i>
                                <span>Status Operasional</span>
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-column gap-2">

                                <div class="d-flex justify-content-between align-items-center p-2 rounded-2 border border-light-subtle article-item-hover">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-patch-check-fill text-success fs-7"></i>
                                        <span class="text-secondary fs-8">Status Sistem</span>
                                    </div>
                                    <span class="badge badge-subtle-success fs-9 fw-bold px-2 py-0.5 rounded-pill">
                                        Semua Aktif
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center p-2 rounded-2 border border-light-subtle article-item-hover">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-share-fill text-primary fs-7"></i>
                                        <span class="text-secondary fs-8">Saluran Sosial Media</span>
                                    </div>
                                    <span class="badge badge-subtle-secondary fs-9 fw-semibold px-2 py-0.5 rounded-pill">
                                        {{ $totalSosmed }} Saluran Aktif
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center p-2 rounded-2 border border-light-subtle article-item-hover">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-shield-check text-danger fs-7"></i>
                                        <span class="text-secondary fs-8">Peran Akun</span>
                                    </div>
                                    <span class="badge {{ Auth::user()->isSuperAdmin() ? 'badge-subtle-danger' : 'badge-subtle-primary' }} fs-9 fw-bold px-2 py-0.5 rounded-pill">
                                        {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin Cabang' }}
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

@endsection
