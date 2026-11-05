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
                                    ? `<div class="text-secondary fs-8"><i class="bi bi-geo-alt text-muted me-1"></i>${item.wilayah}</div>`
                                    : `<span class="text-muted fs-8">-</span>`;

                                const fasilitasText = item.fasilitas
                                    ? `<div class="text-muted fs-8 text-truncate" style="max-width: 220px;" title="${item.fasilitas}">${item.fasilitas}</div>`
                                    : `<span class="text-muted fs-8">-</span>`;

                                const viewText = item.view
                                    ? `<span class="text-muted fs-8"><i class="bi bi-eye text-muted me-1"></i>${item.view}</span>`
                                    : `<span class="text-muted fs-8">0</span>`;

                                const kamarTersediaText = item.tersedia !== undefined && item.tersedia !== null
                                    ? `<span class="text-muted fs-8">${item.tersedia} Unit</span>`
                                    : `<span class="text-muted fs-8">-</span>`;

                                @if(Auth::user()->isSuperAdmin())
                                const deleteButtonHtml = `
                                    <button type="button" class="btn btn-sm btn-light border border-danger-subtle py-1 px-2 rounded-2 text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete${item.id}" title="Hapus Kosan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                `;
                                @else
                                const deleteButtonHtml = '';
                                @endif

                                const webUrl = `/kosan/detail/${item.slug || ''}`;

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
                                        <div class="d-inline-flex align-items-center gap-1.5">
                                            <!-- Kamar Button -->
                                            <a href="${kamarRoute}" class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-2" style="font-size: 0.8rem;" title="Kelola Unit Kamar">
                                                <i class="bi bi-door-open me-1"></i>Kamar
                                            </a>

                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-2" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#modalEdit${item.id}" title="Edit Info Kosan">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </button>

                                            <!-- Galeri Foto Button -->
                                            <button type="button" class="btn btn-sm btn-light border py-1 px-2 rounded-2 text-secondary" data-bs-toggle="modal" data-bs-target="#modalImageKosan${item.id}" title="Kelola Galeri Foto">
                                                <i class="bi bi-images"></i>
                                            </button>

                                            <!-- Lihat di Web Button -->
                                            <a href="${webUrl}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-light border py-1 px-2 rounded-2 text-secondary" title="Lihat Halaman Web">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>

                                            ${deleteButtonHtml}
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

            // Tekan tombol Enter untuk pencarian server-side lengkap beserta modal
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    window.location.href = `{{ route('admin.product.kosan.index') }}?search=${encodeURIComponent(this.value.trim())}`;
                }
            });

            // Fallback klik tombol jika modal belum dimuat di DOM saat pencarian AJAX
            tbody.addEventListener('click', function(e) {
                const btn = e.target.closest('button[data-bs-target]');
                if (btn) {
                    const targetSelector = btn.getAttribute('data-bs-target');
                    if (targetSelector && !document.querySelector(targetSelector)) {
                        e.preventDefault();
                        const titleEl = btn.closest('tr')?.querySelector('.table-cell-title');
                        const query = titleEl ? titleEl.textContent.trim() : searchInput.value.trim();
                        window.location.href = `{{ route('admin.product.kosan.index') }}?search=${encodeURIComponent(query)}`;
                    }
                }
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
        // --- PREVIEW FOTO-FOTO MODAL TAMBAH KOSAN (MULTI-IMAGE) ---
        const kosanImageInput = document.getElementById('kosanImageInput');
        const kosanPlaceholder = document.getElementById('kosanPlaceholder');
        const kosanPreviewWrap = document.getElementById('kosanPreviewWrap');
        const kosanFileName = document.getElementById('kosanFileName');
        const kosanImagesGrid = document.getElementById('kosanImagesGrid');
        const btnCancelKosanImg = document.getElementById('btnCancelKosanImg');

        if (kosanImageInput) {
            kosanImageInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                if (files.length > 0) {
                    if (kosanImagesGrid) kosanImagesGrid.innerHTML = '';
                    if (kosanFileName) kosanFileName.textContent = `${files.length} Foto Dipilih`;
                    
                    files.forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(evt) {
                                const card = document.createElement('div');
                                card.className = 'multi-image-card';
                                card.innerHTML = `
                                    <span class="badge-idx">#${index + 1}</span>
                                    <img src="${evt.target.result}" alt="${file.name}">
                                `;
                                if (kosanImagesGrid) kosanImagesGrid.appendChild(card);
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    if (kosanPlaceholder) kosanPlaceholder.classList.add('d-none');
                    if (kosanPreviewWrap) kosanPreviewWrap.classList.remove('d-none');
                }
            });
        }

        if (btnCancelKosanImg) {
            btnCancelKosanImg.addEventListener('click', function() {
                if (kosanImageInput) kosanImageInput.value = '';
                if (kosanImagesGrid) kosanImagesGrid.innerHTML = '';
                if (kosanFileName) kosanFileName.textContent = '0 Foto Dipilih';
                if (kosanPreviewWrap) kosanPreviewWrap.classList.add('d-none');
                if (kosanPlaceholder) kosanPlaceholder.classList.remove('d-none');
            });
        }
    });
</script>

@endsection
