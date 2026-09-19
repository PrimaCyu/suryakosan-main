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
        border-color: var(--dash-border, #cbd5e1) !important;
        border-radius: 10px !important;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        background-color: #ffffff;
    }
    .note-toolbar {
        background-color: var(--dash-bg-subtle, #f8fafc) !important;
        border-bottom: 1px solid var(--dash-border, #e2e8f0) !important;
        padding: 6px 10px !important;
    }
    .note-editable {
        min-height: 420px !important;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
        font-size: 0.95rem !important;
        line-height: 1.85 !important;
        color: #334155 !important;
        padding: 20px 24px !important;
    }
    .note-editable h1, .note-editable h2, .note-editable h3 {
        font-weight: 700;
        color: #0f172a;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
    }
    .note-editable p {
        margin-bottom: 1rem;
    }
    .note-editable ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .note-editable ol {
        list-style-type: decimal;
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .note-editable blockquote {
        border-left: 4px solid #4f46e5;
        padding: 8px 16px;
        background-color: #f8fafc;
        color: #475569;
        font-style: italic;
        margin: 1rem 0;
        border-radius: 0 8px 8px 0;
    }

    /* Fullscreen Mode Optimization for Long-form Writing */
    .note-editor.note-frame.fullscreen {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 100000 !important;
        border-radius: 0 !important;
        background: #ffffff !important;
    }
    .note-editor.note-frame.fullscreen .note-editable {
        height: calc(100vh - 110px) !important;
        max-width: 960px;
        margin: 0 auto;
        padding: 30px 40px !important;
    }
    .note-editor.note-frame.fullscreen .note-toolbar {
        position: sticky;
        top: 0;
        z-index: 100001;
        background-color: #f1f5f9 !important;
        border-bottom: 1px solid #cbd5e1 !important;
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
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form action="{{ route('admin.artikel.insert') }}" method="POST" enctype="multipart/form-data" id="formTambahArtikel" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header border-bottom py-3 px-4 bg-body-tertiary">
                <h5 class="modal-title fs-6 fw-bold" id="modalTambahArtikelLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i> Tulis Artikel Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
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
                            placeholder="Contoh: 5 Tips Memilih Kos Nyaman & Hemat untuk Mahasiswa..."
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

                    <!-- Tips Menulis Artikel -->
                    <div class="col-12">
                        <div class="alert alert-light border d-flex align-items-start gap-2.5 p-2.5 mb-0 rounded-3 text-secondary fs-8">
                            <i class="bi bi-lightbulb-fill text-warning fs-6 mt-0.5 shrink-0"></i>
                            <div>
                                <strong class="text-dark">Tips Menulis Artikel Rapi & Menarik:</strong>
                                Gunakan <strong>Heading 2 / 3</strong> untuk judul bab, <strong>Bullet/Numbered List</strong> untuk poin penting, dan tombol <strong>Fullscreen</strong> (<i class="bi bi-arrows-fullscreen"></i>) di pojok kanan toolbar untuk menulis naskah panjang tanpa gangguan.
                            </div>
                        </div>
                    </div>

                    <!-- Isi Konten Summernote -->
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <label class="form-label fw-bold fs-8 text-body-emphasis mb-0">
                                Konten / Isi Lengkap Naskah Artikel <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-2 fs-9 text-muted">
                                <span id="counter_tambah"><i class="bi bi-fonts me-1"></i>0 kata</span>
                                <span>•</span>
                                <span id="time_tambah"><i class="bi bi-clock me-1"></i>1 mnt baca</span>
                            </div>
                        </div>
                        <textarea name="deskripsi" id="summernote_deskripsi_tambah" class="form-control"></textarea>
                        <div class="d-flex justify-content-between align-items-center mt-1.5 fs-9 text-muted">
                            <div>
                                <i class="bi bi-shield-check text-success me-1"></i>Format teks otomatis dibersihkan saat menyalin dari luar (AI / Word).
                            </div>
                            <div>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fs-9 text-secondary" onclick="cleanEditorFormat('tambah')">
                                    <i class="bi bi-eraser me-1"></i>Bersihkan Gaya Inline
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2.5 px-4 bg-body-tertiary">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm" id="btn-submit-tambah">
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
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form action="" method="POST" enctype="multipart/form-data" id="formEditArtikel" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            @method('PUT')
            <div class="modal-header border-bottom py-3 px-4 bg-body-tertiary">
                <h5 class="modal-title fs-6 fw-bold" id="modalEditArtikelLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i> Edit Artikel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
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

                    <!-- Tips Menulis Artikel -->
                    <div class="col-12">
                        <div class="alert alert-light border d-flex align-items-start gap-2.5 p-2.5 mb-0 rounded-3 text-secondary fs-8">
                            <i class="bi bi-lightbulb-fill text-warning fs-6 mt-0.5 shrink-0"></i>
                            <div>
                                <strong class="text-dark">Tips Menulis Artikel Rapi & Menarik:</strong>
                                Gunakan <strong>Heading 2 / 3</strong> untuk judul bab, <strong>Bullet/Numbered List</strong> untuk poin penting, dan tombol <strong>Fullscreen</strong> (<i class="bi bi-arrows-fullscreen"></i>) di pojok kanan toolbar untuk menulis naskah panjang tanpa gangguan.
                            </div>
                        </div>
                    </div>

                    <!-- Isi Konten Summernote -->
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <label class="form-label fw-bold fs-8 text-body-emphasis mb-0">
                                Konten / Isi Lengkap Naskah Artikel <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-2 fs-9 text-muted">
                                <span id="counter_edit"><i class="bi bi-fonts me-1"></i>0 kata</span>
                                <span>•</span>
                                <span id="time_edit"><i class="bi bi-clock me-1"></i>1 mnt baca</span>
                            </div>
                        </div>
                        <textarea name="deskripsi" id="summernote_deskripsi_edit" class="form-control"></textarea>
                        <div class="d-flex justify-content-between align-items-center mt-1.5 fs-9 text-muted">
                            <div>
                                <i class="bi bi-shield-check text-success me-1"></i>Format teks otomatis dibersihkan saat menyalin dari luar (AI / Word).
                            </div>
                            <div>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fs-9 text-secondary" onclick="cleanEditorFormat('edit')">
                                    <i class="bi bi-eraser me-1"></i>Bersihkan Gaya Inline
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2.5 px-4 bg-body-tertiary">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm" id="btn-submit-edit">
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

    // Common Summernote Config for Long-Form Articles
    const summernoteConfig = {
        placeholder: 'Tuliskan judul bab, paragraf, poin edukasi, atau panduan lengkap artikel di sini...',
        tabsize: 2,
        minHeight: 420,
        dialogsInBody: true,
        dialogsFade: true,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video', 'hr']],
            ['view', ['fullscreen', 'codeview', 'undo', 'redo']]
        ],
        styleTags: [
            'p',
            { title: 'Judul Utama (H2)', tag: 'h2', className: 'fw-bold fs-4 text-dark', value: 'h2' },
            { title: 'Sub Judul (H3)', tag: 'h3', className: 'fw-bold fs-5 text-dark', value: 'h3' },
            { title: 'Sub Bagian (H4)', tag: 'h4', className: 'fw-semibold fs-6 text-dark', value: 'h4' },
            { title: 'Kutipan Menarik (Quote)', tag: 'blockquote', className: 'blockquote', value: 'blockquote' },
            { title: 'Blok Kode / Catatan', tag: 'pre', className: 'bg-light p-2', value: 'pre' }
        ],
        fontSizes: ['11', '12', '13', '14', '15', '16', '18', '20', '24', '28', '32', '36'],
        lineHeights: ['1.0', '1.2', '1.4', '1.6', '1.8', '2.0'],
        callbacks: {
            onPaste: function (e) {
                const bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
                if (bufferText) {
                    e.preventDefault();
                    const div = document.createElement('div');
                    div.innerHTML = bufferText;
                    
                    // Clean aggressive styles that break formatting while keeping semantic tags
                    div.querySelectorAll('*').forEach(el => {
                        if (el.style) {
                            el.style.fontFamily = '';
                            el.style.lineHeight = '';
                            el.style.fontSize = '';
                            el.style.backgroundColor = '';
                        }
                        Array.from(el.attributes).forEach(attr => {
                            if (attr.name.startsWith('_ng') || attr.name.startsWith('data-')) {
                                el.removeAttribute(attr.name);
                            }
                        });
                    });
                    
                    document.execCommand('insertHTML', false, div.innerHTML);
                }
            },
            onChange: function(contents, $editable) {
                if ($editable.closest('#modalTambahArtikel').length) {
                    updateWordCounter('tambah');
                } else if ($editable.closest('#modalEditArtikel').length) {
                    updateWordCounter('edit');
                }
            }
        }
    };

    function initSummernoteArtikel(selector) {
        const $el = $(selector);
        if ($el.length && !$el.next('.note-editor').length) {
            $el.summernote(summernoteConfig);
        }
    }

    function updateWordCounter(type) {
        const selector = type === 'edit' ? '#summernote_deskripsi_edit' : '#summernote_deskripsi_tambah';
        const counterEl = document.getElementById(type === 'edit' ? 'counter_edit' : 'counter_tambah');
        const timeEl = document.getElementById(type === 'edit' ? 'time_edit' : 'time_tambah');
        if (!counterEl || !timeEl || !$(selector).length) return;

        const html = $(selector).summernote('code') || '';
        const temp = document.createElement('div');
        temp.innerHTML = html;
        const text = (temp.textContent || temp.innerText || '').trim();
        const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
        const minutes = Math.max(1, Math.ceil(words / 180));

        counterEl.innerHTML = `<i class="bi bi-fonts me-1"></i>${words} kata`;
        timeEl.innerHTML = `<i class="bi bi-clock me-1"></i>${minutes} mnt baca`;
    }

    function cleanEditorFormat(type) {
        const selector = type === 'edit' ? '#summernote_deskripsi_edit' : '#summernote_deskripsi_tambah';
        const $el = $(selector);
        if (!$el.length) return;

        let html = $el.summernote('code');
        const temp = document.createElement('div');
        temp.innerHTML = html;

        temp.querySelectorAll('*').forEach(node => {
            if (node.style) {
                node.style.fontFamily = '';
                node.style.lineHeight = '';
                node.style.fontSize = '';
                node.style.backgroundColor = '';
            }
            Array.from(node.attributes).forEach(attr => {
                if (attr.name.startsWith('_ng') || attr.name.startsWith('data-')) {
                    node.removeAttribute(attr.name);
                }
            });
        });

        $el.summernote('code', temp.innerHTML);
        updateWordCounter(type);

        Swal.fire({
            icon: 'success',
            title: 'Format Dirapikan',
            text: 'Gaya inline dan atribut berlebih berhasil dibersihkan.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
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

        // Initialize Summernote first if needed
        initSummernoteArtikel('#summernote_deskripsi_edit');

        // Set Summernote content safely
        $('#summernote_deskripsi_edit').summernote('code', artikel.deskripsi || '');
        updateWordCounter('edit');

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
        // Initialize Summernote for Tambah
        $('#modalTambahArtikel').on('shown.bs.modal', function () {
            initSummernoteArtikel('#summernote_deskripsi_tambah');
            updateWordCounter('tambah');
        });

        // Initialize Summernote for Edit
        $('#modalEditArtikel').on('shown.bs.modal', function () {
            initSummernoteArtikel('#summernote_deskripsi_edit');
            updateWordCounter('edit');
        });

        // Form Validation on Submit: Prevent empty Summernote and sync content
        document.getElementById('formTambahArtikel').addEventListener('submit', function(e) {
            const editor = $('#summernote_deskripsi_tambah');
            editor.val(editor.summernote('code'));
            const content = editor.summernote('isEmpty');
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
            const editor = $('#summernote_deskripsi_edit');
            editor.val(editor.summernote('code'));
            const content = editor.summernote('isEmpty');
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
