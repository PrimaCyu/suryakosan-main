@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-share-fill text-primary"></i> Kelola Sosial Media
                </h1>
                <p class="page-subtitle">Atur tautan profil sosial media resmi dan saluran komunikasi Sinar Citra Lestari.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSosialMedia">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Sosial Media
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
                        <i class="bi bi-link-45deg text-primary me-2"></i>Daftar Akun & Platform
                    </h5>
                    <p class="text-muted fs-8 mb-0">Total {{ $sosialMedias->total() }} platform terhubung.</p>
                </div>
                <div class="search-box-responsive">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="admin-search-sosial-media" class="form-control border-start-0 ps-0" placeholder="Cari platform..." value="{{ request('search') }}" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- TABEL UTAMA SOSIAL MEDIA -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-min-md" id="table-sosial-media-list">
                        <thead>
                            <tr>
                                <th style="width: 45px" class="text-center">No</th>
                                <th>Nama Platform</th>
                                <th>Tautan / URL</th>
                                <th style="width: 140px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sosial-media-tbody">
                            @forelse ($sosialMedias as $index => $item)
                                <tr class="align-middle">
                                    <td class="text-center fw-semibold text-muted">{{ $sosialMedias->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="p-2 rounded-2 bg-primary-subtle text-primary">
                                                @php
                                                    $titleLower = strtolower($item->title);
                                                    $icon = 'bi-share-fill';
                                                    if (str_contains($titleLower, 'instagram')) $icon = 'bi-instagram';
                                                    elseif (str_contains($titleLower, 'facebook')) $icon = 'bi-facebook';
                                                    elseif (str_contains($titleLower, 'tiktok')) $icon = 'bi-tiktok';
                                                    elseif (str_contains($titleLower, 'youtube')) $icon = 'bi-youtube';
                                                    elseif (str_contains($titleLower, 'whatsapp')) $icon = 'bi-whatsapp';
                                                    elseif (str_contains($titleLower, 'twitter') || str_contains($titleLower, 'x')) $icon = 'bi-twitter-x';
                                                    elseif (str_contains($titleLower, 'telegram')) $icon = 'bi-telegram';
                                                @endphp
                                                <i class="bi {{ $icon }} fs-6"></i>
                                            </div>
                                            <span class="table-cell-title">{{ $item->title }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ $item->url }}" target="_blank" class="badge badge-subtle-primary text-decoration-none py-1 px-2 fs-8">
                                            {{ $item->url }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.65rem;"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditSosialMedia{{ $item->id }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeleteSosialMedia{{ $item->id }}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="bi bi-share display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada tautan sosial media.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted fs-8">
                    Menampilkan {{ $sosialMedias->firstItem() ?? 0 }} - {{ $sosialMedias->lastItem() ?? 0 }} dari {{ $sosialMedias->total() }} akun
                </small>
                <div>
                    {{ $sosialMedias->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH SOSIAL MEDIA -->
        <div class="modal fade" id="modalTambahSosialMedia" tabindex="-1" aria-labelledby="modalTambahSosialMediaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header modal-header-primary text-white">
                        <h5 class="modal-title fs-6 fw-bold" id="modalTambahSosialMediaLabel">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Akun Sosial Media
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.sosial.media.insert') }}" method="POST">
                        @csrf
                        <div class="modal-body p-3 p-md-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="title_tambah" class="form-label">
                                        Nama Platform / Sosial Media <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" id="title_tambah" class="form-control" placeholder="Contoh: Instagram / WhatsApp / TikTok" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="url_tambah" class="form-label">
                                        URL / Link Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="url" name="url" id="url_tambah" class="form-control" placeholder="https://instagram.com/sinarcitralestari" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Sosial Media
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT & DELETE SOSIAL MEDIA -->
        @foreach ($sosialMedias as $item)
            <!-- MODAL EDIT -->
            <div class="modal fade" id="modalEditSosialMedia{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditSosialMediaLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header modal-header-primary text-white">
                            <h5 class="modal-title fs-6 fw-bold" id="modalEditSosialMediaLabel{{ $item->id }}">
                                <i class="bi bi-pencil-square me-2"></i> Edit: {{ $item->title }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.sosial.media.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-3 p-md-4">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="title_edit_{{ $item->id }}" class="form-label">
                                            Nama Platform <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="title" id="title_edit_{{ $item->id }}" class="form-control" value="{{ $item->title }}" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="url_edit_{{ $item->id }}" class="form-label">
                                            URL / Link Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="url" name="url" id="url_edit_{{ $item->id }}" class="form-control" value="{{ $item->url }}" required>
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
            <div class="modal fade" id="modalDeleteSosialMedia{{ $item->id }}" tabindex="-1" aria-labelledby="modalDeleteSosialMediaLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-body p-4 text-center">
                            <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-flex mb-3">
                                <i class="bi bi-trash3-fill fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Hapus Sosial Media?</h6>
                            <p class="text-muted fs-8 mb-0">Apakah Anda yakin ingin menghapus <strong>"{{ $item->title }}"</strong>?</p>
                        </div>
                        <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
                            <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.sosial.media.delete', $item->id) }}" method="POST" class="d-inline">
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
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('admin-search-sosial-media');
        const tbody = document.getElementById('sosial-media-tbody');
        let searchTimeout = null;

        if (searchInput && tbody) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('admin.sosial.media.search.ajax') }}?search=${encodeURIComponent(query)}`, {
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
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="bi bi-share display-6 d-block mb-2 opacity-50"></i>
                                        Data sosial media tidak ditemukan.
                                    </td>
                                </tr>
                            `;
                        } else {
                            items.forEach((item, index) => {
                                const tr = document.createElement('tr');
                                tr.className = 'align-middle';
                                tr.innerHTML = `
                                    <td class="text-center fw-semibold text-muted">${index + 1}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="p-2 rounded-2 bg-primary-subtle text-primary">
                                                <i class="bi bi-share-fill fs-6"></i>
                                            </div>
                                            <span class="table-cell-title">${item.title}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="${item.url}" target="_blank" class="badge badge-subtle-primary text-decoration-none py-1 px-2 fs-8">
                                            ${item.url} <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.65rem;"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditSosialMedia${item.id}">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeleteSosialMedia${item.id}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        }
                    })
                    .catch(err => console.error('Error Live Search Sosial Media:', err));
                }, 250);
            });
        }
    });
</script>

@endsection
