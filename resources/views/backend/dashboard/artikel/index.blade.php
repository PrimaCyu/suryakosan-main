@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-file-earmark-text-fill text-primary"></i> Kelola Artikel Blog
                </h1>
                <p class="page-subtitle">Publikasikan konten edukasi sewa kos, tips properti, dan panduan untuk calon penyewa.</p>
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

        <div class="card mb-4">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h5 class="card-title mb-0 fs-6 fw-bold">
                        <i class="bi bi-journal-richtext text-primary me-2"></i>Daftar Artikel Blog
                    </h5>
                    <p class="text-muted fs-8 mb-0">Total {{ $artikels->total() }} artikel terpublikasi.</p>
                </div>
                <div class="search-box-responsive">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="admin-search-artikel" class="form-control border-start-0 ps-0" placeholder="Cari artikel real-time..." value="{{ request('search') }}" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- TABEL UTAMA ARTIKEL -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-min-md" id="table-artikel-list">
                        <thead>
                            <tr>
                                <th style="width: 45px" class="text-center">No</th>
                                <th style="width: 90px" class="text-center">Gambar</th>
                                <th>Judul Artikel</th>
                                <th class="text-center">Views</th>
                                <th>Tanggal</th>
                                <th style="width: 140px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="artikel-tbody">
                            @forelse ($artikels as $index => $item)
                                <tr class="align-middle">
                                    <td class="text-center fw-semibold text-muted">{{ $artikels->firstItem() + $index }}</td>
                                    <td class="text-center">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="rounded-2 border shadow-sm" style="height: 48px; width: 70px; object-fit: cover;">
                                        @else
                                            <span class="badge badge-subtle-secondary fs-8">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-cell-title">{{ $item->title }}</div>
                                        <small class="table-cell-sub">Slug: {{ $item->slug }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-subtle-primary">
                                            <i class="bi bi-eye-fill me-1"></i>{{ number_format($item->view) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="table-cell-sub">{{ $item->created_at ? $item->created_at->isoFormat('D MMM Y') : '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditArtikel{{ $item->id }}" title="Edit Artikel">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeleteArtikel{{ $item->id }}" title="Hapus Artikel">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-journal-x display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada artikel yang dipublikasikan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted fs-8">
                    Menampilkan {{ $artikels->firstItem() ?? 0 }} - {{ $artikels->lastItem() ?? 0 }} dari {{ $artikels->total() }} artikel
                </small>
                <div>
                    {{ $artikels->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH ARTIKEL -->
        <div class="modal fade" id="modalTambahArtikel" tabindex="-1" aria-labelledby="modalTambahArtikelLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header modal-header-primary text-white">
                        <h5 class="modal-title fs-6 fw-bold" id="modalTambahArtikelLabel">
                            <i class="bi bi-pencil-square me-2"></i> Tulis Artikel Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.artikel.insert') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-3 p-md-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="title_tambah" class="form-label">
                                        Judul Artikel <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" id="title_tambah" class="form-control" placeholder="Masukkan judul artikel yang menarik..." required>
                                </div>

                                <div class="col-md-12">
                                    <label for="image_tambah" class="form-label">Gambar Sampul / Utama</label>
                                    <input type="file" name="image" id="image_tambah" class="form-control" accept="image/*" onchange="previewImageTambah(event)">
                                    <div id="previewContainerTambah" class="mt-2 d-none">
                                        <img id="imagePreviewTambah" src="#" alt="Preview Gambar" class="rounded-2 border shadow-sm" style="max-height: 140px;">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="summernote_deskripsi_tambah" class="form-label">Konten / Isi Artikel <span class="text-danger">*</span></label>
                                    <textarea name="deskripsi" id="summernote_deskripsi_tambah" class="form-control summernote-artikel"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-save me-1"></i> Publikasikan Artikel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT & DELETE ARTIKEL -->
        @foreach ($artikels as $item)
            <!-- MODAL EDIT -->
            <div class="modal fade" id="modalEditArtikel{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditArtikelLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header modal-header-primary text-white">
                            <h5 class="modal-title fs-6 fw-bold" id="modalEditArtikelLabel{{ $item->id }}">
                                <i class="bi bi-pencil-square me-2"></i> Edit Artikel: {{ $item->title }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.artikel.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-3 p-md-4">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="title_edit_{{ $item->id }}" class="form-label">
                                            Judul Artikel <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="title" id="title_edit_{{ $item->id }}" class="form-control" value="{{ $item->title }}" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="image_edit_{{ $item->id }}" class="form-label">Ganti Gambar Utama (Opsional)</label>
                                        <input type="file" name="image" id="image_edit_{{ $item->id }}" class="form-control" accept="image/*" onchange="previewImageEdit(event, {{ $item->id }})">

                                        <div class="mt-2">
                                            @if($item->image)
                                                <small class="d-block text-muted mb-1">Gambar Saat Ini:</small>
                                                <img id="imagePreviewEdit{{ $item->id }}" src="{{ asset('storage/' . $item->image) }}" alt="Preview" class="rounded-2 border shadow-sm" style="max-height: 120px;">
                                            @else
                                                <div id="previewContainerEdit{{ $item->id }}" class="d-none">
                                                    <img id="imagePreviewEdit{{ $item->id }}" src="#" alt="Preview" class="rounded-2 border shadow-sm" style="max-height: 120px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="summernote_edit_{{ $item->id }}" class="form-label">Konten / Isi Artikel <span class="text-danger">*</span></label>
                                        <textarea name="deskripsi" id="summernote_edit_{{ $item->id }}" class="form-control summernote-edit-artikel">{!! $item->deskripsi !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL DELETE -->
            <div class="modal fade" id="modalDeleteArtikel{{ $item->id }}" tabindex="-1" aria-labelledby="modalDeleteArtikelLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-body p-4 text-center">
                            <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-flex mb-3">
                                <i class="bi bi-trash3-fill fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Hapus Artikel?</h6>
                            <p class="text-muted fs-8 mb-0">Apakah Anda yakin ingin menghapus artikel <strong>"{{ $item->title }}"</strong>?</p>
                        </div>
                        <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
                            <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.artikel.delete', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger px-3">
                                    <i class="bi bi-trash me-1"></i> Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>

<script>
    function previewImageTambah(event) {
        const input = event.target;
        const previewContainer = document.getElementById('previewContainerTambah');
        const imagePreview = document.getElementById('imagePreviewTambah');

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

    function previewImageEdit(event, id) {
        const input = event.target;
        const imagePreview = document.getElementById(`imagePreviewEdit${id}`);
        const previewContainer = document.getElementById(`previewContainerEdit${id}`);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if(imagePreview) {
                    imagePreview.src = e.target.result;
                }
                if(previewContainer) {
                    previewContainer.classList.remove('d-none');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        $('#modalTambahArtikel').on('shown.bs.modal', function () {
            $('#summernote_deskripsi_tambah').summernote({
                placeholder: 'Tuliskan isi atau deskripsi lengkap artikel di sini...',
                tabsize: 2,
                height: 220,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        });

        $('#modalTambahArtikel').on('hidden.bs.modal', function () {
            $('#summernote_deskripsi_tambah').summernote('destroy');
        });

        $('.modal').on('shown.bs.modal', function () {
            $(this).find('.summernote-edit-artikel').summernote({
                placeholder: 'Tuliskan isi atau deskripsi lengkap artikel di sini...',
                tabsize: 2,
                height: 220,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        });

        $('.modal').on('hidden.bs.modal', function () {
            $(this).find('.summernote-edit-artikel').summernote('destroy');
        });

        const searchInput = document.getElementById('admin-search-artikel');
        const tbody = document.getElementById('artikel-tbody');
        let searchTimeout = null;

        if (searchInput && tbody) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('admin.artikel.search.ajax') }}?search=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(res => {
                        tbody.innerHTML = '';
                        const items = res.data || [];

                        if (items.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-journal-x display-6 d-block mb-2 opacity-50"></i>
                                        Data artikel tidak ditemukan.
                                    </td>
                                </tr>
                            `;
                        } else {
                            items.forEach((item, index) => {
                                const imageHtml = item.image
                                    ? `<img src="{{ asset('storage') }}/${item.image}" alt="${item.title}" class="rounded-2 border shadow-sm" style="height: 48px; width: 70px; object-fit: cover;">`
                                    : `<span class="badge badge-subtle-secondary fs-8">No Image</span>`;

                                const tr = document.createElement('tr');
                                tr.className = 'align-middle';
                                tr.innerHTML = `
                                    <td class="text-center fw-semibold text-muted">${index + 1}</td>
                                    <td class="text-center">${imageHtml}</td>
                                    <td>
                                        <div class="table-cell-title">${item.title}</div>
                                        <small class="table-cell-sub">Slug: ${item.slug || '-'}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-subtle-primary">
                                            <i class="bi bi-eye-fill me-1"></i>${item.view}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="table-cell-sub">-</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditArtikel${item.id}">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeleteArtikel${item.id}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        }
                    })
                    .catch(err => console.error('Error Live Search Artikel:', err));
                }, 250);
            });
        }
    });
</script>

@endsection
