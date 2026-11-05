@extends('backend.dashboard.main')
@section('content')

<style>
    /* ========================================================================= */
    /* MODERN SAAS STYLING FOR SUMMERNOTE & ARTIKEL DASHBOARD                   */
    /* ========================================================================= */

    /* Z-Index Fixes: Ensure Summernote dialogs & dropdowns float above Bootstrap 5 modals */
    .note-modal {
        z-index: 1065 !important;
    }
    .note-modal-backdrop {
        z-index: 1060 !important;
    }
    .note-dropdown-menu {
        z-index: 1070 !important;
        border-radius: 10px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
        padding: 6px !important;
    }
    .note-dropdown-item {
        border-radius: 6px !important;
        padding: 7px 14px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        transition: background-color 0.12s ease;
    }
    .note-dropdown-item:hover {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
    }

    /* Editor Frame */
    .note-editor.note-frame {
        border: 1px solid var(--dash-border, #cbd5e1) !important;
        border-radius: 10px !important;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        background-color: #ffffff;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .note-editor.note-frame:focus-within {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    /* Toolbar Styling */
    .note-toolbar {
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 6px 10px !important;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 3px;
    }
    .note-btn-group {
        display: inline-flex !important;
        align-items: center !important;
        margin-right: 4px !important;
        gap: 1px !important;
    }
    .note-btn-group:not(:last-child)::after {
        content: "";
        display: inline-block;
        width: 1px;
        height: 18px;
        background-color: #e2e8f0;
        margin-left: 6px;
        margin-right: 3px;
    }
    .note-btn {
        border: 1px solid transparent !important;
        background-color: transparent !important;
        color: #475569 !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        padding: 5px 9px !important;
        font-weight: 500 !important;
        line-height: 1.2 !important;
        transition: all 0.12s ease-in-out !important;
    }
    .note-btn:hover {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        border-color: #e2e8f0 !important;
    }
    .note-btn.active, .note-btn:active {
        background-color: #eef2ff !important;
        color: #4f46e5 !important;
        border-color: #c7d2fe !important;
    }

    /* Style dropdown: replace magic wand icon with clear 'Gaya Teks' text */
    .note-btn.dropdown-toggle .note-icon-magic:before {
        content: "Gaya Teks " !important;
        font-family: inherit !important;
        font-weight: 600 !important;
        font-size: 12px !important;
        letter-spacing: -0.01em;
    }

    /* Content Area */
    .note-editable {
        min-height: 280px !important;
        max-height: 480px !important;
        overflow-y: auto !important;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
        font-size: 0.95rem !important;
        line-height: 1.85 !important;
        color: #334155 !important;
        padding: 18px 22px !important;
    }
    .note-editable:focus {
        outline: none !important;
    }
    .note-placeholder {
        padding: 18px 22px !important;
        font-size: 0.95rem !important;
        color: #94a3b8 !important;
    }
    .note-editable h2 {
        font-size: 1.45rem;
        font-weight: 700;
        color: #0f172a;
        margin-top: 1.4rem;
        margin-bottom: 0.6rem;
    }
    .note-editable h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 1.2rem;
        margin-bottom: 0.5rem;
    }
    .note-editable h4 {
        font-size: 1.05rem;
        font-weight: 600;
        color: #334155;
        margin-top: 1rem;
        margin-bottom: 0.4rem;
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
        padding: 10px 18px;
        background-color: #f8fafc;
        color: #475569;
        font-style: italic;
        margin: 1.2rem 0;
        border-radius: 0 8px 8px 0;
    }
    .note-editable hr {
        border-top: 1px solid #e2e8f0;
        margin: 1.5rem 0;
    }

    /* Summernote Modal (Link Dialog, etc.) */
    .note-modal .modal-dialog {
        margin-top: 12vh !important;
    }
    .note-modal .modal-content {
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }
    .note-modal .modal-header {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 12px 18px !important;
    }
    .note-modal .modal-title {
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        color: #0f172a !important;
    }
    .note-modal .form-control {
        font-size: 0.875rem !important;
        border-radius: 6px !important;
        border-color: #cbd5e1 !important;
    }
    .note-modal .btn-primary {
        background-color: #0f172a !important;
        border-color: #0f172a !important;
        border-radius: 6px !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        padding: 6px 14px !important;
    }

    /* Fullscreen Distraction-free Writing Canvas */
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
        height: calc(100vh - 95px) !important;
        max-width: 860px;
        margin: 0 auto;
        padding: 36px 30px !important;
    }
    .note-editor.note-frame.fullscreen .note-toolbar {
        position: sticky;
        top: 0;
        z-index: 100001;
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 10px 24px !important;
        justify-content: center;
    }

    @media (max-width: 767.98px) {
        .note-toolbar {
            padding: 4px 6px !important;
            gap: 2px;
        }
        .note-btn-group {
            margin-right: 2px !important;
        }
        .note-btn-group:not(:last-child)::after {
            display: none;
        }
        .note-btn {
            padding: 4px 6px !important;
            font-size: 11px !important;
        }
        .note-editable {
            padding: 14px !important;
            min-height: 220px !important;
        }
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
                                <th style="width: 80px" class="text-center">Sampul</th>
                                <th>Informasi Artikel</th>
                                <th style="width: 120px" class="text-center">Tayangan</th>
                                <th style="width: 130px">Tanggal</th>
                                <th style="width: 140px" class="text-center">Aksi</th>
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
                                                style="height: 46px; width: 68px; object-fit: cover;"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="rounded-2 border bg-light d-flex align-items-center justify-content-center text-muted mx-auto" style="height: 46px; width: 68px;">
                                                <i class="bi bi-image fs-6 text-secondary opacity-50"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-body-emphasis mb-0.5" style="font-size: 0.92rem;">
                                            {{ $item->title }}
                                        </div>
                                        <div class="text-muted fs-8">
                                            <i class="bi bi-clock me-1"></i>{{ $item->reading_time }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-muted fs-8 tabular-nums">
                                            <i class="bi bi-eye text-muted me-1"></i>{{ number_format($item->view) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-8 fw-medium">
                                            {{ $item->created_at ? $item->created_at->isoFormat('D MMM Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-1.5">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-2"
                                                style="font-size: 0.8rem;"
                                                onclick="openEditModal({{ $item->id }})"
                                                title="Edit Artikel"
                                            >
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </button>
                                            <a
                                                href="{{ route('news.detail', $item->slug) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-sm btn-light border py-1 px-2 rounded-2"
                                                style="font-size: 0.8rem;"
                                                title="Lihat di Web"
                                            >
                                                <i class="bi bi-box-arrow-up-right text-secondary"></i>
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-light border border-danger-subtle text-danger py-1 px-2 rounded-2"
                                                style="font-size: 0.8rem;"
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
<!-- 1. MODAL TAMBAH ARTIKEL                                                   -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalTambahArtikel" tabindex="-1" aria-labelledby="modalTambahArtikelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down" style="max-width: 860px;">
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
                            Gambar Sampul / Banner <span class="text-muted fw-normal">(Opsional, Format JPG/PNG/WEBP, Maks. 5MB)</span>
                        </label>
                        <input
                            type="file"
                            name="image"
                            id="image_tambah"
                            class="form-control form-control-sm"
                            accept="image/jpeg,image/png,image/webp,image/gif"
                            onchange="previewImageTambah(event)"
                        >
                        <div id="previewContainerTambah" class="mt-2 d-none position-relative d-inline-block">
                            <img id="imagePreviewTambah" src="#" alt="Preview Gambar" class="rounded-2 border shadow-sm" style="max-height: 140px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1" onclick="removePreviewTambah()" title="Batalkan Gambar">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tips Menulis Artikel Singkat -->
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2 p-2.5 rounded-2 bg-light border text-secondary fs-8">
                            <i class="bi bi-info-circle text-primary fs-7 shrink-0"></i>
                            <span><strong>Tips Menulis:</strong> Gunakan <strong>Gaya Teks</strong> untuk sub-bab (H2/H3), <strong>Poin</strong> untuk daftar, dan tombol <strong>Fullscreen (⛶)</strong> untuk menulis leluasa tanpa distraksi.</span>
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
                                <i class="bi bi-shield-check text-success me-1"></i>Format otomatis dirapikan saat menyalin dari luar.
                            </div>
                            <div>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fs-9 text-secondary" onclick="cleanEditorFormat('tambah')">
                                    <i class="bi bi-eraser me-1"></i>Bersihkan Gaya Luar
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
<!-- 2. SINGLE UNIFIED MODAL EDIT ARTIKEL                                      -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalEditArtikel" tabindex="-1" aria-labelledby="modalEditArtikelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down" style="max-width: 860px;">
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
                            Ganti Gambar Sampul <span class="text-muted fw-normal">(Biarkan kosong jika tidak diubah, Maks. 5MB)</span>
                        </label>
                        <input
                            type="file"
                            name="image"
                            id="image_edit"
                            class="form-control form-control-sm"
                            accept="image/jpeg,image/png,image/webp,image/gif"
                            onchange="previewImageEdit(event)"
                        >
                        
                        <!-- Container Preview Gambar Saat Ini / Baru -->
                        <div class="mt-2" id="currentImageContainerEdit">
                            <small class="d-block text-muted fs-9 mb-1" id="labelPreviewEdit">Gambar Sampul Saat Ini:</small>
                            <img id="imagePreviewEdit" src="#" alt="Preview" class="rounded-2 border shadow-sm" style="max-height: 130px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Tips Menulis Artikel Singkat -->
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2 p-2.5 rounded-2 bg-light border text-secondary fs-8">
                            <i class="bi bi-info-circle text-primary fs-7 shrink-0"></i>
                            <span><strong>Tips Menulis:</strong> Gunakan <strong>Gaya Teks</strong> untuk sub-bab (H2/H3), <strong>Poin</strong> untuk daftar, dan tombol <strong>Fullscreen (⛶)</strong> untuk menulis leluasa tanpa distraksi.</span>
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
                                <i class="bi bi-shield-check text-success me-1"></i>Format otomatis dirapikan saat menyalin dari luar.
                            </div>
                            <div>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fs-9 text-secondary" onclick="cleanEditorFormat('edit')">
                                    <i class="bi bi-eraser me-1"></i>Bersihkan Gaya Luar
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
<!-- 3. HIDDEN REUSABLE DELETE FORM                                            -->
<!-- ========================================================================= -->
<form id="formDeleteArtikel" action="" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<!-- ========================================================================= -->
<!-- 4. JAVASCRIPT LOGIC & SWEETALERT INTEGRATION                              -->
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
        const slug = (title || '').toLowerCase()
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
            const file = input.files[0];
            if (file.size > 5 * 1024 * 1024) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ukuran Terlalu Besar',
                        text: 'Maksimal ukuran gambar sampul adalah 5MB.',
                        confirmButtonColor: '#0f172a'
                    });
                } else {
                    alert('Maksimal ukuran gambar sampul adalah 5MB.');
                }
                input.value = '';
                if (previewContainer) previewContainer.classList.add('d-none');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
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
            const file = input.files[0];
            if (file.size > 5 * 1024 * 1024) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ukuran Terlalu Besar',
                        text: 'Maksimal ukuran gambar sampul adalah 5MB.',
                        confirmButtonColor: '#0f172a'
                    });
                } else {
                    alert('Maksimal ukuran gambar sampul adalah 5MB.');
                }
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                container.classList.remove('d-none');
                label.textContent = 'Preview Gambar Baru:';
            };
            reader.readAsDataURL(file);
        }
    }

    // Clean, Curated Summernote Config for Modern Long-Form Articles
    const summernoteConfig = {
        placeholder: 'Tuliskan judul bab, paragraf, poin edukasi, atau panduan lengkap artikel di sini...',
        tabsize: 2,
        minHeight: 280,
        dialogsInBody: false,
        dialogsFade: false,
        toolbar: window.innerWidth < 768 ? [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['fullscreen']]
        ] : [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'hr']],
            ['view', ['fullscreen', 'codeview', 'undo', 'redo']]
        ],
        styleTags: [
            { title: 'Normal (Paragraf)', tag: 'p', className: '', value: 'p' },
            { title: 'Judul Bab (H2)', tag: 'h2', className: 'fw-bold text-dark', value: 'h2' },
            { title: 'Sub Judul (H3)', tag: 'h3', className: 'fw-bold text-dark', value: 'h3' },
            { title: 'Sub Bagian (H4)', tag: 'h4', className: 'fw-semibold text-dark', value: 'h4' },
            { title: 'Kutipan Penting (Quote)', tag: 'blockquote', className: 'blockquote', value: 'blockquote' }
        ],
        callbacks: {
            onPaste: function (e) {
                const bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
                if (bufferText) {
                    e.preventDefault();
                    const div = document.createElement('div');
                    div.innerHTML = bufferText;
                    
                    // Clean aggressive external styles that break formatting while keeping semantic tags
                    div.querySelectorAll('*').forEach(el => {
                        if (el.style) {
                            el.style.fontFamily = '';
                            el.style.lineHeight = '';
                            el.style.fontSize = '';
                            el.style.backgroundColor = '';
                            el.style.color = '';
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
        if ($el.length && !$el.data('summernote')) {
            $el.summernote(summernoteConfig);
        }
    }

    function stripHtmlTags(html) {
        const tmp = document.createElement('DIV');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    function updateWordCounter(type) {
        const selector = type === 'edit' ? '#summernote_deskripsi_edit' : '#summernote_deskripsi_tambah';
        const counterEl = document.getElementById(type === 'edit' ? 'counter_edit' : 'counter_tambah');
        const timeEl = document.getElementById(type === 'edit' ? 'time_edit' : 'time_tambah');
        if (!counterEl || !timeEl || !$(selector).length || !$(selector).data('summernote')) return;

        const html = $(selector).summernote('code') || '';
        const text = stripHtmlTags(html).trim();
        const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
        const minutes = Math.max(1, Math.ceil(words / 180));

        counterEl.innerHTML = `<i class="bi bi-fonts me-1"></i>${words} kata`;
        timeEl.innerHTML = `<i class="bi bi-clock me-1"></i>${minutes} mnt baca`;
    }

    function cleanEditorFormat(type) {
        const selector = type === 'edit' ? '#summernote_deskripsi_edit' : '#summernote_deskripsi_tambah';
        const $el = $(selector);
        if (!$el.length || !$el.data('summernote')) return;

        let html = $el.summernote('code');
        const temp = document.createElement('div');
        temp.innerHTML = html;

        temp.querySelectorAll('*').forEach(node => {
            if (node.style) {
                node.style.fontFamily = '';
                node.style.lineHeight = '';
                node.style.fontSize = '';
                node.style.backgroundColor = '';
                node.style.color = '';
            }
            Array.from(node.attributes).forEach(attr => {
                if (attr.name.startsWith('_ng') || attr.name.startsWith('data-')) {
                    node.removeAttribute(attr.name);
                }
            });
        });

        $el.summernote('code', temp.innerHTML);
        updateWordCounter(type);

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Format Dirapikan',
                text: 'Gaya inline dan atribut eksternal berhasil dibersihkan.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    // Open Unified Edit Modal
    function openEditModal(id) {
        const artikel = window.artikelDataMap[id];
        if (!artikel) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Ditemukan',
                    text: 'Detail artikel tidak dapat dimuat.',
                    confirmButtonColor: '#0f172a'
                });
            } else {
                alert('Detail artikel tidak dapat dimuat.');
            }
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
            labelPreview.textContent = 'Gambar Sampul Saat Ini:';
        } else {
            imgContainer.classList.add('d-none');
        }

        const modalEl = document.getElementById('modalEditArtikel');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

        // Populate editor when modal is shown (or immediately if already visible)
        $(modalEl).one('shown.bs.modal', function() {
            initSummernoteArtikel('#summernote_deskripsi_edit');
            $('#summernote_deskripsi_edit').summernote('code', artikel.deskripsi || '');
            updateWordCounter('edit');
        });

        if ($('#summernote_deskripsi_edit').data('summernote')) {
            $('#summernote_deskripsi_edit').summernote('code', artikel.deskripsi || '');
            updateWordCounter('edit');
        }

        modal.show();
    }

    // SweetAlert2 Delete Confirmation
    function confirmDelete(id, title) {
        const artikel = window.artikelDataMap[id];
        const deleteUrl = artikel ? artikel.delete_url : `{{ url('admin/artikel/delete') }}/${id}`;

        if (typeof Swal !== 'undefined') {
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
                    if (form) {
                        form.action = deleteUrl;
                        form.submit();
                    }
                }
            });
        } else {
            if (confirm(`Apakah Anda yakin ingin menghapus artikel "${title}"?`)) {
                const form = document.getElementById('formDeleteArtikel');
                if (form) {
                    form.action = deleteUrl;
                    form.submit();
                }
            }
        }
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

        // Reset Tambah modal on hide so reopened modal is clean
        $('#modalTambahArtikel').on('hidden.bs.modal', function () {
            const form = document.getElementById('formTambahArtikel');
            if (form) form.reset();
            removePreviewTambah();
            if ($('#summernote_deskripsi_tambah').data('summernote')) {
                $('#summernote_deskripsi_tambah').summernote('code', '');
            }
            updateSlugPreview('', 'slug-preview-tambah');
            updateWordCounter('tambah');
        });

        // Initialize Summernote for Edit
        $('#modalEditArtikel').on('shown.bs.modal', function () {
            initSummernoteArtikel('#summernote_deskripsi_edit');
            updateWordCounter('edit');
        });

        // Form Validation on Submit: Synchronize Summernote and prevent empty submission
        document.getElementById('formTambahArtikel').addEventListener('submit', function(e) {
            const $editor = $('#summernote_deskripsi_tambah');
            if ($editor.data('summernote')) {
                const code = $editor.summernote('code');
                $editor.val(code);
                const text = stripHtmlTags(code).trim();
                if ($editor.summernote('isEmpty') || !text) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Konten Belum Diisi',
                            text: 'Silakan isi konten atau naskah artikel sebelum mempublikasikan.',
                            confirmButtonColor: '#0f172a'
                        });
                    } else {
                        alert('Silakan isi konten atau naskah artikel sebelum mempublikasikan.');
                    }
                    return false;
                }
            }
        });

        document.getElementById('formEditArtikel').addEventListener('submit', function(e) {
            const $editor = $('#summernote_deskripsi_edit');
            if ($editor.data('summernote')) {
                const code = $editor.summernote('code');
                $editor.val(code);
                const text = stripHtmlTags(code).trim();
                if ($editor.summernote('isEmpty') || !text) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Konten Belum Diisi',
                            text: 'Silakan isi konten atau naskah artikel sebelum menyimpan.',
                            confirmButtonColor: '#0f172a'
                        });
                    } else {
                        alert('Silakan isi konten atau naskah artikel sebelum menyimpan.');
                    }
                    return false;
                }
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
                                    ? `<img src="${item.image_url}" alt="${item.title}" class="rounded-2 border shadow-sm" style="height: 46px; width: 68px; object-fit: cover;">`
                                    : `<div class="rounded-2 border bg-light d-flex align-items-center justify-content-center text-muted mx-auto" style="height: 46px; width: 68px;">
                                           <i class="bi bi-image fs-6 text-secondary opacity-50"></i>
                                       </div>`;

                                const escapedTitle = (item.title || '').replace(/'/g, "\\'");

                                const tr = document.createElement('tr');
                                tr.className = 'align-middle';
                                tr.innerHTML = `
                                    <td class="text-center fw-semibold text-muted">${index + 1}</td>
                                    <td class="text-center">${imageHtml}</td>
                                    <td>
                                        <div class="fw-semibold text-body-emphasis mb-0.5" style="font-size: 0.92rem;">
                                            ${item.title}
                                        </div>
                                        <div class="text-muted fs-8">
                                            <i class="bi bi-clock me-1"></i>${item.reading_time || '1 mnt baca'}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-muted fs-8 tabular-nums">
                                            <i class="bi bi-eye text-muted me-1"></i>${Number(item.view || 0).toLocaleString('id-ID')}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-8 fw-medium">
                                            ${item.formatted_date || '-'}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-1.5">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-2"
                                                style="font-size: 0.8rem;"
                                                onclick="openEditModal(${item.id})"
                                                title="Edit Artikel"
                                            >
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </button>
                                            <a
                                                href="${item.detail_url}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-sm btn-light border py-1 px-2 rounded-2"
                                                style="font-size: 0.8rem;"
                                                title="Lihat di Web"
                                            >
                                                <i class="bi bi-box-arrow-up-right text-secondary"></i>
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-light border border-danger-subtle text-danger py-1 px-2 rounded-2"
                                                style="font-size: 0.8rem;"
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
