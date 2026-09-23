@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                <div>
                    <h1 class="page-title mb-1">
                        <i class="bi bi-calendar-check text-secondary me-2"></i>Permintaan Booking
                    </h1>
                    <p class="page-subtitle mb-0">Verifikasi pemesanan unit kamar dan pemantauan riwayat transaksi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @include('backend.dashboard.kosan.kamar.partials.alerts')

        <!-- STATUS FILTER TABS (MINIMALIST & TOUCH-SCROLLABLE) -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div class="status-filter-nav">
                <a href="{{ route('admin.booking.index', array_merge(['status' => 'pending'], request()->except(['status', 'page']))) }}"
                   class="status-filter-btn {{ ($statusFilter ?? 'pending') === 'pending' ? 'active' : '' }}">
                    Menunggu Review
                    <span class="badge rounded-pill ms-1 {{ ($statusFilter ?? 'pending') === 'pending' ? 'bg-white text-dark' : 'bg-warning-subtle text-warning-emphasis' }}">
                        {{ $countPending ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('admin.booking.index', array_merge(['status' => 'approved'], request()->except(['status', 'page']))) }}"
                   class="status-filter-btn {{ ($statusFilter ?? '') === 'approved' ? 'active' : '' }}">
                    Disetujui
                    <span class="badge rounded-pill ms-1 {{ ($statusFilter ?? '') === 'approved' ? 'bg-white text-dark' : 'bg-secondary-subtle text-secondary' }}">
                        {{ $countApproved ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('admin.booking.index', array_merge(['status' => 'reject'], request()->except(['status', 'page']))) }}"
                   class="status-filter-btn {{ ($statusFilter ?? '') === 'reject' ? 'active' : '' }}">
                    Ditolak
                    <span class="badge rounded-pill ms-1 {{ ($statusFilter ?? '') === 'reject' ? 'bg-white text-dark' : 'bg-secondary-subtle text-secondary' }}">
                        {{ $countReject ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('admin.booking.index', array_merge(['status' => 'all'], request()->except(['status', 'page']))) }}"
                   class="status-filter-btn {{ ($statusFilter ?? '') === 'all' ? 'active' : '' }}">
                    Semua
                    <span class="badge rounded-pill ms-1 {{ ($statusFilter ?? '') === 'all' ? 'bg-white text-dark' : 'bg-secondary-subtle text-secondary' }}">
                        {{ $countAll ?? 0 }}
                    </span>
                </a>
            </div>

            @if(request('status') || request('search'))
                <a href="{{ route('admin.booking.index') }}" class="btn btn-xs btn-outline-secondary fs-8">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </a>
            @endif
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 py-3">
                <div>
                    <h5 class="card-title mb-0 fs-6 fw-bold">
                        <i class="bi bi-clock-history text-secondary me-2"></i>Daftar Transaksi
                    </h5>
                    <p class="text-muted fs-8 mb-0">Total {{ $bookings->total() }} data tercatat.</p>
                </div>
                <div class="search-box-responsive">
                    <form action="{{ route('admin.booking.index') }}" method="GET" class="input-group input-group-sm">
                        <input type="hidden" name="status" value="{{ $statusFilter ?? 'pending' }}">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama, telp, kamar..." value="{{ request('search') }}" autocomplete="off">
                        <button type="submit" class="btn btn-outline-secondary">Cari</button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- TABEL BERSIH & MINIMALIS -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-min-md" id="table-booking-list">
                        <thead>
                            <tr>
                                <th style="width: 45px" class="text-center">No</th>
                                <th>Pemesan</th>
                                <th>Unit Kamar</th>
                                <th>Periode & Biaya</th>
                                <th class="text-center" style="width: 90px">Bukti</th>
                                <th style="width: 140px">Status</th>
                                <th style="width: 140px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $index => $item)
                                <tr>
                                    <td class="text-center fw-semibold text-muted">{{ $bookings->firstItem() + $index }}</td>
                                    
                                    <!-- 1. PEMESAN -->
                                    <td>
                                        <div class="table-cell-title">{{ $item->name }}</div>
                                        <div class="text-muted fs-8 mt-0.5">
                                            <a href="https://wa.me/{{ $item->whatsapp_number }}" target="_blank" class="text-decoration-none text-success fw-semibold" title="Chat WhatsApp">
                                                <i class="bi bi-whatsapp me-0.5"></i>{{ $item->telp }}
                                            </a>
                                            <span class="mx-1 text-muted opacity-50">•</span>
                                            <span>{{ $item->email }}</span>
                                        </div>
                                    </td>

                                    <!-- 2. UNIT KAMAR -->
                                    <td>
                                        <div class="table-cell-title">{{ $item->productKamarKosan->productKosan->title ?? '-' }}</div>
                                        <small class="text-muted fs-8">Kamar {{ $item->productKamarKosan->room ?? '-' }}</small>
                                    </td>

                                    <!-- 3. PERIODE & BIAYA -->
                                    <td>
                                        <div class="table-cell-title text-primary fw-semibold">
                                            Rp {{ number_format((float)$item->total_price, 0, ',', '.') }}
                                        </div>
                                        <small class="table-cell-sub">
                                            {{ \Carbon\Carbon::parse($item->start_date)->isoFormat('D MMM') }} s/d {{ \Carbon\Carbon::parse($item->end_date)->isoFormat('D MMM Y') }}
                                        </small>
                                    </td>

                                    <!-- 4. BUKTI TRANSFER -->
                                    <td class="text-center">
                                        @if($item->proof_of_transfer)
                                            <button type="button" class="btn btn-sm btn-outline-info py-0.5 px-2 fs-8 rounded-2" data-bs-toggle="modal" data-bs-target="#modalProof{{ $item->id }}" title="Lihat Bukti Transfer">
                                                <i class="bi bi-image me-1"></i>Bukti
                                            </button>
                                        @else
                                            <span class="text-muted fs-8">-</span>
                                        @endif
                                    </td>

                                    <!-- 5. STATUS -->
                                    <td>
                                        @if($item->status === 'approved')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 fs-8 fw-semibold">
                                                Disetujui
                                            </span>
                                            <small class="text-muted fs-8 d-block mt-0.5 text-truncate" style="max-width: 130px;" title="Oleh: {{ $item->processedBy->name ?? 'Admin' }}">
                                                {{ $item->processedBy->name ?? 'Admin' }}
                                            </small>
                                        @elseif($item->status === 'reject')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 fs-8 fw-semibold">
                                                Ditolak
                                            </span>
                                            <small class="text-muted fs-8 d-block mt-0.5 text-truncate" style="max-width: 130px;" title="Oleh: {{ $item->processedBy->name ?? 'Admin' }}">
                                                {{ $item->processedBy->name ?? 'Admin' }}
                                            </small>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-0.5 fs-8 fw-semibold">
                                                Menunggu
                                            </span>
                                            <small class="text-muted fs-8 d-block mt-0.5">
                                                {{ $item->created_at ? $item->created_at->diffForHumans(null, true) : '-' }}
                                            </small>
                                        @endif
                                    </td>

                                    <!-- 6. AKSI -->
                                    <td class="text-center text-nowrap">
                                        @if($item->status === 'pending')
                                            <div class="d-inline-flex gap-1.5">
                                                <button type="button" class="btn btn-sm btn-primary py-0.5 px-2.5 rounded-2 fs-8 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalApprove{{ $item->id }}" title="Setujui Booking">
                                                    <i class="bi bi-check2"></i> Terima
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger py-0.5 px-2.5 rounded-2 fs-8" data-bs-toggle="modal" data-bs-target="#modalReject{{ $item->id }}" title="Tolak Booking">
                                                    <i class="bi bi-x"></i> Tolak
                                                </button>
                                            </div>
                                        @else
                                            <button type="button" class="btn btn-sm btn-light border py-0.5 px-2.5 rounded-2 text-secondary fs-8" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}" title="Lihat Rekam Jejak">
                                                <i class="bi bi-shield-check me-1"></i>Detail
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                                        Tidak ada transaksi booking pada kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2 py-2.5">
                <small class="text-muted fs-8">
                    Menampilkan {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} dari {{ $bookings->total() }} booking
                </small>
                <div>
                    {{ $bookings->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS PER ITEM (PROOF, APPROVE, REJECT, DETAIL AUDIT) -->
@foreach ($bookings as $item)

    <!-- MODAL PROOF OF TRANSFER -->
    @if($item->proof_of_transfer)
        <div class="modal fade" id="modalProof{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header py-3 border-bottom">
                        <h6 class="modal-title fw-bold text-dark mb-0">
                            <i class="bi bi-image text-secondary me-2"></i>Bukti Transfer: {{ $item->name }}
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body text-center p-3">
                        <img src="{{ asset('storage/' . $item->proof_of_transfer) }}" alt="Bukti Transfer" class="img-fluid rounded-2 border" style="max-height: 380px; object-fit: contain;">
                    </div>
                    <div class="modal-footer py-2 border-top">
                        <a href="{{ asset('storage/' . $item->proof_of_transfer) }}" target="_blank" class="btn btn-sm btn-outline-secondary me-auto fs-8">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Buka Gambar Asli
                        </a>
                        <button type="button" class="btn btn-sm btn-secondary fs-8" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL APPROVE BOOKING -->
    @if($item->status === 'pending')
    <div class="modal fade" id="modalApprove{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Setujui Booking?</h6>
                    <p class="text-muted fs-8 mb-3">
                        Pemesanan atas nama <strong>"{{ $item->name }}"</strong> akan diverifikasi dan tamu terdaftar sebagai penghuni aktif.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-sm btn-secondary px-3 fs-8" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.booking.approve', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-primary px-3 fs-8 fw-semibold">
                                Ya, Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL REJECT BOOKING (WITH MANDATORY AUDIT REASON) -->
    <div class="modal fade" id="modalReject{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom py-3">
                    <h6 class="modal-title fw-bold text-danger mb-0">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Tolak Permintaan Booking
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Batal"></button>
                </div>
                <form action="{{ route('admin.booking.reject', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <p class="text-muted fs-8 mb-3">
                            Tolak booking <strong>"{{ $item->name }}"</strong>. Berikan alasan penolakan untuk rekam jejak audit:
                        </p>

                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold text-dark">Alasan Penolakan <span class="text-danger">*</span></label>
                            <select name="reason_preset" class="form-select form-select-sm" id="presetSelect{{ $item->id }}" onchange="handleRejectPresetChange('{{ $item->id }}')">
                                <option value="Bukti transfer tidak valid / struk fiktif">Bukti transfer tidak valid / struk fiktif</option>
                                <option value="Nominal transfer tidak sesuai tarif sewa">Nominal transfer tidak sesuai tarif sewa</option>
                                <option value="Kamar telah terisi / dipesan secara offline">Kamar telah terisi / dipesan secara offline</option>
                                <option value="Calon penghuni tidak dapat dihubungi via WA/Telp">Calon penghuni tidak dapat dihubungi via WA/Telp</option>
                                <option value="other">Alasan Lainnya (Tulis Catatan Bebas)</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fs-8 fw-semibold text-dark">Catatan Tambahan (Opsional)</label>
                            <textarea name="rejection_reason" id="customReason{{ $item->id }}" rows="2" class="form-control form-control-sm" placeholder="Rincian catatan jika diperlukan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2.5">
                        <button type="button" class="btn btn-sm btn-secondary px-3 fs-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger px-3 fs-8 fw-semibold">
                            Ya, Tolak Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL DETAIL AUDIT (FOR PROCESSED BOOKINGS) -->
    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="bi bi-shield-check text-secondary me-2"></i>Detail Transaksi & Jejak Audit: {{ $item->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Kolom Kiri: Informasi Transaksi -->
                        <div class="col-md-6 border-end-md">
                            <h6 class="fw-bold fs-7 mb-3 text-secondary">
                                <i class="bi bi-person me-1"></i> Data Tamu & Pesanan
                            </h6>
                            <table class="table table-sm table-borderless fs-8 mb-0">
                                <tr>
                                    <td class="text-muted ps-0" style="width: 120px;">Nama Lengkap:</td>
                                    <td class="fw-semibold text-dark">{{ $item->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">WhatsApp:</td>
                                    <td>
                                        <a href="https://wa.me/{{ $item->whatsapp_number }}" target="_blank" class="text-success text-decoration-none fw-semibold">
                                            <i class="bi bi-whatsapp me-1"></i>{{ $item->telp }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Email:</td>
                                    <td class="text-dark">{{ $item->email }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Properti Kos:</td>
                                    <td class="fw-semibold text-dark">{{ $item->productKamarKosan->productKosan->title ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Unit Kamar:</td>
                                    <td><span class="badge badge-subtle-primary">Kamar {{ $item->productKamarKosan->room ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Periode Sewa:</td>
                                    <td class="text-dark">{{ \Carbon\Carbon::parse($item->start_date)->isoFormat('D MMM Y') }} s/d {{ \Carbon\Carbon::parse($item->end_date)->isoFormat('D MMM Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Total Pembayaran:</td>
                                    <td class="fw-bold text-primary">Rp {{ number_format((float)$item->total_price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Metode Bayar:</td>
                                    <td class="text-dark">{{ ucfirst($item->payment_method ?? 'Transfer') }}</td>
                                </tr>
                            </table>

                            @if($item->proof_of_transfer)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="fs-8 text-muted">Bukti Pembayaran:</span>
                                        <a href="{{ asset('storage/' . $item->proof_of_transfer) }}" target="_blank" class="btn btn-xs btn-outline-info fs-8">
                                            <i class="bi bi-image me-1"></i>Lihat Gambar Asli
                                        </a>
                                    </div>
                                    <img src="{{ asset('storage/' . $item->proof_of_transfer) }}" alt="Bukti" class="img-fluid rounded-2 border" style="max-height: 120px; object-fit: contain;">
                                </div>
                            @endif
                        </div>

                        <!-- Kolom Kanan: Timeline Audit Trail -->
                        <div class="col-md-6">
                            <h6 class="fw-bold fs-7 mb-3 text-secondary">
                                <i class="bi bi-clock-history me-1"></i> Rekam Jejak Pengelola
                            </h6>

                            <div class="p-3 bg-light rounded-2 mb-3">
                                <div class="fs-8 text-muted mb-1">Status Transaksi:</div>
                                @if($item->status === 'approved')
                                    <span class="badge bg-success text-white px-2.5 py-1 fw-semibold fs-8">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui (Approved)
                                    </span>
                                @elseif($item->status === 'reject')
                                    <span class="badge bg-danger text-white px-2.5 py-1 fw-semibold fs-8">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak (Rejected)
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-2.5 py-1 fw-semibold fs-8">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Review
                                    </span>
                                @endif
                            </div>

                            <ul class="list-unstyled position-relative border-start border-2 ps-3 ms-2 fs-8 text-muted mb-0">
                                <li class="mb-3 position-relative">
                                    <span class="position-absolute top-0 start-0 translate-middle-x bg-primary rounded-circle" style="width: 10px; height: 10px; margin-left: -17px; margin-top: 4px;"></span>
                                    <div class="fw-semibold text-dark">Permintaan Booking Dibuat</div>
                                    <div class="text-muted">{{ $item->created_at ? $item->created_at->translatedFormat('d F Y, H:i') : '-' }}</div>
                                </li>

                                @if($item->processed_at || $item->processed_by)
                                <li class="position-relative">
                                    <span class="position-absolute top-0 start-0 translate-middle-x {{ $item->status === 'approved' ? 'bg-success' : 'bg-danger' }} rounded-circle" style="width: 10px; height: 10px; margin-left: -17px; margin-top: 4px;"></span>
                                    <div class="fw-semibold {{ $item->status === 'approved' ? 'text-success' : 'text-danger' }}">
                                        Diproses: {{ $item->status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                                    </div>
                                    <div class="text-muted">
                                        {{ $item->processed_at ? $item->processed_at->translatedFormat('d F Y, H:i') : ($item->updated_at ? $item->updated_at->translatedFormat('d F Y, H:i') : '-') }}
                                    </div>
                                    <div class="text-dark mt-1">
                                        Admin Eksekutor: <strong>{{ $item->processedBy->name ?? 'Admin Sistem' }}</strong>
                                    </div>

                                    @if($item->status === 'reject')
                                        <div class="mt-2 p-2 bg-white rounded border border-danger-subtle text-danger fs-8">
                                            <strong>Alasan Penolakan:</strong><br>
                                            {{ $item->rejection_reason ?? 'Tidak ada keterangan spesifik.' }}
                                        </div>
                                    @endif
                                </li>
                                @else
                                <li class="position-relative">
                                    <span class="position-absolute top-0 start-0 translate-middle-x bg-warning rounded-circle" style="width: 10px; height: 10px; margin-left: -17px; margin-top: 4px;"></span>
                                    <div class="fw-semibold text-warning">Menunggu Tindakan Admin</div>
                                    <div class="text-muted opacity-75">Belum ada tindakan verifikasi yang dilakukan.</div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5">
                    <button type="button" class="btn btn-sm btn-secondary fs-8" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endforeach

<script>
    function handleRejectPresetChange(id) {
        const select = document.getElementById('presetSelect' + id);
        const textarea = document.getElementById('customReason' + id);
        if (select && textarea) {
            if (select.value === 'other') {
                textarea.value = '';
                textarea.focus();
            } else {
                textarea.value = select.value;
            }
        }
    }
</script>

@endsection
