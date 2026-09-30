@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <a href="{{ route('admin.product.kosan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Kosan
                    </a>
                </div>
                <h1 class="page-title">
                    <i class="bi bi-door-closed text-secondary"></i> Kelola Kamar: {{ $kosan->title }}
                </h1>
                <p class="page-subtitle">Atur unit tipe kamar, galeri foto, kategori tarif sewa, fasilitas kamar, dan riwayat penghuni.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKamar">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Kamar Baru
                </button>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @include('backend.dashboard.kosan.kamar.partials.alerts')

        <!-- FILTER STATUS KAMAR TABS -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div class="status-filter-nav">
                <a href="{{ route('admin.product.kosan.kamar.index', array_merge(['product_kosan' => $product_kosan], request()->except(['status', 'page']))) }}"
                   class="status-filter-btn {{ ($statusFilter ?? 'semua') === 'semua' ? 'active' : '' }}">
                    Semua Unit <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $kpiStats['total_kamar'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.product.kosan.kamar.index', array_merge(['product_kosan' => $product_kosan, 'status' => 'kosong'], request()->except(['status', 'page']))) }}"
                   class="status-filter-btn {{ ($statusFilter ?? '') === 'kosong' ? 'active' : '' }}">
                    <i class="bi bi-check-circle me-1 text-success"></i> Kosong <span class="badge bg-success-subtle text-success rounded-pill ms-1">{{ $kpiStats['kosong_kamar'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.product.kosan.kamar.index', array_merge(['product_kosan' => $product_kosan, 'status' => 'terisi'], request()->except(['status', 'page']))) }}"
                   class="status-filter-btn {{ ($statusFilter ?? '') === 'terisi' ? 'active' : '' }}">
                    <i class="bi bi-person-fill me-1 text-primary"></i> Terisi <span class="badge bg-primary-subtle text-primary rounded-pill ms-1">{{ $kpiStats['terisi_kamar'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.product.kosan.kamar.index', array_merge(['product_kosan' => $product_kosan, 'status' => 'pending'], request()->except(['status', 'page']))) }}"
                   class="status-filter-btn {{ ($statusFilter ?? '') === 'pending' ? 'active' : '' }}">
                    <i class="bi bi-hourglass-split me-1 text-warning"></i> Pending <span class="badge bg-warning-subtle text-warning rounded-pill ms-1">{{ $kpiStats['pending_kamar'] ?? 0 }}</span>
                </a>
            </div>
            @if(request('status') || request('search'))
                <a href="{{ route('admin.product.kosan.kamar.index', $product_kosan) }}" class="btn btn-xs btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Reset Filter
                </a>
            @endif
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h5 class="card-title mb-0 fs-6 fw-bold">
                        <i class="bi bi-grid text-secondary me-2"></i>Daftar Unit & Tipe Kamar
                    </h5>
                    <p class="text-muted fs-8 mb-0">Total {{ $kamar_kosan->total() }} unit kamar terdaftar.</p>
                </div>

                <div class="search-box-responsive">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="admin-search-kamar" class="form-control border-start-0 ps-0" placeholder="Cari tipe/nama kamar..." value="{{ request('search') }}" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 40px" class="text-center">No</th>
                                <th class="text-nowrap" style="min-width: 170px">Unit / Tipe Kamar</th>
                                <th class="text-nowrap" style="min-width: 130px">Tarif Sewa</th>
                                <th class="text-center text-nowrap" style="width: 95px">Status</th>
                                <th class="text-nowrap" style="min-width: 140px">Penghuni & Tempo</th>
                                <th class="text-nowrap" style="min-width: 130px">Views & Fasilitas</th>
                                <th style="width: 140px" class="text-center text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kamar-tbody">
                            @forelse ($kamar_kosan as $index => $item)
                                <tr class="align-middle">
                                    <td class="text-center fw-semibold text-muted">{{ $kamar_kosan->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($item->primary_image)
                                                <img src="{{ asset('storage/' . $item->primary_image->image) }}" alt="{{ $item->room }}" class="table-room-thumb" onerror="this.onerror=null;this.classList.add('d-none');">
                                            @else
                                                <div class="table-room-thumb-placeholder" title="Belum ada foto">
                                                    <i class="bi bi-door-closed"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold text-dark text-nowrap fs-7">{{ $item->room }}</div>
                                                @if($item->cumulative_discount)
                                                    <span class="badge badge-subtle-success fs-8 mt-1">
                                                        <i class="bi bi-percent me-1"></i>Diskon {{ $item->cumulative_discount }}%
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap">
                                        @if($item->monthly_price)
                                            @php
                                                $rawMonthly = (float) $item->monthly_price->price;
                                                $disc = (float) ($item->cumulative_discount ?: $item->monthly_price->discount);
                                                $hasDiscount = $disc > 0;
                                                $finalMonthly = $hasDiscount ? ($disc <= 100 ? $rawMonthly * (1 - $disc/100) : max(0, $rawMonthly - $disc)) : $rawMonthly;
                                            @endphp
                                            @if($hasDiscount)
                                                <div class="fw-bold text-success fs-7">
                                                    Rp {{ number_format($finalMonthly, 0, ',', '.') }}<span class="fs-8 fw-normal text-muted">/bln</span>
                                                </div>
                                                <div class="fs-8 text-muted text-decoration-line-through">
                                                    Rp {{ number_format($rawMonthly, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="fw-bold text-dark fs-7">
                                                    Rp {{ number_format($rawMonthly, 0, ',', '.') }}<span class="fs-8 fw-normal text-muted">/bln</span>
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge badge-subtle-secondary fs-8">Belum diatur</span>
                                        @endif

                                        @if($item->yearly_price)
                                            <div class="fs-8 text-muted mt-0.5">
                                                Rp {{ number_format($item->yearly_price->price, 0, ',', '.') }}/thn
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @if($item->room_status === 'terisi')
                                            <span class="badge badge-subtle-primary">
                                                <i class="bi bi-person-fill-check me-1"></i>Terisi
                                            </span>
                                        @elseif($item->room_status === 'pending')
                                            <span class="badge badge-subtle-warning">
                                                <i class="bi bi-clock-history me-1"></i>Pending
                                            </span>
                                        @else
                                            <span class="badge badge-subtle-success">
                                                <i class="bi bi-check-circle-fill me-1"></i>Kosong
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        @if($item->active_tenant)
                                            @php
                                                $activeTamu = $item->active_tenant;
                                                try {
                                                    $endDate = \Carbon\Carbon::parse($activeTamu->end_date);
                                                    $daysLeft = ceil(now()->diffInDays($endDate, false));
                                                } catch (\Exception $e) {
                                                    $endDate = null;
                                                    $daysLeft = null;
                                                }
                                            @endphp
                                            <div class="tenant-compact text-nowrap">
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <span class="fw-semibold fs-8 text-dark" title="{{ $activeTamu->name }}">
                                                        {{ Str::limit($activeTamu->name, 14) }}
                                                    </span>
                                                    @if($activeTamu->hp)
                                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $activeTamu->hp) }}" target="_blank" rel="noopener noreferrer" class="text-success fs-8" title="Chat WA {{ $activeTamu->name }}">
                                                            <i class="bi bi-whatsapp"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                @if($endDate)
                                                    <div class="fs-8 text-muted d-flex align-items-center gap-1 mt-0">
                                                        <span>{{ $endDate->format('d/m/y') }}</span>
                                                        @if($daysLeft >= 0 && $daysLeft <= 7)
                                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-0 px-1 fs-8 fw-semibold" title="Sisa {{ $daysLeft }} hari">H-{{ $daysLeft }}</span>
                                                        @elseif($daysLeft < 0)
                                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-0 px-1 fs-8 fw-semibold">Habis</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($item->pending_tenant)
                                            <div class="fs-8 text-warning text-nowrap d-flex align-items-center gap-1">
                                                <span title="{{ $item->pending_tenant->name }}"><i class="bi bi-hourglass-split me-1"></i>{{ Str::limit($item->pending_tenant->name, 12) }}</span>
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle py-0 px-1 fs-8">Wait</span>
                                            </div>
                                        @else
                                            <span class="text-muted fs-8">-</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="fs-8">
                                            <div class="text-muted d-flex align-items-center gap-1">
                                                <i class="bi bi-eye"></i> {{ number_format($item->views ?? 0) }} views
                                            </div>
                                            @if($item->fasilitas)
                                                @php
                                                    $fasKamarArr = array_filter(array_map('trim', explode(',', $item->fasilitas)));
                                                @endphp
                                                @if(!empty($fasKamarArr))
                                                    <div class="text-secondary text-truncate mt-0.5" style="max-width: 150px;" title="{{ implode(', ', $fasKamarArr) }}">
                                                        {{ implode(', ', array_slice($fasKamarArr, 0, 2)) }}{{ count($fasKamarArr) > 2 ? '...' : '' }}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-1.5">
                                            <!-- Edit Quick Button -->
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-2"
                                                    style="font-size: 0.8rem;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditKamar{{ $item->id }}"
                                                    title="Edit Data Kamar">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </button>

                                            <!-- Dropdown Menu Opsi -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border py-1 px-2 rounded-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Opsi">
                                                    <i class="bi bi-three-dots-vertical text-secondary"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 fs-8" style="border-radius: 10px; min-width: 170px;">
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1.5 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalPriceKamar{{ $item->id }}">
                                                            <i class="bi bi-cash-stack text-secondary"></i> Tarif Sewa
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1.5 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalImageKamar{{ $item->id }}">
                                                            <i class="bi bi-images text-secondary"></i> Galeri Foto
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1.5 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTamuKamar{{ $item->id }}">
                                                            <i class="bi bi-people text-secondary"></i> Riwayat Penghuni
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('kamar.detail', $item->id) }}" target="_blank" rel="noopener noreferrer" class="dropdown-item py-1.5 d-flex align-items-center gap-2">
                                                            <i class="bi bi-box-arrow-up-right text-secondary"></i> Lihat di Web
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1.5 d-flex align-items-center gap-2 text-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteKamar{{ $item->id }}">
                                                            <i class="bi bi-trash"></i> Hapus Kamar
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-door-closed display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada unit kamar yang sesuai dengan filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted fs-8">
                    Menampilkan {{ $kamar_kosan->firstItem() ?? 0 }} - {{ $kamar_kosan->lastItem() ?? 0 }} dari {{ $kamar_kosan->total() }} kamar
                </small>
                <div>
                    {{ $kamar_kosan->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL-MODAL PER ITEM (EDIT, DELETE, SUB-FITUR) -->
@foreach ($kamar_kosan as $item)
    @include('backend.dashboard.kosan.kamar.partials.modal-kamar-edit-delete')
    @include('backend.dashboard.kosan.kamar.partials.modal-image-kamar')
    @include('backend.dashboard.kosan.kamar.partials.modal-price-kamar')
    @include('backend.dashboard.kosan.kamar.partials.modal-tamu')
@endforeach

<!-- MODAL TAMBAH KAMAR -->
@include('backend.dashboard.kosan.kamar.partials.modal-kamar-tambah')

<script>
    (function () {
        let kamarRowIndex = 1;
        const isSuperAdminUser = {{ Auth::user()->isSuperAdmin() ? 'true' : 'false' }};
        const container = document.getElementById('kamarInputContainer');

        // --- PREVIEW FOTO-FOTO KAMAR (MULTI-IMAGE) DI MODAL TERPADU ---
        const singleRoomInput = document.getElementById('singleRoomImageInput');
        const singleRoomPlaceholder = document.getElementById('singleRoomPlaceholder');
        const singleRoomPreviewWrap = document.getElementById('singleRoomPreviewWrap');
        const singleRoomFileName = document.getElementById('singleRoomFileName');
        const singleRoomImagesGrid = document.getElementById('singleRoomImagesGrid');
        const btnCancelSingleImg = document.getElementById('btnCancelSingleImg');

        if (singleRoomInput) {
            singleRoomInput.addEventListener('change', function() {
                const files = Array.from(this.files || []);
                if (files.length > 0) {
                    if (singleRoomImagesGrid) singleRoomImagesGrid.innerHTML = '';
                    if (singleRoomFileName) singleRoomFileName.textContent = `${files.length} Foto Dipilih`;

                    files.forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const card = document.createElement('div');
                                card.className = 'multi-image-card';
                                card.innerHTML = `
                                    <span class="badge-idx">#${index + 1}</span>
                                    <img src="${e.target.result}" alt="${file.name}">
                                `;
                                if (singleRoomImagesGrid) singleRoomImagesGrid.appendChild(card);
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    if (singleRoomPlaceholder) singleRoomPlaceholder.classList.add('d-none');
                    if (singleRoomPreviewWrap) singleRoomPreviewWrap.classList.remove('d-none');
                }
            });
        }

        if (btnCancelSingleImg) {
            btnCancelSingleImg.addEventListener('click', function() {
                if (singleRoomInput) singleRoomInput.value = '';
                if (singleRoomImagesGrid) singleRoomImagesGrid.innerHTML = '';
                if (singleRoomFileName) singleRoomFileName.textContent = '0 Foto Dipilih';
                if (singleRoomPreviewWrap) singleRoomPreviewWrap.classList.add('d-none');
                if (singleRoomPlaceholder) singleRoomPlaceholder.classList.remove('d-none');
            });
        }

        function initSummernote(element) {
            if (typeof $.fn.summernote !== 'undefined') {
                $(element).summernote({
                    placeholder: 'Tuliskan deskripsi kamar...',
                    tabsize: 2,
                    height: 150,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                });
            }
        }

        $(document).on('shown.bs.modal', function(e) {
            $(e.target).find('.summernote-edit').each(function() {
                if (!$(this).next('.note-editor').length) {
                    initSummernote(this);
                }
            });
        });

        function updateAddPricePreview() {
            const rawVal = parseFloat(document.getElementById('add_price_bulan')?.value) || 0;
            const discVal = parseFloat(document.getElementById('add_cumulative_discount')?.value) || 0;
            const netDisplay = document.getElementById('addPriceNetDisplay');
            if (!netDisplay) return;

            if (rawVal <= 0) {
                netDisplay.innerHTML = 'Rp 0 <span class="fs-8 fw-normal text-muted">/ bulan</span>';
                return;
            }

            let finalVal = rawVal;
            if (discVal > 0) {
                if (discVal <= 100) {
                    finalVal = rawVal * (1 - (discVal / 100));
                } else {
                    finalVal = Math.max(0, rawVal - discVal);
                }
            }

            const fmtNet = new Intl.NumberFormat('id-ID').format(Math.round(finalVal));
            if (discVal > 0) {
                const hemat = Math.round(rawVal - finalVal);
                netDisplay.innerHTML = `Rp ${fmtNet} <span class="fs-8 fw-normal text-muted">/ bulan</span> <span class="badge bg-success-subtle text-success ms-1.5 fs-8">Hemat Rp ${new Intl.NumberFormat('id-ID').format(hemat)}</span>`;
            } else {
                netDisplay.innerHTML = `Rp ${fmtNet} <span class="fs-8 fw-normal text-muted">/ bulan</span>`;
            }
        }

        document.addEventListener('input', function(e) {
            if (e.target.id === 'add_price_bulan' || e.target.id === 'add_cumulative_discount') {
                updateAddPricePreview();
                return;
            }

            if (e.target.classList.contains('edit-price-bulan') || e.target.classList.contains('edit-cumulative-discount')) {
                const kamarId = e.target.getAttribute('data-kamar-id');
                if (!kamarId) return;
                const priceEl = document.getElementById(`edit_price_bulan_${kamarId}`);
                const discEl = document.getElementById(`edit_cumulative_discount_${kamarId}`);
                const displayEl = document.getElementById(`editPriceNetDisplay${kamarId}`);
                if (!priceEl || !displayEl) return;

                const rawVal = parseFloat(priceEl.value) || 0;
                const discVal = parseFloat(discEl ? discEl.value : 0) || 0;

                if (rawVal <= 0) {
                    displayEl.innerHTML = 'Rp 0 <span class="fs-8 fw-normal text-muted">/ bulan</span>';
                    return;
                }

                let finalVal = rawVal;
                if (discVal > 0) {
                    if (discVal <= 100) {
                        finalVal = rawVal * (1 - (discVal / 100));
                    } else {
                        finalVal = Math.max(0, rawVal - discVal);
                    }
                }

                const fmtNet = new Intl.NumberFormat('id-ID').format(Math.round(finalVal));
                if (discVal > 0) {
                    const hemat = Math.round(rawVal - finalVal);
                    displayEl.innerHTML = `Rp ${fmtNet} <span class="fs-8 fw-normal text-muted">/ bulan</span> <span class="badge bg-success-subtle text-success ms-1.5 fs-8">Hemat Rp ${new Intl.NumberFormat('id-ID').format(hemat)}</span>`;
                } else {
                    displayEl.innerHTML = `Rp ${fmtNet} <span class="fs-8 fw-normal text-muted">/ bulan</span>`;
                }
            }
        });

        document.addEventListener('click', function(e) {
            const btnAddKmrRow = e.target.closest('.btn-add-kamar-row');
            if (btnAddKmrRow) {
                const container = document.getElementById('kamarInputContainer');
                if (container) {
                    const rows = container.querySelectorAll('.kamar-row');
                    const index = rows.length;
                    const div = document.createElement('div');
                    div.className = 'kamar-row card border rounded p-3 mb-3';
                    div.id = `kamar-row-${index}`;
                    const discKamarField = isSuperAdminUser
                        ? `<input type="number" step="0.01" min="0" max="100" name="dataKamar[${index}][cumulative_discount]" class="form-control" placeholder="0">`
                        : `<input type="number" step="0.01" name="dataKamar[${index}][cumulative_discount]" class="form-control bg-light" value="0" readonly disabled title="Hanya diatur langsung oleh Super Admin"><small class="text-muted d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-lock-fill text-warning me-1"></i>Khusus Super Admin</small>`;

                    div.innerHTML = `
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <label class="form-label fs-8 fw-semibold mb-1">Nama / Tipe Kamar <span class="text-danger">*</span></label>
                                <input type="text" name="dataKamar[${index}][room]" class="form-control" placeholder="Contoh: Kamar 102" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold mb-1">Tarif Bulanan (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted fs-8">Rp</span>
                                    <input type="number" name="dataKamar[${index}][price_bulan]" class="form-control fw-semibold" placeholder="1500000" min="0" step="10000" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-semibold mb-1">Diskon (%)</label>
                                ${discKamarField}
                            </div>
                            <div class="col-md-1 text-end align-self-end">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-kamar-row w-100" title="Hapus Baris">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="col-12">
                                <label class="form-label fs-8 fw-semibold mb-1">Deskripsi Singkat</label>
                                <textarea name="dataKamar[${index}][description]" class="form-control" rows="2" placeholder="Deskripsi kamar..."></textarea>
                            </div>
                        </div>
                    `;
                    container.appendChild(div);
                    rebuildBulkKamarIndexes(container);
                }
                return;
            }

            const btnRemoveKmrRow = e.target.closest('.btn-remove-kamar-row');
            if (btnRemoveKmrRow) {
                const container = btnRemoveKmrRow.closest('.kamar-input-container') || document.getElementById('kamarInputContainer');
                btnRemoveKmrRow.closest('.kamar-row').remove();
                if (container) rebuildBulkKamarIndexes(container);
                return;
            }

            const btnAddPrcRow = e.target.closest('.btn-add-price-row');
            if (btnAddPrcRow) {
                const kamarId = btnAddPrcRow.getAttribute('data-kamar-id');
                const container = document.getElementById(`priceKamarContainer${kamarId}`);
                const rows = container.querySelectorAll('.price-kamar-row');
                const index = rows.length;
                const div = document.createElement('div');
                div.className = 'price-kamar-row card border rounded p-3 mb-3';
                const discPriceField = isSuperAdminUser
                    ? `<input type="number" step="0.01" name="priceKamar[${index}][discount]" class="form-control" placeholder="0">`
                    : `<input type="number" step="0.01" name="priceKamar[${index}][discount]" class="form-control bg-light" value="0" readonly disabled title="Hanya diatur langsung oleh Super Admin"><small class="text-muted d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-lock-fill text-warning me-1"></i>Khusus Super Admin</small>`;

                div.innerHTML = `
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <label class="form-label mb-1">Kategori Sewa <span class="text-danger">*</span></label>
                            <select name="priceKamar[${index}][kategori]" class="form-select" required>
                                <option value="bulan">Bulanan (Per Bulan)</option>
                                <option value="tahun">Tahunan (Per Tahun)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1">Nominal Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="priceKamar[${index}][price]" class="form-control" placeholder="Contoh: 1500000" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1">Diskon (%)</label>
                            ${discPriceField}
                        </div>
                        <div class="col-md-1 text-end align-self-end">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-price-row w-100" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(div);
                rebuildBulkPriceIndexes(container);
                return;
            }

            const btnRemovePrcRow = e.target.closest('.btn-remove-price-row');
            if (btnRemovePrcRow) {
                const container = btnRemovePrcRow.closest('.price-kamar-container');
                btnRemovePrcRow.closest('.price-kamar-row').remove();
                if (container) rebuildBulkPriceIndexes(container);
                return;
            }
        });

        function rebuildBulkKamarIndexes(container) {
            const rows = container.querySelectorAll('.kamar-row');
            rows.forEach((row, i) => {
                const room = row.querySelector('input[name*="[room]"]');
                const price = row.querySelector('input[name*="[price_bulan]"]');
                const disc = row.querySelector('input[name*="[cumulative_discount]"]');
                const desc = row.querySelector('textarea[name*="[description]"]');

                if (room) room.name = `dataKamar[${i}][room]`;
                if (price) price.name = `dataKamar[${i}][price_bulan]`;
                if (disc) disc.name = `dataKamar[${i}][cumulative_discount]`;
                if (desc) desc.name = `dataKamar[${i}][description]`;

                const btnRemove = row.querySelector('.btn-remove-kamar-row');
                if (btnRemove) {
                    btnRemove.style.display = rows.length > 1 ? '' : 'none';
                }
            });
        }

        function rebuildBulkPriceIndexes(container) {
            const rows = container.querySelectorAll('.price-kamar-row');
            rows.forEach((row, i) => {
                const kategori = row.querySelector('[name*="[kategori]"]');
                const price = row.querySelector('input[name*="[price]"]');
                const discount = row.querySelector('input[name*="[discount]"]');
                if (kategori) kategori.name = `priceKamar[${i}][kategori]`;
                if (price) price.name = `priceKamar[${i}][price]`;
                if (discount) discount.name = `priceKamar[${i}][discount]`;
                const btnRemove = row.querySelector('.btn-remove-price-row');
                if (btnRemove) {
                    btnRemove.style.display = rows.length > 1 ? '' : 'none';
                }
            });
        }

        const searchKamarInput = document.getElementById('admin-search-kamar');
        const kamarTbody = document.getElementById('kamar-tbody');
        let kamarTimeout = null;

        if (searchKamarInput && kamarTbody) {
            searchKamarInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(kamarTimeout);

                kamarTimeout = setTimeout(() => {
                    fetch(`{{ route('admin.product.kosan.kamar.search.ajax', $product_kosan) }}?search=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        kamarTbody.innerHTML = '';
                        const items = data.data || data;

                        if (!items || items.length === 0) {
                            kamarTbody.innerHTML = `
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-door-closed display-6 d-block mb-2 opacity-50"></i>
                                        Data kamar tidak ditemukan.
                                    </td>
                                </tr>
                            `;
                        } else {
                            items.forEach((item, index) => {
                                const tr = document.createElement('tr');
                                tr.className = 'align-middle';

                                const discountBadge = item.cumulative_discount
                                    ? `<span class="badge badge-subtle-success fs-8 mt-1 d-inline-block"><i class="bi bi-percent me-1"></i>Diskon ${item.cumulative_discount}%</span>`
                                    : '';

                                // Thumbnail
                                let thumbHtml = `<div class="table-room-thumb-placeholder" title="Belum ada foto"><i class="bi bi-door-closed"></i></div>`;
                                if (item.primary_image && item.primary_image.image) {
                                    thumbHtml = `<img src="/storage/${item.primary_image.image}" alt="${item.room}" class="table-room-thumb" onerror="this.onerror=null;this.style.display='none';">`;
                                }

                                // Harga
                                let priceHtml = `<span class="badge badge-subtle-secondary fs-8">Belum diatur</span>`;
                                if (item.price_kamar && item.price_kamar.length > 0) {
                                    const mPrice = item.price_kamar.find(p => p.kategori === 'bulan');
                                    const yPrice = item.price_kamar.find(p => p.kategori === 'tahun');
                                    if (mPrice) {
                                        const rawP = Number(mPrice.price);
                                        const disc = Number(item.cumulative_discount || mPrice.discount || 0);
                                        if (disc > 0) {
                                            const netP = disc <= 100 ? rawP * (1 - disc / 100) : Math.max(0, rawP - disc);
                                            priceHtml = `
                                                <div class="fw-bold text-success fs-7">Rp ${Math.round(netP).toLocaleString('id-ID')}<span class="fs-8 fw-normal text-muted">/bln</span></div>
                                                <div class="fs-8 text-muted text-decoration-line-through">Rp ${rawP.toLocaleString('id-ID')}</div>
                                            `;
                                        } else {
                                            priceHtml = `<div class="fw-bold text-dark fs-7">Rp ${rawP.toLocaleString('id-ID')}<span class="fs-8 fw-normal text-muted">/bln</span></div>`;
                                        }
                                    }
                                    if (yPrice) {
                                        priceHtml += `<div class="fs-8 text-muted mt-0.5">Rp ${Number(yPrice.price).toLocaleString('id-ID')}/thn</div>`;
                                    }
                                }

                                // Status
                                let statusHtml = `<span class="badge badge-subtle-success"><i class="bi bi-check-circle-fill me-1"></i>Kosong</span>`;
                                if (item.room_status === 'terisi') {
                                    statusHtml = `<span class="badge badge-subtle-primary"><i class="bi bi-person-fill-check me-1"></i>Terisi</span>`;
                                } else if (item.room_status === 'pending') {
                                    statusHtml = `<span class="badge badge-subtle-warning"><i class="bi bi-clock-history me-1"></i>Pending</span>`;
                                }

                                // Penghuni
                                let tenantHtml = '<span class="text-muted fs-8">-</span>';
                                if (item.active_tenant) {
                                    let phoneLink = item.active_tenant.hp ? `<a href="https://wa.me/${item.active_tenant.hp.replace(/[^0-9]/g, '')}" target="_blank" class="text-success fs-8" title="Chat WhatsApp"><i class="bi bi-whatsapp"></i></a>` : '';
                                    let nameShort = item.active_tenant.name.length > 14 ? item.active_tenant.name.substring(0, 14) + '...' : item.active_tenant.name;
                                    let dateHtml = '';
                                    if (item.active_tenant.end_date) {
                                        dateHtml = `<div class="fs-8 text-muted mt-0 text-nowrap">${item.active_tenant.end_date.substring(0, 10)}</div>`;
                                    }
                                    tenantHtml = `
                                        <div class="tenant-compact text-nowrap">
                                            <div class="d-flex align-items-center gap-1.5">
                                                <span class="fw-semibold fs-8 text-dark" title="${item.active_tenant.name}">${nameShort}</span>
                                                ${phoneLink}
                                            </div>
                                            ${dateHtml}
                                        </div>
                                    `;
                                } else if (item.pending_tenant) {
                                    let pNameShort = item.pending_tenant.name.length > 12 ? item.pending_tenant.name.substring(0, 12) + '...' : item.pending_tenant.name;
                                    tenantHtml = `<div class="fs-8 text-warning text-nowrap d-flex align-items-center gap-1"><span title="${item.pending_tenant.name}"><i class="bi bi-hourglass-split me-1"></i>${pNameShort}</span><span class="badge bg-warning-subtle text-warning border border-warning-subtle py-0 px-1 fs-8">Wait</span></div>`;
                                }

                                // Fasilitas
                                let fasHtml = '';
                                if (item.fasilitas) {
                                    const fasArr = item.fasilitas.split(',').map(s => s.trim()).filter(Boolean);
                                    if (fasArr.length > 0) {
                                        const previewFas = fasArr.slice(0, 2).join(', ');
                                        const moreFas = fasArr.length > 2 ? '...' : '';
                                        fasHtml = `<div class="text-secondary text-truncate mt-0.5" style="max-width: 150px;" title="${item.fasilitas}">${previewFas}${moreFas}</div>`;
                                    }
                                }

                                const viewsCount = item.views ? Number(item.views).toLocaleString('id-ID') : 0;

                                tr.innerHTML = `
                                    <td class="text-center fw-semibold text-muted">${index + 1}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            ${thumbHtml}
                                            <div>
                                                <div class="fw-semibold text-dark text-nowrap fs-7">${item.room}</div>
                                                ${discountBadge}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap">${priceHtml}</td>
                                    <td class="text-center text-nowrap">${statusHtml}</td>
                                    <td>${tenantHtml}</td>
                                    <td class="text-nowrap">
                                        <div class="fs-8">
                                            <div class="text-muted d-flex align-items-center gap-1">
                                                <i class="bi bi-eye"></i> ${viewsCount} views
                                            </div>
                                            ${fasHtml}
                                        </div>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-1.5">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-2"
                                                    style="font-size: 0.8rem;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditKamar${item.id}"
                                                    title="Edit Data Kamar">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </button>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border py-1 px-2 rounded-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Opsi">
                                                    <i class="bi bi-three-dots-vertical text-secondary"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 fs-8" style="border-radius: 10px; min-width: 170px;">
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1.5 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalPriceKamar${item.id}">
                                                            <i class="bi bi-cash-stack text-secondary"></i> Tarif Sewa
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1.5 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalImageKamar${item.id}">
                                                            <i class="bi bi-images text-secondary"></i> Galeri Foto
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1.5 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTamuKamar${item.id}">
                                                            <i class="bi bi-people text-secondary"></i> Riwayat Penghuni
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <a href="/kamar/detail/${item.id}" target="_blank" rel="noopener noreferrer" class="dropdown-item py-1.5 d-flex align-items-center gap-2">
                                                            <i class="bi bi-box-arrow-up-right text-secondary"></i> Lihat di Web
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1.5 d-flex align-items-center gap-2 text-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteKamar${item.id}">
                                                            <i class="bi bi-trash"></i> Hapus Kamar
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                `;
                                kamarTbody.appendChild(tr);
                            });
                        }
                    })
                    .catch(err => console.error('Error Live Search Kamar:', err));
                }, 250);
            });
        }

        // --- MODERN GALLERY DROPZONE & LIVE PREVIEW ---
        function initGalleryDropzone(dropzone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                }, false);
            });

            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                const input = dropzone.querySelector('.gallery-file-input');
                if (input && files && files.length > 0) {
                    input.files = files;
                    handleFilesPreview(input);
                }
            });
        }

        document.querySelectorAll('.gallery-dropzone').forEach(initGalleryDropzone);

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('gallery-file-input')) {
                handleFilesPreview(e.target);
            }
        });

        function handleFilesPreview(input) {
            const targetId = input.getAttribute('data-target');
            const btnId = input.getAttribute('data-btn');
            const container = document.getElementById(targetId);
            const submitBtn = document.getElementById(btnId);
            const countTextId = targetId.replace('previewContainer', 'fileCountText');
            const countText = document.getElementById(countTextId);

            if (!container) return;
            container.innerHTML = '';

            const files = input.files;
            if (!files || files.length === 0) {
                container.classList.add('d-none');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Foto Kamar`;
                }
                if (countText) countText.textContent = 'Belum ada file dipilih.';
                return;
            }

            container.classList.remove('d-none');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload ${files.length} Foto Kamar`;
            }
            if (countText) {
                let totalBytes = Array.from(files).reduce((sum, f) => sum + f.size, 0);
                let mb = (totalBytes / (1024 * 1024)).toFixed(1);
                countText.innerHTML = `<span class="text-success fw-bold"><i class="bi bi-check2-circle me-1"></i>${files.length} foto dipilih</span> (${mb} MB)`;
            }

            Array.from(files).forEach((file, idx) => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}">
                        <div class="preview-badge-name" title="${file.name}">${file.name}</div>
                        <button type="button" class="btn-remove-preview" title="Hapus foto ini" data-idx="${idx}">
                            <i class="bi bi-x"></i>
                        </button>
                    `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Cancel/remove preview
        document.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.btn-remove-preview');
            if (removeBtn) {
                const previewItem = removeBtn.closest('.preview-item');
                const previewContainer = removeBtn.closest('.preview-grid');
                if (previewItem && previewContainer) {
                    previewItem.remove();
                    if (previewContainer.children.length === 0) {
                        previewContainer.classList.add('d-none');
                        const form = previewContainer.closest('form');
                        if (form) {
                            const input = form.querySelector('.gallery-file-input');
                            if (input) input.value = '';
                            const submitBtn = form.querySelector('button[type="submit"]');
                            if (submitBtn) {
                                submitBtn.disabled = true;
                                submitBtn.innerHTML = `<i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Foto Kamar`;
                            }
                            const countText = form.querySelector('small[id^="fileCountText"]');
                            if (countText) countText.textContent = 'Belum ada file dipilih.';
                        }
                    }
                }
            }
        });

        // Auto-open tamu modal if triggered directly from dashboard due date alerts
        @if(request('open_tamu_kamar'))
            document.addEventListener('DOMContentLoaded', function() {
                const targetModalId = 'modalTamuKamar{{ request('open_tamu_kamar') }}';
                const modalEl = document.getElementById(targetModalId);
                if (modalEl) {
                    const bsModal = new bootstrap.Modal(modalEl);
                    bsModal.show();
                }
            });
        @endif
    })();
</script>

@endsection
