@extends('backend.dashboard.main')
@section('content')

<style>
    .rating-star-options {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 6px;
    }
    .rating-star-options input[type="radio"] {
        display: none;
    }
    .rating-star-options label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #cbd5e1;
        transition: color 0.15s ease-in-out, transform 0.15s ease;
    }
    .rating-star-options label:hover,
    .rating-star-options label:hover ~ label,
    .rating-star-options input[type="radio"]:checked ~ label {
        color: #f59e0b;
        transform: scale(1.08);
    }
</style>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-chat-left-quote-fill text-primary"></i> Kelola Testimoni & Review
                </h1>
                <p class="page-subtitle">Kelola ulasan kepuasan, pengalaman penghuni kos, dan rating bintang.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTestimoni">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Testimoni
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
                        <i class="bi bi-star-half text-warning me-2"></i>Daftar Testimoni Pelanggan
                    </h5>
                    <p class="text-muted fs-8 mb-0">Total {{ $testimonis->total() }} ulasan tercatat.</p>
                </div>
                <div class="search-box-responsive">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="admin-search-testimoni" class="form-control border-start-0 ps-0" placeholder="Cari nama pelanggan..." value="{{ request('search') }}" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- TABEL UTAMA TESTIMONI -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-min-md" id="table-testimoni-list">
                        <thead>
                            <tr>
                                <th style="width: 45px" class="text-center">No</th>
                                <th style="width: 70px" class="text-center">Profil</th>
                                <th>Nama Pelanggan</th>
                                <th>Rating</th>
                                <th>Review / Ulasan</th>
                                <th style="width: 140px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="testimoni-tbody">
                            @forelse ($testimonis as $index => $item)
                                <tr class="align-middle">
                                    <td class="text-center fw-semibold text-muted">{{ $testimonis->firstItem() + $index }}</td>
                                    <td class="text-center">
                                        @if($item->image_profile)
                                            <img src="{{ asset('storage/' . $item->image_profile) }}" alt="{{ $item->name }}" class="rounded-circle border shadow-sm" style="width: 38px; height: 38px; object-fit: cover;">
                                        @else
                                            <div class="user-avatar-badge mx-auto" style="width: 36px; height: 36px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($item->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-cell-title">{{ $item->name }}</div>
                                    </td>
                                    <td>
                                        <div class="text-warning fs-8 d-flex align-items-center gap-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $item->rating)
                                                    <i class="bi bi-star-fill"></i>
                                                @else
                                                    <i class="bi bi-star text-muted opacity-30"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1 text-dark fw-bold fs-8">({{ $item->rating }}/5)</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->review)
                                            <span class="table-cell-sub">"{{ Str::limit($item->review, 85) }}"</span>
                                        @else
                                            <span class="text-muted fs-8">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditTestimoni{{ $item->id }}" title="Edit Testimoni">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeleteTestimoni{{ $item->id }}" title="Hapus Testimoni">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-chat-square-dots display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada testimoni pelanggan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted fs-8">
                    Menampilkan {{ $testimonis->firstItem() ?? 0 }} - {{ $testimonis->lastItem() ?? 0 }} dari {{ $testimonis->total() }} testimoni
                </small>
                <div>
                    {{ $testimonis->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH TESTIMONI -->
        <div class="modal fade" id="modalTambahTestimoni" tabindex="-1" aria-labelledby="modalTambahTestimoniLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header modal-header-primary text-white">
                        <h5 class="modal-title fs-6 fw-bold" id="modalTambahTestimoniLabel">
                            <i class="bi bi-chat-quote-fill me-2"></i> Tambah Testimoni Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.testimoni.insert') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-3 p-md-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name_tambah" class="form-label">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name_tambah" class="form-control" placeholder="Nama pemberi testimoni" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Rating Bintang <span class="text-danger">*</span>
                                    </label>
                                    <div class="rating-star-options pt-1">
                                        <input type="radio" id="star5_tambah" name="rating" value="5" checked />
                                        <label for="star5_tambah" title="5 Bintang"><i class="bi bi-star-fill"></i></label>

                                        <input type="radio" id="star4_tambah" name="rating" value="4" />
                                        <label for="star4_tambah" title="4 Bintang"><i class="bi bi-star-fill"></i></label>

                                        <input type="radio" id="star3_tambah" name="rating" value="3" />
                                        <label for="star3_tambah" title="3 Bintang"><i class="bi bi-star-fill"></i></label>

                                        <input type="radio" id="star2_tambah" name="rating" value="2" />
                                        <label for="star2_tambah" title="2 Bintang"><i class="bi bi-star-fill"></i></label>

                                        <input type="radio" id="star1_tambah" name="rating" value="1" />
                                        <label for="star1_tambah" title="1 Bintang"><i class="bi bi-star-fill"></i></label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="image_profile_tambah" class="form-label">Foto Profil Pelanggan</label>
                                    <input type="file" name="image_profile" id="image_profile_tambah" class="form-control" accept="image/*" onchange="previewImageProfileTambah(event)">
                                    <div id="previewProfileContainerTambah" class="mt-2 d-none">
                                        <img id="imageProfilePreviewTambah" src="#" alt="Preview" class="rounded-circle border shadow-sm" style="width: 65px; height: 65px; object-fit: cover;">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="review_tambah" class="form-label">Ulasan / Pesan Testimoni <span class="text-danger">*</span></label>
                                    <textarea name="review" id="review_tambah" class="form-control" rows="4" placeholder="Tuliskan testimoni atau review dari penghuni..." required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Testimoni
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT & DELETE TESTIMONI -->
        @foreach ($testimonis as $item)
            <!-- MODAL EDIT -->
            <div class="modal fade" id="modalEditTestimoni{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditTestimoniLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header modal-header-primary text-white">
                            <h5 class="modal-title fs-6 fw-bold" id="modalEditTestimoniLabel{{ $item->id }}">
                                <i class="bi bi-pencil-square me-2"></i> Edit Testimoni: {{ $item->name }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.testimoni.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-3 p-md-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name_edit_{{ $item->id }}" class="form-label">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" id="name_edit_{{ $item->id }}" class="form-control" value="{{ $item->name }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Rating Bintang <span class="text-danger">*</span>
                                        </label>
                                        <div class="rating-star-options pt-1">
                                            <input type="radio" id="star5_edit_{{ $item->id }}" name="rating" value="5" {{ $item->rating == 5 ? 'checked' : '' }} />
                                            <label for="star5_edit_{{ $item->id }}" title="5 Bintang"><i class="bi bi-star-fill"></i></label>

                                            <input type="radio" id="star4_edit_{{ $item->id }}" name="rating" value="4" {{ $item->rating == 4 ? 'checked' : '' }} />
                                            <label for="star4_edit_{{ $item->id }}" title="4 Bintang"><i class="bi bi-star-fill"></i></label>

                                            <input type="radio" id="star3_edit_{{ $item->id }}" name="rating" value="3" {{ $item->rating == 3 ? 'checked' : '' }} />
                                            <label for="star3_edit_{{ $item->id }}" title="3 Bintang"><i class="bi bi-star-fill"></i></label>

                                            <input type="radio" id="star2_edit_{{ $item->id }}" name="rating" value="2" {{ $item->rating == 2 ? 'checked' : '' }} />
                                            <label for="star2_edit_{{ $item->id }}" title="2 Bintang"><i class="bi bi-star-fill"></i></label>

                                            <input type="radio" id="star1_edit_{{ $item->id }}" name="rating" value="1" {{ $item->rating == 1 ? 'checked' : '' }} />
                                            <label for="star1_edit_{{ $item->id }}" title="1 Bintang"><i class="bi bi-star-fill"></i></label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="image_profile_edit_{{ $item->id }}" class="form-label">Ganti Foto Profil (Opsional)</label>
                                        <input type="file" name="image_profile" id="image_profile_edit_{{ $item->id }}" class="form-control" accept="image/*" onchange="previewImageProfileEdit(event, {{ $item->id }})">

                                        <div class="mt-2">
                                            @if($item->image_profile)
                                                <small class="d-block text-muted mb-1">Foto Saat Ini:</small>
                                                <img id="imageProfilePreviewEdit{{ $item->id }}" src="{{ asset('storage/' . $item->image_profile) }}" alt="Preview" class="rounded-circle border shadow-sm" style="width: 65px; height: 65px; object-fit: cover;">
                                            @else
                                                <div id="previewProfileContainerEdit{{ $item->id }}" class="d-none">
                                                    <img id="imageProfilePreviewEdit{{ $item->id }}" src="#" alt="Preview" class="rounded-circle border shadow-sm" style="width: 65px; height: 65px; object-fit: cover;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="review_edit_{{ $item->id }}" class="form-label">Ulasan / Pesan Testimoni <span class="text-danger">*</span></label>
                                        <textarea name="review" id="review_edit_{{ $item->id }}" class="form-control" rows="4" required>{{ $item->review }}</textarea>
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
            <div class="modal fade" id="modalDeleteTestimoni{{ $item->id }}" tabindex="-1" aria-labelledby="modalDeleteTestimoniLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-body p-4 text-center">
                            <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-flex mb-3">
                                <i class="bi bi-trash3-fill fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Hapus Testimoni?</h6>
                            <p class="text-muted fs-8 mb-0">Apakah Anda yakin ingin menghapus ulasan dari <strong>"{{ $item->name }}"</strong>?</p>
                        </div>
                        <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
                            <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.testimoni.delete', $item->id) }}" method="POST" class="d-inline">
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
    function previewImageProfileTambah(event) {
        const input = event.target;
        const previewContainer = document.getElementById('previewProfileContainerTambah');
        const imagePreview = document.getElementById('imageProfilePreviewTambah');

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

    function previewImageProfileEdit(event, id) {
        const input = event.target;
        const imagePreview = document.getElementById(`imageProfilePreviewEdit${id}`);
        const previewContainer = document.getElementById(`previewProfileContainerEdit${id}`);

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
        const searchInput = document.getElementById('admin-search-testimoni');
        const tbody = document.getElementById('testimoni-tbody');
        let searchTimeout = null;

        if (searchInput && tbody) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('admin.testimoni.search.ajax') }}?search=${encodeURIComponent(query)}`, {
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
                                        <i class="bi bi-chat-square-dots display-6 d-block mb-2 opacity-50"></i>
                                        Data testimoni tidak ditemukan.
                                    </td>
                                </tr>
                            `;
                        } else {
                            items.forEach((item, index) => {
                                let profileHtml = '';
                                if (item.image_profile) {
                                    profileHtml = `<img src="{{ asset('storage') }}/${item.image_profile}" alt="${item.name}" class="rounded-circle border shadow-sm" style="width: 38px; height: 38px; object-fit: cover;">`;
                                } else {
                                    const initial = item.name ? item.name.charAt(0).toUpperCase() : '?';
                                    profileHtml = `<div class="user-avatar-badge mx-auto" style="width: 36px; height: 36px; font-size: 0.8rem;">${initial}</div>`;
                                }

                                let starsHtml = '';
                                for (let i = 1; i <= 5; i++) {
                                    if (i <= item.rating) {
                                        starsHtml += `<i class="bi bi-star-fill"></i>`;
                                    } else {
                                        starsHtml += `<i class="bi bi-star text-muted opacity-30"></i>`;
                                    }
                                }
                                starsHtml += `<span class="ms-1 text-dark fw-bold fs-8">(${item.rating}/5)</span>`;

                                const reviewSnippet = item.review
                                    ? (item.review.length > 85 ? `"${item.review.substring(0, 85)}..."` : `"${item.review}"`)
                                    : '-';

                                const tr = document.createElement('tr');
                                tr.className = 'align-middle';
                                tr.innerHTML = `
                                    <td class="text-center fw-semibold text-muted">${index + 1}</td>
                                    <td class="text-center">${profileHtml}</td>
                                    <td><div class="table-cell-title">${item.name}</div></td>
                                    <td><div class="text-warning fs-8 d-flex align-items-center gap-1">${starsHtml}</div></td>
                                    <td><span class="table-cell-sub">${reviewSnippet}</span></td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditTestimoni${item.id}">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeleteTestimoni${item.id}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        }
                    })
                    .catch(err => console.error('Error Live Search Testimoni:', err));
                }, 250);
            });
        }
    });
</script>

@endsection
