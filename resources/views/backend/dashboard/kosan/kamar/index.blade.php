@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <a href="{{ route('admin.product.kosan.index') }}" class="btn btn-xs btn-outline-secondary rounded-pill px-2">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Kosan
                    </a>
                </div>
                <h1 class="page-title">
                    <i class="bi bi-door-closed-fill text-primary"></i> Kelola Kamar: {{ $kosan->title }}
                </h1>
                <p class="page-subtitle">Atur unit tipe kamar, galeri foto, kategori tarif sewa, fasilitas kamar, dan riwayat penghuni.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKamar">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Kamar Baru
                </button>
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
                        <i class="bi bi-grid text-primary me-2"></i>Daftar Unit & Tipe Kamar
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
                    <table class="table table-hover align-middle mb-0 table-min-lg">
                        <thead>
                            <tr>
                                <th style="width: 45px" class="text-center">No</th>
                                <th>Tipe / Nama Kamar</th>
                                <th class="text-center" style="width: 90px">Views</th>
                                <th>Fasilitas Kamar</th>
                                <th style="width: 280px" class="text-center">Aksi Manajemen</th>
                            </tr>
                        </thead>
                        <tbody id="kamar-tbody">
                            @forelse ($kamar_kosan as $index => $item)
                                <tr class="align-middle">
                                    <td class="text-center fw-semibold text-muted">{{ $kamar_kosan->firstItem() + $index }}</td>
                                    <td>
                                        <div class="table-cell-title">{{ $item->room }}</div>
                                        @if($item->cumulative_discount)
                                            <span class="badge badge-subtle-success fs-8 mt-1">
                                                <i class="bi bi-percent me-1"></i>Diskon {{ $item->cumulative_discount }}%
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-subtle-primary">
                                            <i class="bi bi-eye-fill me-1"></i>{{ number_format($item->views ?? 0) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->fasilitas)
                                            @php
                                                $fasKamarArr = array_filter(array_map('trim', explode(',', $item->fasilitas)));
                                            @endphp
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach(array_slice($fasKamarArr, 0, 2) as $fk)
                                                    <span class="badge badge-subtle-info fs-8">{{ $fk }}</span>
                                                @endforeach
                                                @if(count($fasKamarArr) > 2)
                                                    <span class="badge badge-subtle-secondary fs-8 text-muted">+{{ count($fasKamarArr) - 2 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted fs-8">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <!-- Sub-fitur Modals Trigger -->
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-warning py-1 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalImageKamar{{ $item->id }}"
                                                    title="Galeri Foto">
                                                <i class="bi bi-images"></i> Galeri
                                            </button>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success py-1 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalPriceKamar{{ $item->id }}"
                                                    title="Tarif Harga">
                                                <i class="bi bi-cash-stack"></i> Harga
                                            </button>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary py-1 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalTamuKamar{{ $item->id }}"
                                                    title="Riwayat Penghuni">
                                                <i class="bi bi-people"></i> Penghuni
                                            </button>

                                            <!-- Edit Button -->
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary py-1 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditKamar{{ $item->id }}"
                                                    title="Edit Kamar">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger py-1 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDeleteKamar{{ $item->id }}"
                                                    title="Hapus Kamar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-door-closed display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada unit kamar yang ditambahkan untuk kosan ini.
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
        const container = document.getElementById('kamarInputContainer');

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
                    div.innerHTML = `
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label mb-1">Nama / Tipe Kamar <span class="text-danger">*</span></label>
                                <input type="text" name="dataKamar[${index}][room]" class="form-control" placeholder="Contoh: Kamar Deluxe B" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1">Diskon Akumulatif (%)</label>
                                <input type="number" step="0.01" name="dataKamar[${index}][cumulative_discount]" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1"><i class="bi bi-eye me-1"></i> Views</label>
                                <input type="number" min="0" name="dataKamar[${index}][views]" class="form-control" placeholder="0" value="0">
                            </div>
                            <div class="col-md-1 text-end align-self-end">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-kamar-row w-100" title="Hapus Baris">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-2">Fasilitas Kamar</label>
                                <div class="p-3 rounded-3 bg-body-tertiary border">
                                    <div class="row g-2">
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="AC" id="fas_kmr_${index}_0"><label class="form-check-label fs-8" for="fas_kmr_${index}_0">AC</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Kamar Mandi Dalam" id="fas_kmr_${index}_1"><label class="form-check-label fs-8" for="fas_kmr_${index}_1">Kamar Mandi Dalam</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Water Heater / Air Hangat" id="fas_kmr_${index}_2"><label class="form-check-label fs-8" for="fas_kmr_${index}_2">Water Heater</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Kasur Springbed" id="fas_kmr_${index}_3"><label class="form-check-label fs-8" for="fas_kmr_${index}_3">Kasur Springbed</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Lemari Pakaian" id="fas_kmr_${index}_4"><label class="form-check-label fs-8" for="fas_kmr_${index}_4">Lemari Pakaian</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Meja & Kursi Belajar" id="fas_kmr_${index}_5"><label class="form-check-label fs-8" for="fas_kmr_${index}_5">Meja & Kursi</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="TV / Smart TV" id="fas_kmr_${index}_6"><label class="form-check-label fs-8" for="fas_kmr_${index}_6">TV / Smart TV</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Wastafel" id="fas_kmr_${index}_7"><label class="form-check-label fs-8" for="fas_kmr_${index}_7">Wastafel</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Balkon Kamar" id="fas_kmr_${index}_8"><label class="form-check-label fs-8" for="fas_kmr_${index}_8">Balkon</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Jendela / Ventilasi Bagus" id="fas_kmr_${index}_9"><label class="form-check-label fs-8" for="fas_kmr_${index}_9">Jendela/Ventilasi</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Kipas Angin" id="fas_kmr_${index}_10"><label class="form-check-label fs-8" for="fas_kmr_${index}_10">Kipas Angin</label></div></div>
                                        <div class="col-md-4 col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="dataKamar[${index}][fasilitas][]" value="Kulkas Mini" id="fas_kmr_${index}_11"><label class="form-check-label fs-8" for="fas_kmr_${index}_11">Kulkas Mini</label></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-1">Deskripsi Kamar</label>
                                <textarea name="dataKamar[${index}][description]" class="form-control summernote-edit" rows="2" placeholder="Deskripsi kamar..."></textarea>
                            </div>
                        </div>
                    `;
                    container.appendChild(div);
                    rebuildBulkKamarIndexes(container);

                    const newTextarea = div.querySelector('.summernote-edit');
                    if (newTextarea && typeof $.fn.summernote !== 'undefined') {
                        $(newTextarea).summernote({
                            placeholder: 'Deskripsi kamar...',
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
                div.innerHTML = `
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <label class="form-label mb-1">Kategori Sewa <span class="text-danger">*</span></label>
                            <input type="text" name="priceKamar[${index}][kategori]" class="form-control" placeholder="Contoh: Bulanan" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1">Nominal Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="priceKamar[${index}][price]" class="form-control" placeholder="Contoh: 1500000" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1">Diskon (%)</label>
                            <input type="number" step="0.01" name="priceKamar[${index}][discount]" class="form-control" placeholder="0">
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
                rebuildBulkPriceIndexes(container);
                return;
            }
        });

        function rebuildBulkKamarIndexes(container) {
            const rows = container.querySelectorAll('.kamar-row');
            rows.forEach((row, i) => {
                const room = row.querySelector('input[name*="[room]"]');
                const disc = row.querySelector('input[name*="[cumulative_discount]"]');
                const views = row.querySelector('input[name*="[views]"]');
                const desc = row.querySelector('textarea[name*="[description]"]');
                const fasInputs = row.querySelectorAll('input[name*="[fasilitas]"]');

                if (room) room.name = `dataKamar[${i}][room]`;
                if (disc) disc.name = `dataKamar[${i}][cumulative_discount]`;
                if (views) views.name = `dataKamar[${i}][views]`;
                if (desc) desc.name = `dataKamar[${i}][description]`;

                fasInputs.forEach((input, fKey) => {
                    input.name = `dataKamar[${i}][fasilitas][]`;
                    input.id = `fas_kmr_${i}_${fKey}`;
                    const label = input.nextElementSibling;
                    if (label) label.setAttribute('for', `fas_kmr_${i}_${fKey}`);
                });

                const btnRemove = row.querySelector('.btn-remove-kamar-row');
                if (btnRemove) {
                    btnRemove.style.display = rows.length > 1 ? '' : 'none';
                }
            });
        }

        function rebuildBulkPriceIndexes(container) {
            const rows = container.querySelectorAll('.price-kamar-row');
            rows.forEach((row, i) => {
                const kategori = row.querySelector('input[name*="[kategori]"]');
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
                                    <td colspan="5" class="text-center text-muted py-5">
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

                                let fasHtml = '<span class="text-muted fs-8">-</span>';
                                if (item.fasilitas) {
                                    const fasArr = item.fasilitas.split(',').map(s => s.trim()).filter(Boolean);
                                    if (fasArr.length > 0) {
                                        const slice = fasArr.slice(0, 2).map(f => `<span class="badge badge-subtle-info fs-8 me-1">${f}</span>`).join('');
                                        const more = fasArr.length > 2 ? `<span class="badge badge-subtle-secondary fs-8 text-muted">+${fasArr.length - 2}</span>` : '';
                                        fasHtml = `<div class="d-flex flex-wrap gap-1">${slice}${more}</div>`;
                                    }
                                }

                                const viewsCount = item.views ? Number(item.views).toLocaleString('id-ID') : 0;

                                tr.innerHTML = `
                                    <td class="text-center fw-semibold text-muted">${index + 1}</td>
                                    <td>
                                        <div class="table-cell-title">${item.room}</div>
                                        ${discountBadge}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-subtle-primary">
                                            <i class="bi bi-eye-fill me-1"></i>${viewsCount}
                                        </span>
                                    </td>
                                    <td>${fasHtml}</td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalImageKamar${item.id}" title="Galeri Foto">
                                                <i class="bi bi-images"></i> Galeri
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalPriceKamar${item.id}" title="Tarif Harga">
                                                <i class="bi bi-cash-stack"></i> Harga
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalTamuKamar${item.id}" title="Penghuni">
                                                <i class="bi bi-people"></i> Penghuni
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditKamar${item.id}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeleteKamar${item.id}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
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
    })();
</script>

@endsection
