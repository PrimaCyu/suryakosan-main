@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <div class="page-title">
                    <i class="bi bi-calendar-check-fill text-primary"></i> Permintaan Booking
                    @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                        <span class="badge badge-subtle-danger rounded-pill px-3 py-1 fs-8">
                            <i class="bi bi-bell-fill me-1"></i> {{ $pendingBookingCount }} Menunggu Review
                        </span>
                    @else
                        <span class="badge badge-subtle-success rounded-pill px-3 py-1 fs-8">
                            <i class="bi bi-check-circle-fill me-1"></i> Semua Terproses
                        </span>
                    @endif
                </div>
                <p class="page-subtitle">Kelola verifikasi pembayaran pemesanan kamar kosan dari penyewa.</p>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @include('backend.dashboard.kosan.kamar.partials.alerts')

        <div class="card mb-4">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h5 class="card-title mb-0 fs-6 fw-bold">
                        <i class="bi bi-clock-history text-warning me-2"></i>Antrean Booking Masuk
                    </h5>
                    <p class="text-muted fs-8 mb-0">Total {{ $bookings->total() }} transaksi booking tercatat.</p>
                </div>
                <div class="search-box-responsive">
                    <form action="{{ route('admin..booking.index') }}" method="GET" class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama, email, telp..." value="{{ request('search') }}" autocomplete="off">
                        <button type="submit" class="btn btn-outline-secondary">Cari</button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- TABEL PERMINTAAN BOOKING PENDING -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-min-lg" id="table-booking-list">
                        <thead>
                            <tr>
                                <th style="width: 45px" class="text-center">No</th>
                                <th>Nama Pemesan</th>
                                <th>Kontak</th>
                                <th>Kosan & Kamar</th>
                                <th>Periode Sewa</th>
                                <th>Total Biaya</th>
                                <th class="text-center">Bukti Transfer</th>
                                <th style="width: 170px" class="text-center">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $index => $item)
                                <tr>
                                    <td class="text-center fw-semibold text-muted">{{ $bookings->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="user-avatar-badge" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($item->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="table-cell-title">{{ $item->name }}</div>
                                                <small class="table-cell-sub">Metode: {{ $item->payment_method ?? 'Transfer' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="https://wa.me/{{ $item->whatsapp_number }}" target="_blank" class="text-decoration-none text-success fw-semibold fs-8" title="Hubungi via WhatsApp">
                                                <i class="bi bi-whatsapp me-1"></i>{{ $item->telp }}
                                            </a>
                                        </div>
                                        <small class="table-cell-sub"><i class="bi bi-envelope me-1"></i>{{ $item->email }}</small>
                                    </td>
                                    <td>
                                        <div class="table-cell-title">{{ $item->productKamarKosan->productKosan->title ?? '-' }}</div>
                                        <span class="badge badge-subtle-primary fs-8">Kamar: {{ $item->productKamarKosan->room ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="table-cell-title">{{ \Carbon\Carbon::parse($item->start_date)->isoFormat('D MMM Y') }}</span>
                                        <small class="d-block table-cell-sub">s/d {{ \Carbon\Carbon::parse($item->end_date)->isoFormat('D MMM Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="table-cell-title text-primary">Rp {{ number_format((float)$item->total_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->proof_of_transfer)
                                            <button type="button" class="btn btn-sm btn-outline-info py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalProof{{ $item->id }}">
                                                <i class="bi bi-image me-1"></i> Bukti
                                            </button>
                                        @else
                                            <span class="badge badge-subtle-secondary fs-8">
                                                <i class="bi bi-x-circle me-1"></i> Tanpa Bukti
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <!-- Approve Button -->
                                            <button type="button" class="btn btn-sm btn-success py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalApprove{{ $item->id }}" title="Setujui Booking">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>

                                            <!-- Reject Button -->
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalReject{{ $item->id }}" title="Tolak Booking">
                                                <i class="bi bi-x-circle"></i> Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                                        Tidak ada permintaan booking yang berstatus pending.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
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

<!-- MODALS PER ITEM (PROOF, APPROVE, REJECT) -->
@foreach ($bookings as $item)

    <!-- MODAL PROOF OF TRANSFER -->
    @if($item->proof_of_transfer)
        <div class="modal fade" id="modalProof{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header modal-header-modern text-white">
                        <h5 class="modal-title fs-6 fw-bold">
                            <i class="bi bi-image me-2 text-info"></i> Bukti Transfer: {{ $item->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-3 p-md-4">
                        <img src="{{ asset('storage/' . $item->proof_of_transfer) }}" alt="Bukti Transfer" class="img-fluid rounded-3 border shadow-sm" style="max-height: 400px; object-fit: contain;">
                        <p class="text-muted fs-8 mt-2 mb-0">File: {{ $item->proof_of_transfer }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL APPROVE BOOKING -->
    <div class="modal fade" id="modalApprove{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div class="p-3 rounded-circle bg-success-subtle text-success d-inline-flex mb-3">
                        <i class="bi bi-check-circle-fill fs-2"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Setujui Booking?</h6>
                    <p class="text-muted fs-8 mb-0">
                        Booking atas nama <strong>"{{ $item->name }}"</strong> akan diubah menjadi <strong>Approved</strong> dan tamu resmi terdaftar.
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin..booking.approve', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success px-3">
                            <i class="bi bi-check-lg me-1"></i> Ya, Setujui
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL REJECT BOOKING -->
    <div class="modal fade" id="modalReject{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-flex mb-3">
                        <i class="bi bi-exclamation-triangle-fill fs-2"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Tolak Booking?</h6>
                    <p class="text-muted fs-8 mb-0">
                        Booking atas nama <strong>"{{ $item->name }}"</strong> akan ditolak dan dihapus dari antrean pending.
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin..booking.reject', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-danger px-3">
                            <i class="bi bi-trash me-1"></i> Ya, Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endforeach

@endsection
