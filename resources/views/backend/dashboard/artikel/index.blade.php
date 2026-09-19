@extends('backend.dashboard.main')
@section('content')

<style>
    /* Fix Summernote modal z-index when inside Bootstrap 5 modal */
    .note-modal {
        z-index: 1065 !important;
    }
    .note-modal-backdrop {
        z-index: 1060 !important;
    }
    .note-editor.note-frame {
        border-color: var(--dash-border, #e2e8f0) !important;
        border-radius: 8px !important;
        overflow: hidden;
    }
    .note-toolbar {
        background-color: var(--dash-bg-subtle, #f8fafc) !important;
        border-bottom: 1px solid var(--dash-border, #e2e8f0) !important;
    }
</style>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-file-earmark-text text-secondary"></i> Kelola Artikel Blog
                </h1>
                <p class="page-subtitle">Publikasikan konten edukasi sewa kos, tips hunian, dan panduan informatif untuk calon penyewa.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahArtikel">
                    <i class="bi bi-plus-lg me-1"></i> Tulis Artikel Baru
                </button>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @include('backend.dashboard.kosan.kamar.partials.alerts')

        <!-- KPI STATS METRICS ROW -->
        <div class="kpi-stat-grid mb-4">
            <!-- 1. Total Artikel -->
            <div class="kpi-stat-card">
                <div class="kpi-stat-icon kpi-icon-indigo">
                    <i class="bi bi-journal-richtext"></i>
                </div>
                <div class="kpi-stat-content">
                    <div class="kpi-stat-label">Total Artikel</div>
                    <div class="kpi-stat-value">{{ number_format($kpiStats['total_artikels']) }} <span class="fs-7 fw-normal text-muted">Postingan</span></div>
                    <div class="kpi-stat-sub">
                        <i class="bi bi-check-circle-fill text-success"></i> Konten terpublikasi aktif
                    </div>
                </div>
            </div>

            <!-- 2. Total Views -->
            <div class="kpi-stat-card">
                <div class="kpi-stat-icon kpi-icon-success">
                    <i class="bi bi-eye-fill"></i>
                </div>
                <div class="kpi-stat-content">
                    <div class="kpi-stat-label">Total Tayangan</div>
                    <div class="kpi-stat-value text-success">{{ number_format($kpiStats['total_views']) }} <span class="fs-7 fw-normal text-muted">Views</span></div>
                    <div class="kpi-stat-sub">
                        <span>Akumulasi seluruh pembaca</span>
                    </div>
                </div>
            </div>

            <!-- 3. Rata-rata Views -->
            <div class="kpi-stat-card">
                <div class="kpi-stat-icon kpi-icon-primary">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <div class="kpi-stat-content">
                    <div class="kpi-stat-label">Rata-rata Tayangan</div>
                    <div class="kpi-stat-value text-primary">{{ number_format($kpiStats['avg_views']) }} <span class="fs-7 fw-normal text-muted">/ artikel</span></div>
                    <div class="kpi-stat-sub">
                        <span>Tingkat keterbacaan artikel</span>
                    </div>
                </div>
            </div>

            <!-- 4. Artikel Terpopuler -->
            <div class="kpi-stat-card">
                <div class="kpi-stat-icon kpi-icon-warning">
                    <i class="bi bi-fire"></i>
                </div>
                <div class="kpi-stat-content">
                    <div class="kpi-stat-label">Artikel Terpopuler</div>
                    <div class="kpi-stat-value text-truncate" style="max-width: 200px; font-size: 1.15rem;" title="{{ $kpiStats['top_title'] }}">
                        {{ $kpiStats['top_title'] }}
                    </div>
                    <div class="kpi-stat-sub text-warning fw-semibold">
                        <i class="bi bi-trophy-fill me-1"></i> {{ number_format($kpiStats['top_views']) }} views
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN TABLE CARD -->
        <div class="card mb-4 border shadow-sm">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3">
                <div class="d-flex flex-column">
                    <h5 class="card-title mb-1 fs-6 fw-bold text-body-emphasis d-flex align-items-center">
                        <i class="bi bi-journal-text text-secondary me-2"></i>Daftar Artikel Blog
                    </h5>
                    <p class="text-muted fs-8 mb-0">Menampilkan {{ $artikels->total() }} artikel yang tersedia di sistem.</p>
                </div>

                <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
                    <!-- SORT DROPDOWN -->
                    <div class="d-flex align-items-center gap-1.5">
                        <label for="admin-sort-artikel" class="fs-8 text-muted fw-semibold text-nowrap d-none d-sm-inline">Urutkan:</label>
                        <select id="admin-sort-artikel" class="form-select form-select-sm" style="min-width: 140px;" onchange="applySort(this.value)">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Terpopuler (Views)</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>Judul (A - Z)</option>
                        </select>
                    </div>

                    <!-- SEARCH INPUT -->
                    <div class="search-box-responsive position-relative">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-end-0 text-muted">
                                <i class="bi bi-search" id="search-spinner-icon"></i>
                            </span>
                            <input
                                type="text"
                                id="admin-search-artikel"
                                class="form-control border-start-0 ps-0"
                                placeholder="Cari artikel real-time..."
                                value="{{ request('search') }}"
                                autocomplete="off"
                            >
                            <button type="button" class="btn btn-outline-secondary border-start-0 d-none" id="btn-clear-search" onclick="clearSearch()" title="Hapus Pencarian">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-min-md" id="table-artikel-list">
                        <thead>
                            <tr class="table-light">
                                <th style="width: 50px" class="text-center">No</th>
                                <th style="width: 90px" class="text-center">Sampul</th>
                                <th>Informasi Artikel</th>
                                <th style="width: 120px" class="text-center">Tayangan</th>
                                <th style="width: 140px">Tanggal</th>
                                <th style="width: 150px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="artikel-tbody">
                            @forelse ($artikels as $index => $item)
                                <tr class="align-middle">
                                    <td class="text-center fw-semibold text-muted">{{ $artikels->firstItem() + $index }}</td>
                                    <td class="text-center">
                                        @if($item->image)
                                            <img
                                                src="{{ asset('storage/' . $item->image) }}"
                                                alt="{{ $item->title }}"
                                                class="rounded-2 border shadow-sm"
                                                style="height: 50px; width: 75px; object-fit: cover;"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="rounded-2 border bg-light d-flex align-items-center justify-content-center text-muted mx-auto" style="height: 50px; width: 75px;">
                                                <i class="bi bi-image fs-5 text-secondary opacity-50"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-body-emphasis mb-1" style="font-size: 0.92rem;">
                                            {{ $item->title }}
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-8">
                                                /news/detail/{{ $item->slug }}
                                            </span>
                                            <span class="badge bg-light text-muted border fs-8">
                                                <i class="bi bi-clock me-1"></i>{{ $item->reading_time }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fw-bold fs-8">
                                            <i class="bi bi-eye-fill me-1"></i>{{ number_format($item->view) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-8 fw-medium">
                                            {{ $item->created_at ? $item->created_at->isoFormat('D MMM Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1.5">
                                            <a
                                                href="{{ route('news.detail', $item->slug) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-sm btn-outline-info py-1 px-2 rounded-2"
                                                title="Lihat di Web Frontend"
                                            >
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-2"
                                                onclick="openEditModal({{ $item->id }})"
                                                title="Edit Artikel"
                                            >
                                                <i class="bi bi-pencil-square me-1"></i>Edit
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger py-1 px-2 rounded-2"
                                                onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->title) }}')"
                                                title="Hapus Artikel"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-journal-x display-6 d-block mb-2 opacity-50"></i>
                                        <div class="fw-semibold text-body-emphasis">Belum Ada Artikel</div>
                                        <p class="fs-8 text-muted mb-3">Mulai publikasikan konten pertama Anda untuk menarik calon penyewa kos.</p>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahArtikel">
                                            <i class="bi bi-plus-lg me-1"></i> Tulis Artikel Baru
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PAGINATION FOOTER -->
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2 py-3 bg-white" id="artikel-pagination-footer">
                <small class="text-muted fs-8">
                    Menampilkan {{ $artikels->firstItem() ?? 0 }} - {{ $artikels->lastItem() ?? 0 }} dari {{ $artikels->total() }} artikel
                </small>
                <div>
                    {{ $artikels->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- 1. MODAL TAMBAH ARTIKEL -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalTambahArtikel" tabindex="-1" aria-labelledby="modalTambahArtikelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('admin.artikel.insert') }}" method="POST" enctype="multipart/form-data" id="formTambahArtikel" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fs-6 fw-bold" id="modalTambahArtikelLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i> Tulis Artikel Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <div class="row g-3">
                    <!-- Judul Artikel -->
                    <div class="col-12">
                        <label for="title_tambah" class="form-label fw-bold fs-8 text-body-emphasis">
                            Judul Artikel <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title_tambah"
                            class="form-control form-control-sm"
                            placeholder="Contoh: 5 Tips Memilih Kos Nyaman Dekat Kampus..."
                            required
                            oninput="updateSlugPreview(this.value, 'slug-preview-tambah')"
                        >
                        <div class="mt-1 fs-9 text-muted d-flex align-items-center gap-1">
                            <span>URL Preview:</span>
                            <span class="badge bg-secondary-subtle text-secondary font-monospace" id="slug-preview-tambah">/news/detail/...</span>
                        </div>
                    </div>

                    <!-- Gambar Sampul -->
                    <div class="col-12">
                        <label for="image_tambah" class="form-label fw-bold fs-8 text-body-emphasis">
                            Gambar Sampul / Banner <span class="text-muted fw-normal">(Opsional, Maks. 5MB)</span>
                        </label>
                        <input
                            type="file"
                            name="image"
                            id="image_tambah"
                            class="form-control form-control-sm"
                            accept="image/*"
                            onchange="previewImageTambah(event)"
                        >
                        <div id="previewContainerTambah" class="mt-2 d-none position-relative d-inline-block">
                            <img id="imagePreviewTambah" src="#" alt="Preview Gambar" class="rounded-2 border shadow-sm" style="max-height: 140px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1" onclick="removePreviewTambah()" title="Batalkan Gambar">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Isi Konten Summernote -->
                    <div class="col-12">
                        <label class="form-label fw-bold fs-8 text-body-emphasis">
                            Konten / Isi Lengkap Artikel <span class="text-danger">*</span>
                        </label>
                        <textarea name="deskripsi" id="summernote_deskripsi_tambah" class="form-control"></textarea>
                        <div class="form-text fs-9 text-muted">
                            Format teks, masukkan poin, tautan, dan gambar pendukung menggunakan editor di atas.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2.5">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm" id="btn-submit-tambah">
                    <i class="bi bi-send-fill me-1"></i> Publikasikan Artikel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. SINGLE UNIFIED MODAL EDIT ARTIKEL -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalEditArtikel" tabindex="-1" aria-labelledby="modalEditArtikelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="" method="POST" enctype="multipart/form-data" id="formEditArtikel" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            @method('PUT')
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fs-6 fw-bold" id="modalEditArtikelLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i> Edit Artikel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <div class="row g-3">
                    <!-- Judul Artikel -->
                    <div class="col-12">
                        <label for="title_edit" class="form-label fw-bold fs-8 text-body-emphasis">
                            Judul Artikel <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title_edit"
                            class="form-control form-control-sm"
                            required
                            oninput="updateSlugPreview(this.value, 'slug-preview-edit')"
                        >
                        <div class="mt-1 fs-9 text-muted d-flex align-items-center gap-1">
                            <span>URL Preview:</span>
                            <span class="badge bg-secondary-subtle text-secondary font-monospace" id="slug-preview-edit">/news/detail/...</span>
                        </div>
                    </div>

                    <!-- Gambar Sampul -->
                    <div class="col-12">
                        <label for="image_edit" class="form-label fw-bold fs-8 text-body-emphasis">
                            Ganti Gambar Sampul <span class="text-muted fw-normal">(Biarkan kosong jika tidak ingin mengganti, Maks. 5MB)</span>
                        </label>
                        <input
                            type="file"
                            name="image"
                            id="image_edit"
                            class="form-control form-control-sm"
                            accept="image/*"
                            onchange="previewImageEdit(event)"
                        >
                        
                        <!-- Container Preview Gambar Saat Ini / Baru -->
                        <div class="mt-2" id="currentImageContainerEdit">
                            <small class="d-block text-muted fs-9 mb-1" id="labelPreviewEdit">Gambar Saat Ini:</small>
                            <img id="imagePreviewEdit" src="#" alt="Preview" class="rounded-2 border shadow-sm" style="max-height: 130px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Isi Konten Summernote -->
                    <div class="col-12">
                        <label class="form-label fw-bold fs-8 text-body-emphasis">
                            Konten / Isi Lengkap Artikel <span class="text-danger">*</span>
                        </label>
                        <textarea name="deskripsi" id="summernote_deskripsi_edit" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2.5">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm" id="btn-submit-edit">
                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3. HIDDEN REUSABLE DELETE FORM -->
<!-- ========================================================================= -->
<form id="formDeleteArtikel" action="" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<!-- ========================================================================= -->
<!-- 4. JAVASCRIPT LOGIC & SWEETALERT INTEGRATION -->
<!-- ========================================================================= -->
<script>
    // Data Map for Instant Modal Population
    window.artikelDataMap = {};

    @foreach($artikels as $a)
        window.artikelDataMap[{{ $a->id }}] = {
            id: {{ $a->id }},
            title: {!! json_encode($a->title) !!},
            slug: {!! json_encode($a->slug) !!},
            image: {!! json_encode($a->image) !!},
            image_url: {!! json_encode($a->image_url) !!},
            deskripsi: {!! json_encode($a->deskripsi) !!},
            update_url: {!! json_encode(route('admin.artikel.update', $a->id)) !!},
            delete_url: {!! json_encode(route('admin.artikel.delete', $a->id)) !!},
            detail_url: {!! json_encode(route('news.detail', $a->slug)) !!}
        };
    @endforeach

    // Helper: Live Slug Preview Generator
    function updateSlugPreview(title, targetElementId) {
        const target = document.getElementById(targetElementId);
        if (!target) return;
        const slug = title.toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        target.textContent = `/news/detail/${slug || '...'}`;
    }

    // Helper: Image Preview for Tambah
    function previewImageTambah(event) {
        const input = event.target;
        const previewContainer = document.getElementById('previewContainerTambah');
        const imagePreview = document.getElementById('imagePreviewTambah');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePreviewTambah() {
        const input = document.getElementById('image_tambah');
        const previewContainer = document.getElementById('previewContainerTambah');
        if (input) input.value = '';
        if (previewContainer) previewContainer.classList.add('d-none');
    }

    // Helper: Image Preview for Edit
    function previewImageEdit(event) {
        const input = event.target;
        const imagePreview = document.getElementById('imagePreviewEdit');
        const container = document.getElementById('currentImageContainerEdit');
        const label = document.getElementById('labelPreviewEdit');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                container.classList.remove('d-none');
                label.textContent = 'Preview Gambar Baru:';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Open Unified Edit Modal
    function openEditModal(id) {
        const artikel = window.artikelDataMap[id];
        if (!artikel) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Ditemukan',
                text: 'Detail artikel tidak dapat dimuat.',
                confirmButtonColor: '#0f172a'
            });
            return;
        }

        const form = document.getElementById('formEditArtikel');
        form.action = artikel.update_url;

        const titleInput = document.getElementById('title_edit');
        titleInput.value = artikel.title;
        updateSlugPreview(artikel.title, 'slug-preview-edit');

        // Reset file input
        const fileInput = document.getElementById('image_edit');
        if (fileInput) fileInput.value = '';

        // Image preview
        const imgPreview = document.getElementById('imagePreviewEdit');
        const imgContainer = document.getElementById('currentImageContainerEdit');
        const labelPreview = document.getElementById('labelPreviewEdit');

        if (artikel.image_url) {
            imgPreview.src = artikel.image_url;
            imgContainer.classList.remove('d-none');
            labelPreview.textContent = 'Gambar Saat Ini:';
        } else {
            imgContainer.classList.add('d-none');
        }

        // Set Summernote content safely
        $('#summernote_deskripsi_edit').summernote('code', artikel.deskripsi || '');

        const modalEl = document.getElementById('modalEditArtikel');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    // SweetAlert2 Delete Confirmation
    function confirmDelete(id, title) {
        const artikel = window.artikelDataMap[id];
        const deleteUrl = artikel ? artikel.delete_url : `{{ url('admin/artikel/delete') }}/${id}`;

        Swal.fire({
            title: 'Hapus Artikel Blog?',
            html: `Apakah Anda yakin ingin menghapus artikel <strong>"${title}"</strong>?<br><small class="text-muted">Tindakan ini permanen dan akan menghapus gambar sampul dari server.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b91c1c',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formDeleteArtikel');
                form.action = deleteUrl;
                form.submit();
            }
        });
    }

    // Apply Sort Selection
    function applySort(sortValue) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort', sortValue);
        currentUrl.searchParams.delete('page');
        window.location.href = currentUrl.toString();
    }

    // Document Ready: Summernote & AJAX Live Search
    document.addEventListener("DOMContentLoaded", function() {
        
        // Common Summernote Config
        const summernoteConfig = {
            placeholder: 'Tuliskan isi atau deskripsi lengkap artikel di sini...',
            tabsize: 2,
            height: 250,
            dialogsInBody: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview']]
            ]
        };

        // Initialize Summernote for Tambah
        $('#modalTambahArtikel').on('shown.bs.modal', function () {
            if (!$('#summernote_deskripsi_tambah').next('.note-editor').length) {
                $('#summernote_deskripsi_tambah').summernote(summernoteConfig);
            }
        });

        // Initialize Summernote for Edit
        $('#modalEditArtikel').on('shown.bs.modal', function () {
            if (!$('#summernote_deskripsi_edit').next('.note-editor').length) {
                $('#summernote_deskripsi_edit').summernote(summernoteConfig);
            }
        });

        // Form Validation on Submit: Prevent empty Summernote
        document.getElementById('formTambahArtikel').addEventListener('submit', function(e) {
            const content = $('#summernote_deskripsi_tambah').summernote('isEmpty');
            if (content) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Konten Belum Diisi',
                    text: 'Silakan isi konten atau naskah artikel sebelum mempublikasikan.',
                    confirmButtonColor: '#0f172a'
                });
            }
        });

        document.getElementById('formEditArtikel').addEventListener('submit', function(e) {
            const content = $('#summernote_deskripsi_edit').summernote('isEmpty');
            if (content) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Konten Belum Diisi',
                    text: 'Silakan isi konten atau naskah artikel sebelum menyimpan.',
                    confirmButtonColor: '#0f172a'
                });
            }
        });

        // =====================================================================
        // Real-Time AJAX Live Search
        // =====================================================================
        const searchInput = document.getElementById('admin-search-artikel');
        const clearBtn = document.getElementById('btn-clear-search');
        const tbody = document.getElementById('artikel-tbody');
        const paginationFooter = document.getElementById('artikel-pagination-footer');
        const originalTbodyHtml = tbody.innerHTML;
        const originalFooterHtml = paginationFooter ? paginationFooter.innerHTML : '';
        let searchTimeout = null;

        if (searchInput && tbody) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                const currentSort = document.getElementById('admin-sort-artikel')?.value || 'latest';

                if (query.length > 0) {
                    clearBtn?.classList.remove('d-none');
                } else {
                    clearBtn?.classList.add('d-none');
                    tbody.innerHTML = originalTbodyHtml;
                    if (paginationFooter) paginationFooter.innerHTML = originalFooterHtml;
                    return;
                }

                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    const spinner = document.getElementById('search-spinner-icon');
                    if (spinner) spinner.className = 'spinner-border spinner-border-sm text-primary';

                    fetch(`{{ route('admin.artikel.search.ajax') }}?search=${encodeURIComponent(query)}&sort=${encodeURIComponent(currentSort)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (spinner) spinner.className = 'bi bi-search';
                        tbody.innerHTML = '';
                        const items = res.data || [];

                        if (items.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-journal-x display-6 d-block mb-2 opacity-50"></i>
                                        <div class="fw-semibold text-body-emphasis">Tidak Ada Artikel yang Cocok</div>
                                        <small class="text-muted">Tidak ditemukan hasil untuk kata kunci "${query}".</small>
                                    </td>
                                </tr>
                            `;
                            if (paginationFooter) {
                                paginationFooter.innerHTML = `<small class="text-muted fs-8">Hasil pencarian: 0 artikel ditemukan</small>`;
                            }
                        } else {
                            if (paginationFooter) {
                                paginationFooter.innerHTML = `<small class="text-muted fs-8">Hasil pencarian real-time: <strong>${items.length} artikel</strong> ditemukan untuk "${query}"</small>`;
                            }

                            items.forEach((item, index) => {
                                // Cache in data map for edit modal
                                window.artikelDataMap[item.id] = item;

                                const imageHtml = item.image_url
                                    ? `<img src="${item.image_url}" alt="${item.title}" class="rounded-2 border shadow-sm" style="height: 50px; width: 75px; object-fit: cover;">`
                                    : `<div class="rounded-2 border bg-light d-flex align-items-center justify-content-center text-muted mx-auto" style="height: 50px; width: 75px;">
                                           <i class="bi bi-image fs-5 text-secondary opacity-50"></i>
                                       </div>`;

                                const safeTitle = (item.title || '').replace(/"/g, '&quot;');
                                const escapedTitle = (item.title || '').replace(/'/g, "\\'");

                                const tr = document.createElement('tr');
                                tr.className = 'align-middle';
                                tr.innerHTML = `
                                    <td class="text-center fw-semibold text-muted">${index + 1}</td>
                                    <td class="text-center">${imageHtml}</td>
                                    <td>
                                        <div class="fw-bold text-body-emphasis mb-1" style="font-size: 0.92rem;">
                                            ${item.title}
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-8">
                                                /news/detail/${item.slug || '-'}
                                            </span>
                                            <span class="badge bg-light text-muted border fs-8">
                                                <i class="bi bi-clock me-1"></i>${item.reading_time || '1 mnt baca'}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fw-bold fs-8">
                                            <i class="bi bi-eye-fill me-1"></i>${Number(item.view || 0).toLocaleString('id-ID')}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-8 fw-medium">
                                            ${item.formatted_date || '-'}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1.5">
                                            <a
                                                href="${item.detail_url}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-sm btn-outline-info py-1 px-2 rounded-2"
                                                title="Lihat di Web Frontend"
                                            >
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-2"
                                                onclick="openEditModal(${item.id})"
                                                title="Edit Artikel"
                                            >
                                                <i class="bi bi-pencil-square me-1"></i>Edit
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger py-1 px-2 rounded-2"
                                                onclick="confirmDelete(${item.id}, '${escapedTitle}')"
                                                title="Hapus Artikel"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        }
                    })
                    .catch(err => {
                        if (spinner) spinner.className = 'bi bi-search';
                        console.error('Error Live Search Artikel:', err);
                    });
                }, 250);
            });
        }
    });

    function clearSearch() {
        const searchInput = document.getElementById('admin-search-artikel');
        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        }
    }
</script>

@endsection
