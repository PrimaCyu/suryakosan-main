@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="mb-1 fs-3 fw-bold text-dark">
                    <i class="bi bi-house-door-fill text-primary me-2"></i> Kelola Properti Kos-kosan
                </h1>
                <p class="text-muted fs-7 mb-0">Manajemen daftar properti kos, spesifikasi wilayah, fasilitas, dan unit kamar.</p>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Kosan Baru
                </button>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @include('backend.dashboard.kosan.kamar.partials.alerts')

        <div class="tab-content" id="kosanTabContent">
            <!-- Partial: Tab Data Kos-kosan -->
            @include('backend.dashboard.kosan.partials-kosan._data_kosan')
        </div> <!-- end tab-content -->

        <!-- Partial: Modal Tambah Kosan -->
        @include('backend.dashboard.kosan.partials-kosan._modals')
    </div>
</div>

<script>
    // Auto Generate Slug
    function generateSlug() {
        const title = document.getElementById('title').value;
        const slugInput = document.getElementById('slug');
        if(slugInput) {
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            slugInput.value = slug;
        }
    }

    // Preview Image Denah
    function previewImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.classList.add('d-none');
        }
    }

    // Fetch Data Wilayah Bali dari API Wilayah Indonesia (Kode Provinsi Bali = 51)
    document.addEventListener("DOMContentLoaded", function() {
        const selectTambah = document.getElementById('select_wilayah_tambah');
        const selectEdits = document.querySelectorAll('.select-wilayah-edit');

        fetch('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/51.json')
            .then(response => response.json())
            .then(regencies => {
                let optionsHtml = '<option value="" selected disabled>-- Pilih Kab / Kota (Bali) --</option>';
                regencies.forEach(reg => {
                    let formattedName = reg.name.replace(/KABUPATEN |KOTA /gi, '').trim();
                    formattedName = formattedName.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
                    optionsHtml += `<option value="${formattedName}">${formattedName}</option>`;
                });

                if (selectTambah) {
                    selectTambah.innerHTML = optionsHtml;
                }

                selectEdits.forEach(selectEdit => {
                    const currentValue = selectEdit.getAttribute('data-current-value');
                    selectEdit.innerHTML = optionsHtml;
                    if (currentValue) {
                        selectEdit.value = currentValue;
                    }
                });
            })
            .catch(err => {
                console.error('Gagal mengambil API Wilayah Bali:', err);
                const fallbackHtml = `
                    <option value="" disabled>Gagal load API. Pilih Manual:</option>
                    <option value="Badung">Badung</option>
                    <option value="Denpasar">Denpasar</option>
                    <option value="Gianyar">Gianyar</option>
                    <option value="Tabanan">Tabanan</option>
                    <option value="Buleleng">Buleleng</option>
                    <option value="Karangasem">Karangasem</option>
                    <option value="Klungkung">Klungkung</option>
                    <option value="Bangli">Bangli</option>
                    <option value="Jembrana">Jembrana</option>
                `;
                if (selectTambah) selectTambah.innerHTML = fallbackHtml;
                selectEdits.forEach(s => s.innerHTML = fallbackHtml);
            });
    });

    // Live Search AJAX untuk Tabel Kosan
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('admin-search-kosan');
        const tbody = document.getElementById('kosan-tbody');
        let searchTimeout = null;

        if (searchInput && tbody) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('admin.product.kosan.search.ajax') }}?search=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        tbody.innerHTML = '';
                        const items = data.data || data;

                        if (!items || items.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-house-x display-6 d-block mb-2 text-secondary opacity-50"></i>
                                        Data kosan tidak ditemukan.
                                    </td>
                                </tr>
                            `;
                        } else {
                            items.forEach((item, index) => {
                                const kamarRoute = `{{ url('admin/product-kosan') }}/${item.id}/kamar/index`;

                                const wilayahBadge = item.wilayah
                                    ? `<span class="badge badge-subtle-info"><i class="bi bi-geo-alt-fill me-1"></i>${item.wilayah}</span>`
                                    : `<span class="text-muted fs-8">-</span>`;

                                const fasilitasText = item.fasilitas
                                    ? `<span class="badge badge-subtle-secondary fs-8">${item.fasilitas.length > 35 ? item.fasilitas.substring(0, 35) + '...' : item.fasilitas}</span>`
                                    : `<span class="text-muted fs-8">-</span>`;

                                const viewText = item.view
                                    ? `<span class="badge badge-subtle-primary"><i class="bi bi-eye-fill me-1"></i>${item.view}</span>`
                                    : `<span class="text-muted fs-8">0</span>`;

                                const kamarTersediaText = item.tersedia !== undefined && item.tersedia !== null
                                    ? `<span class="badge badge-subtle-success"><i class="bi bi-door-closed me-1"></i>${item.tersedia} Unit</span>`
                                    : `<span class="text-muted fs-8">-</span>`;

                                const tr = document.createElement('tr');
                                tr.className = 'align-middle';
                                tr.innerHTML = `
                                    <td class="text-center fw-semibold text-muted">${index + 1}</td>
                                    <td>
                                        <div class="table-cell-title">${item.title}</div>
                                        <small class="table-cell-sub">Slug: ${item.slug || '-'}</small>
                                    </td>
                                    <td>${wilayahBadge}</td>
                                    <td>${fasilitasText}</td>
                                    <td class="text-center">${viewText}</td>
                                    <td class="text-center">${kamarTersediaText}</td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <a href="${kamarRoute}" class="btn btn-sm btn-primary py-1 px-2" title="Kelola Kamar">
                                                <i class="bi bi-door-closed"></i> Kamar
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalImageKosan${item.id}" title="Galeri Foto">
                                                <i class="bi bi-images"></i> Galeri
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEdit${item.id}" title="Edit Kosan">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDelete${item.id}" title="Hapus Kosan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        }
                    })
                    .catch(err => console.error('Error Live Search Kosan:', err));
                }, 250);
            });
        }
    });
</script>

@endsection
