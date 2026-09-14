@extends('backend.dashboard.main')

@section('content')

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

                    <h2 class="fw-extrabold mb-1.5 text-white tracking-tight" style="font-size: clamp(1.35rem, 2.5vw, 1.85rem);">
                        Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }} 👋
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

                        <a href="{{ route('admin.product.kosan.index') }}" class="btn-banner btn-banner-primary">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span>Tambah Kos</span>
                        </a>

                        <a href="{{ route('admin..booking.index') }}" class="btn-banner btn-banner-warning">
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

        <!-- Stat Widgets Row (KPIs) -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Kosan -->
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

            <!-- 2. Total Kamar -->
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

            <!-- 3. Pending Booking -->
            <div class="col-6 col-lg-3">
                <div class="stat-card stat-amber h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="stat-label">Permintaan Sewa</span>
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
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                    <div class="border-top pt-2 mt-3">
                        <a href="{{ route('admin..booking.index') }}" class="text-decoration-none fs-8 fw-bold text-warning d-inline-flex align-items-center gap-1">
                            <span>Review Booking</span>
                            <i class="bi bi-chevron-right fs-9"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Artikel & Testimoni -->
            <div class="col-6 col-lg-3">
                <div class="stat-card stat-rose h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="stat-label">Artikel & Review</span>
                            <div class="stat-value">{{ number_format($totalArtikel) }} <span class="fs-7 text-muted fw-normal">Blog</span></div>
                            <div class="mt-2">
                                <span class="badge badge-subtle-info fs-8">
                                    <i class="bi bi-star-fill text-warning me-1"></i> {{ $totalTestimoni }} Review
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-newspaper"></i>
                        </div>
                    </div>
                    <div class="border-top pt-2 mt-3">
                        <a href="{{ route('admin.artikel.index') }}" class="text-decoration-none fs-8 fw-bold text-danger d-inline-flex align-items-center gap-1">
                            <span>Kelola Konten</span>
                            <i class="bi bi-chevron-right fs-9"></i>
                        </a>
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
                                <a href="{{ route('admin..booking.index') }}" class="btn btn-xs btn-outline-primary fw-semibold px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5">
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
                                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $b->telp) }}" target="_blank" class="table-cell-sub text-decoration-none text-success fw-semibold d-inline-flex align-items-center gap-1">
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
                                                            <a href="{{ route('admin..booking.index') }}" class="btn btn-xs btn-primary fw-semibold px-2.5 py-0.5 rounded-pill mt-0.5 d-inline-flex align-items-center gap-1">
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
                                                    <div class="py-3">
                                                        <i class="bi bi-inbox display-6 d-block mb-2 text-secondary opacity-50"></i>
                                                        <h6 class="fw-bold text-body-emphasis">Belum Ada Permintaan Booking</h6>
                                                        <p class="text-muted fs-8 mb-0">Permintaan booking dari pengunjung akan langsung muncul di tabel ini.</p>
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
                                                    <i class="bi bi-houses display-6 d-block mb-2 text-secondary opacity-50"></i>
                                                    Belum ada properti kos yang ditugaskan kepada akun ini.
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
                                                <i class="bi bi-shield-lock-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold fs-7 text-body-emphasis">Kelola Admin & Cabang</div>
                                                <small class="text-muted fs-8">Atur akun & penugasan kos</small>
                                            </div>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted fs-8"></i>
                                    </a>
                                @endif

                                <a href="{{ route('admin.product.kosan.index') }}" class="quick-action-item">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="quick-action-icon bg-primary-subtle text-primary">
                                            <i class="bi bi-houses-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-7 text-body-emphasis">Kelola Properti Kos</div>
                                            <small class="text-muted fs-8">Daftar kosan, unit, fasilitas</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted fs-8"></i>
                                </a>

                                <a href="{{ route('admin..booking.index') }}" class="quick-action-item">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="quick-action-icon bg-warning-subtle text-warning">
                                            <i class="bi bi-calendar-check-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-7 text-body-emphasis">Verifikasi Booking</div>
                                            <small class="text-muted fs-8">Konfirmasi bukti transfer penyewa</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted fs-8"></i>
                                </a>

                                <a href="{{ route('admin.artikel.index') }}" class="quick-action-item">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="quick-action-icon bg-info-subtle text-info">
                                            <i class="bi bi-journal-text"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-7 text-body-emphasis">Artikel & Promo</div>
                                            <small class="text-muted fs-8">Publikasikan konten berita kos</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted fs-8"></i>
                                </a>

                                <a href="{{ route('admin.testimoni.index') }}" class="quick-action-item">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="quick-action-icon bg-success-subtle text-success">
                                            <i class="bi bi-chat-quote-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-7 text-body-emphasis">Testimoni Penghuni</div>
                                            <small class="text-muted fs-8">Rating dan ulasan kepuasan</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted fs-8"></i>
                                </a>

                            </div>
                        </div>
                    </div>

                    <!-- WIDGET 2: BERITA & ARTIKEL TERBARU -->
                    <div class="card shadow-sm border-0 mb-0" style="border-radius: 16px;">
                        <div class="card-header py-3 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="fs-6 fw-bold text-body-emphasis mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-newspaper text-info"></i>
                                <span>Artikel & Promosi</span>
                            </h5>
                            <a href="{{ route('admin.artikel.index') }}" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 fs-8 fw-semibold">
                                + Tulis Baru
                            </a>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-column gap-2">
                                @forelse($recentArtikels as $art)
                                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border border-light-subtle article-item-hover">
                                        <div class="d-flex align-items-center gap-2.5 text-truncate me-2">
                                            <div class="rounded-3 bg-body border p-1 d-flex align-items-center justify-content-center shrink-0 shadow-2xs" style="width: 36px; height: 36px;">
                                                <i class="bi bi-file-earmark-richtext text-info fs-6"></i>
                                            </div>
                                            <div class="text-truncate">
                                                <a href="{{ route('news.detail', $art->slug) }}" target="_blank" class="fw-bold fs-8 text-body-emphasis text-decoration-none hover-primary text-truncate d-block">
                                                    {{ $art->title }}
                                                </a>
                                                <div class="text-muted fs-9 d-flex align-items-center gap-2 mt-0.5">
                                                    <span>{{ \Carbon\Carbon::parse($art->created_at)->isoFormat('D MMM Y') }}</span>
                                                    <span>•</span>
                                                    <span><i class="bi bi-eye me-1"></i>{{ $art->view ?? 0 }} views</span>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('news.detail', $art->slug) }}" target="_blank" class="btn btn-xs btn-outline-secondary border shadow-2xs shrink-0" title="Buka Artikel" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                            <i class="bi bi-box-arrow-up-right fs-9"></i>
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-muted fs-8 text-center my-2">Belum ada artikel yang dipublikasikan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- WIDGET 3: STATUS OPERASIONAL SISTEM -->
                    <div class="card shadow-sm border-0 mb-0" style="border-radius: 16px;">
                        <div class="card-header py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h5 class="fs-6 fw-bold text-body-emphasis mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-cpu-fill text-primary"></i>
                                <span>Status Operasional</span>
                            </h5>
                            <span class="badge bg-success-subtle text-success fs-9 fw-semibold px-2 py-0.5 rounded-pill d-inline-flex align-items-center gap-1">
                                <span class="pulse-dot" style="width: 6px; height: 6px;"></span>
                                <span>Semua Aktif</span>
                            </span>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-column gap-2">
                                
                                <div class="d-flex justify-content-between align-items-center p-2 rounded-2 border border-light-subtle article-item-hover">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-hdd-network text-teal fs-7"></i>
                                        <span class="text-secondary fs-8">Aplikasi Web</span>
                                    </div>
                                    <span class="badge badge-subtle-secondary fs-9 fw-semibold px-2 py-0.5 rounded-pill">
                                        Laravel 11 • PHP 8.3
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center p-2 rounded-2 border border-light-subtle article-item-hover">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-database-check text-primary fs-7"></i>
                                        <span class="text-secondary fs-8">Basis Data MySQL</span>
                                    </div>
                                    <span class="badge badge-subtle-success fs-9 fw-semibold px-2 py-0.5 rounded-pill">
                                        Port 3306 (Terhubung)
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center p-2 rounded-2 border border-light-subtle article-item-hover">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-share text-warning fs-7"></i>
                                        <span class="text-secondary fs-8">Sosial Media</span>
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
