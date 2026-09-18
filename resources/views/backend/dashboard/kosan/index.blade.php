@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-house-door text-secondary"></i> Kelola Properti Kos-kosan
                </h1>
                <p class="page-subtitle">Manajemen daftar properti kos, spesifikasi wilayah, fasilitas, dan unit kamar.</p>
            </div>
            <div>
                @if(Auth::user()->isSuperAdmin())
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kosan Baru
                    </button>
                @else
                    <span class="badge badge-subtle-secondary">
                        <i class="bi bi-shield-check me-1"></i> Mode Admin Cabang
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @include('backend.dashboard.kosan.kamar.partials.alerts')

        <!-- KPI Metrics Okupansi Seluruh Properti -->
        @if(isset($kpiStats))
        <div class="kpi-stat-grid">
            <!-- 1. Total Properti Kosan -->
            <div class="kpi-stat-card">
                <div class="kpi-stat-icon kpi-icon-indigo">
                    <i class="bi bi-buildings-fill"></i>
                </div>
                <div class="kpi-stat-content">
                    <div class="kpi-stat-label">Total Properti Kos</div>
                    <div class="kpi-stat-value">{{ number_format($kpiStats['total_kosan']) }} <span class="fs-7 fw-normal text-muted">Cabang</span></div>
                    <div class="kpi-stat-sub">
                        <i class="bi bi-geo-alt-fill text-primary"></i> Seluruh unit terdaftar
                    </div>
                </div>
            </div>

            <!-- 2. Total Kapasitas Kamar -->
            <div class="kpi-stat-card">
                <div class="kpi-stat-icon kpi-icon-primary">
                    <i class="bi bi-door-closed-fill"></i>
                </div>
                <div class="kpi-stat-content">
                    <div class="kpi-stat-label">Total Kapasitas</div>
                    <div class="kpi-stat-value">{{ number_format($kpiStats['total_capacity']) }} <span class="fs-7 fw-normal text-muted">Kamar</span></div>
                    <div class="kpi-stat-sub">
                        <i class="bi bi-layer-forward text-info"></i> Kapasitas seluruh cabang
                    </div>
                </div>
            </div>

            <!-- 3. Ketersediaan Kamar -->
            <div class="kpi-stat-card">
                <div class="kpi-stat-icon kpi-icon-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="kpi-stat-content">
                    <div class="kpi-stat-label">Kamar Kosong (Siap Huni)</div>
                    <div class="kpi-stat-value text-success">{{ number_format($kpiStats['total_available']) }} <span class="fs-7 fw-normal text-muted">Unit</span></div>
                    <div class="kpi-stat-sub">
                        <span>{{ number_format($kpiStats['total_occupied']) }} Terisi saat ini</span>
                    </div>
                </div>
            </div>

            <!-- 4. Rata-rata Okupansi -->
            <div class="kpi-stat-card">
                <div class="kpi-stat-icon {{ $kpiStats['occupancy_rate'] >= 75 ? 'kpi-icon-success' : ($kpiStats['occupancy_rate'] >= 50 ? 'kpi-icon-primary' : 'kpi-icon-warning') }}">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="kpi-stat-content">
                    <div class="kpi-stat-label">Tingkat Okupansi</div>
                    <div class="kpi-stat-value">{{ $kpiStats['occupancy_rate'] }}%</div>
                    <div class="kpi-progress">
                        <div class="kpi-progress-bar {{ $kpiStats['occupancy_rate'] >= 75 ? 'bg-success' : ($kpiStats['occupancy_rate'] >= 50 ? 'bg-primary' : 'bg-warning') }}"
                             style="width: {{ min(100, $kpiStats['occupancy_rate']) }}%"></div>
                    </div>
                    <div class="kpi-stat-sub mt-1">
                        <small>{{ $kpiStats['total_occupied'] }} dari {{ $kpiStats['total_capacity'] }} kamar tersewa</small>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="tab-content" id="kosanTabContent">
            <!-- Partial: Tab Data Kos-kosan -->
            @include('backend.dashboard.kosan.partials-kosan._data_kosan')
        </div> <!-- end tab-content -->

        @if(Auth::user()->isSuperAdmin())
            <!-- Partial: Modal Tambah Kosan -->
            @include('backend.dashboard.kosan.partials-kosan._modals')
        @endif
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
        const isSuperAdmin = {{ Auth::user()->isSuperAdmin() ? 'true' : 'false' }};
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

                                @if(Auth::user()->isSuperAdmin())
                                const deleteBtnHtml = `
                                    <button type="button" class="btn-action-icon act-delete" data-bs-toggle="modal" data-bs-target="#modalDelete${item.id}" title="Hapus Kosan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                `;
                                @else
                                const deleteBtnHtml = '';
                                @endif

                                const webUrl = `/kosan/${item.slug || ''}`;

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
                                    <td class="text-center text-nowrap">
                                        <div class="table-action-compact justify-content-center">
                                            <!-- Kamar Button -->
                                            <a href="${kamarRoute}" class="btn btn-sm btn-primary" title="Kelola Unit Kamar">
                                                <i class="bi bi-door-closed me-1"></i>Kamar
                                            </a>
                                            <!-- Edit Button -->
                                            <button type="button" class="btn-action-icon act-edit" data-bs-toggle="modal" data-bs-target="#modalEdit${item.id}" title="Edit Info Kosan">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <!-- Galeri Button -->
                                            <button type="button" class="btn-action-icon act-gallery" data-bs-toggle="modal" data-bs-target="#modalImageKosan${item.id}" title="Kelola Galeri Foto">
                                                <i class="bi bi-images"></i>
                                            </button>
                                            <!-- Web Button -->
                                            <a href="${webUrl}" target="_blank" rel="noopener noreferrer" class="btn-action-icon act-web" title="Buka Halaman Publik">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            ${deleteBtnHtml}
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
                    submitBtn.innerHTML = `<i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Foto Sekarang`;
                }
                if (countText) countText.textContent = 'Belum ada file dipilih.';
                return;
            }

            container.classList.remove('d-none');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload ${files.length} Foto Sekarang`;
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
                                submitBtn.innerHTML = `<i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Foto Sekarang`;
                            }
                            const countText = form.querySelector('small[id^="fileCountText"]');
                            if (countText) countText.textContent = 'Belum ada file dipilih.';
                        }
                    }
                }
            }
        });
        // --- PREVIEW FOTO UTAMA MODAL TAMBAH KOSAN ---
        const kosanImageInput = document.getElementById('kosanImageInput');
        const kosanDropzone = document.getElementById('kosanDropzone');
        const kosanPlaceholder = document.getElementById('kosanPlaceholder');
        const kosanPreviewWrap = document.getElementById('kosanPreviewWrap');
        const kosanPreviewImg = document.getElementById('kosanPreviewImg');
        const kosanFileName = document.getElementById('kosanFileName');
        const btnCancelKosanImg = document.getElementById('btnCancelKosanImg');

        if (kosanImageInput) {
            kosanImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        if (kosanPreviewImg) kosanPreviewImg.src = evt.target.result;
                        if (kosanFileName) kosanFileName.textContent = file.name;
                        if (kosanPlaceholder) kosanPlaceholder.classList.add('d-none');
                        if (kosanPreviewWrap) kosanPreviewWrap.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (btnCancelKosanImg) {
            btnCancelKosanImg.addEventListener('click', function() {
                if (kosanImageInput) kosanImageInput.value = '';
                if (kosanPreviewImg) kosanPreviewImg.src = '';
                if (kosanFileName) kosanFileName.textContent = '';
                if (kosanPreviewWrap) kosanPreviewWrap.classList.add('d-none');
                if (kosanPlaceholder) kosanPlaceholder.classList.remove('d-none');
            });
        }
    });
</script>

@endsection
