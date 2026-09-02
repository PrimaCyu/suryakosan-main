@extends('backend.dashboard.main')

@section('content')

<!-- Header Content -->
<div class="app-content-header pb-1">
    <div class="container-fluid">
        <!-- Welcome Banner -->
        <div class="welcome-banner mb-3">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-15 text-white mb-2" style="font-size: 0.775rem;">
                        <i class="bi bi-shield-check text-warning"></i>
                        <span>Panel Admin Workspace</span>
                        <span class="opacity-40">•</span>
                        <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <h2 class="fw-bold mb-1 text-white" style="font-size: clamp(1.25rem, 3vw, 1.65rem);">
                        Selamat Datang, {{ Auth::user()->name ?? 'Admin' }} 👋
                    </h2>
                    <p class="text-white text-opacity-80 mb-0 fs-7" style="max-width: 620px;">
                        Kelola ketersediaan kamar, verifikasi booking, publikasikan konten artikel, dan pantau performa kosan Anda.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <a href="{{ route('admin.product.kosan.index') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                            <i class="bi bi-plus-circle-fill text-primary me-1"></i> Tambah Kosan
                        </a>
                        <a href="{{ route('admin..booking.index') }}" class="btn btn-warning text-dark fw-bold shadow-sm">
                            <i class="bi bi-bell-fill me-1"></i> Review Booking
                            @if($pendingBookingsCount > 0)
                                <span class="badge bg-danger text-white rounded-pill ms-1">{{ $pendingBookingsCount }}</span>
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

        <!-- Stat Widgets Row -->
        <div class="row g-3 mb-4">
            <!-- Total Kosan -->
            <div class="col-6 col-md-3">
                <div class="stat-card stat-indigo h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="stat-label">Total Kosan</span>
                            <div class="stat-value">{{ number_format($totalKosan) }}</div>
                            <div class="mt-2">
                                <a href="{{ route('admin.product.kosan.index') }}" class="text-decoration-none fs-7 fw-semibold text-primary d-inline-flex align-items-center gap-1">
                                    Kelola <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-house-door-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kamar -->
            <div class="col-6 col-md-3">
                <div class="stat-card stat-emerald h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="stat-label">Total Unit Kamar</span>
                            <div class="stat-value">{{ number_format($totalKamar) }}</div>
                            <div class="mt-2">
                                <span class="badge badge-subtle-success fs-8">
                                    <i class="bi bi-check2 me-1"></i> Terdaftar
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Booking -->
            <div class="col-6 col-md-3">
                <div class="stat-card stat-amber h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="stat-label">Booking Pending</span>
                            <div class="stat-value text-warning">{{ number_format($pendingBookingsCount) }}</div>
                            <div class="mt-2">
                                @if($pendingBookingsCount > 0)
                                    <a href="{{ route('admin..booking.index') }}" class="badge badge-subtle-danger text-decoration-none fs-8">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i> Perlu Review
                                    </a>
                                @else
                                    <span class="badge badge-subtle-success fs-8">
                                        <i class="bi bi-check-all me-1"></i> Terproses
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel & Testimoni -->
            <div class="col-6 col-md-3">
                <div class="stat-card stat-cyan h-100">
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
                </div>
            </div>
        </div>

        <!-- Main Grid Row -->
        <div class="row g-3 mb-4">
            <!-- Left Column: Recent Bookings -->
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                                <i class="bi bi-calendar-event-fill fs-6"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fs-6 fw-bold">Permintaan Booking Terbaru</h5>
                                <p class="text-muted fs-8 mb-0">Daftar transaksi sewa yang baru masuk dari penyewa.</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin..booking.index') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 table-min-md">
                                <thead>
                                    <tr>
                                        <th>Pemesan</th>
                                        <th>Kosan & Kamar</th>
                                        <th>Tanggal Sewa</th>
                                        <th>Total Biaya</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBookings as $b)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="user-avatar-badge" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                        {{ strtoupper(substr($b->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="table-cell-title">{{ $b->name }}</div>
                                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $b->telp) }}" target="_blank" class="table-cell-sub text-decoration-none text-success">
                                                            <i class="bi bi-whatsapp me-1"></i>{{ $b->telp }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="table-cell-title">{{ $b->productKamarKosan->productKosan->title ?? '-' }}</div>
                                                <span class="table-cell-sub text-muted">Kamar: {{ $b->productKamarKosan->room ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="table-cell-title">{{ \Carbon\Carbon::parse($b->start_date)->isoFormat('D MMM Y') }}</span>
                                                <small class="d-block table-cell-sub">s/d {{ \Carbon\Carbon::parse($b->end_date)->isoFormat('D MMM Y') }}</small>
                                            </td>
                                            <td>
                                                <span class="table-cell-title text-primary">Rp {{ number_format((float)$b->total_price, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($b->status === 'pending')
                                                    <span class="badge badge-subtle-warning">
                                                        <i class="bi bi-clock-history me-1"></i> Pending
                                                    </span>
                                                @elseif($b->status === 'approved')
                                                    <span class="badge badge-subtle-success">
                                                        <i class="bi bi-check-circle me-1"></i> Approved
                                                    </span>
                                                @else
                                                    <span class="badge badge-subtle-secondary">
                                                        {{ ucfirst($b->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($b->status === 'pending')
                                                    <a href="{{ route('admin..booking.index') }}" class="btn btn-sm btn-primary py-1 px-2">
                                                        Review
                                                    </a>
                                                @else
                                                    <span class="text-muted fs-8">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox display-6 d-block mb-2 text-secondary opacity-50"></i>
                                                Belum ada data permintaan booking.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Shortcuts & Quick Info -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-3">
                    <!-- Quick Shortcuts Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0 fs-6 fw-bold">
                                <i class="bi bi-lightning-charge-fill text-warning me-2"></i> Pintasan Menu Cepat
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.product.kosan.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded-3 border text-decoration-none text-dark bg-body-tertiary">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-2 rounded-2 bg-primary text-white">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-7">Kelola Properti Kosan</div>
                                            <small class="text-muted">Daftar kosan, kamar, harga</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted fs-7"></i>
                                </a>

                                <a href="{{ route('admin..booking.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded-3 border text-decoration-none text-dark bg-body-tertiary">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-2 rounded-2 bg-success text-white">
                                            <i class="bi bi-calendar-check"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-7">Verifikasi Booking</div>
                                            <small class="text-muted">Cek bukti transfer pemesan</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted fs-7"></i>
                                </a>

                                <a href="{{ route('admin.artikel.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded-3 border text-decoration-none text-dark bg-body-tertiary">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-2 rounded-2 bg-info text-white">
                                            <i class="bi bi-journal-text"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-7">Artikel & Berita</div>
                                            <small class="text-muted">Publikasikan konten blog</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted fs-7"></i>
                                </a>

                                <a href="{{ route('admin.testimoni.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded-3 border text-decoration-none text-dark bg-body-tertiary">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-2 rounded-2 bg-warning text-dark">
                                            <i class="bi bi-chat-quote"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-7">Testimoni Penghuni</div>
                                            <small class="text-muted">Rating dan review pelanggan</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted fs-7"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- System Info Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0 fs-6 fw-bold">
                                <i class="bi bi-info-circle-fill text-primary me-2"></i> Ringkasan Sistem
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <ul class="list-group list-group-flush fs-7">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                                    <span class="text-muted">Status Server:</span>
                                    <span class="badge badge-subtle-success"><i class="bi bi-circle-fill fs-8 me-1"></i> Online & Siap</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                                    <span class="text-muted">Total Properti Aktif:</span>
                                    <span class="fw-bold text-dark">{{ $totalKosan }} Kos-kosan</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <span class="text-muted">Platform Sosial Media:</span>
                                    <span class="fw-bold text-dark">{{ $totalSosmed }} Akun</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
