<!-- TAB 1: DATA KOS-KOSAN -->
<div class="tab-pane fade show active" id="tab-kosan" role="tabpanel" aria-labelledby="tab-kosan-btn">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <h5 class="card-title mb-0 fs-6 fw-bold">
                    <i class="bi bi-list-stars text-primary me-2"></i>Daftar Properti Kosan
                </h5>
                <p class="text-muted fs-8 mb-0">Total {{ $product_kosan->total() }} properti kos terdaftar.</p>
            </div>
            <div class="search-box-responsive">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-transparent border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="admin-search-kosan" class="form-control border-start-0 ps-0" placeholder="Cari nama, wilayah, fasilitas..." value="{{ request('search') }}" autocomplete="off">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- TABEL UTAMA -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-min-lg" id="table-kosan-list">
                    <thead>
                        <tr>
                            <th style="width: 45px" class="text-center">No</th>
                            <th>Nama Kosan</th>
                            <th>Wilayah</th>
                            <th>Fasilitas Unggulan</th>
                            <th class="text-center">Views</th>
                            <th class="text-center">Kamar</th>
                            <th class="text-center" style="width: 230px">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody id="kosan-tbody">
                        @forelse ($product_kosan as $index => $item)
                            <tr class="align-middle">
                                <td class="text-center fw-semibold text-muted">{{ $product_kosan->firstItem() + $index }}</td>
                                <td>
                                    <div class="table-cell-title">{{ $item->title }}</div>
                                    <small class="table-cell-sub">Slug: {{ $item->slug }}</small>
                                </td>
                                <td>
                                    @if($item->wilayah)
                                        <span class="badge badge-subtle-info">
                                            <i class="bi bi-geo-alt-fill me-1"></i>{{ $item->wilayah }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-8">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->fasilitas)
                                        @php
                                            $fasArr = array_filter(array_map('trim', explode(',', $item->fasilitas)));
                                        @endphp
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach(array_slice($fasArr, 0, 2) as $f)
                                                <span class="badge badge-subtle-secondary fs-8">{{ $f }}</span>
                                            @endforeach
                                            @if(count($fasArr) > 2)
                                                <span class="badge badge-subtle-secondary fs-8 text-muted">+{{ count($fasArr) - 2 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted fs-8">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-subtle-primary">
                                        <i class="bi bi-eye-fill me-1"></i>{{ number_format($item->view ?? 0) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($item->tersedia !== null)
                                        <span class="badge badge-subtle-success">
                                            <i class="bi bi-door-closed me-1"></i>{{ $item->tersedia }} Unit
                                        </span>
                                    @else
                                        <span class="text-muted fs-8">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="action-btn-group">
                                        <!-- Kamar Button -->
                                        <a href="{{ route('admin.product.kosan.kamar.index', $item->id) }}" class="btn btn-sm btn-primary py-1 px-2" title="Kelola Kamar">
                                            <i class="bi bi-door-closed"></i> Kamar
                                        </a>

                                        <!-- Image Button -->
                                        <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalImageKosan{{ $item->id }}" title="Galeri Foto">
                                            <i class="bi bi-images"></i> Galeri
                                        </button>

                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}" title="Edit Kosan">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Delete Button -->
                                        @if(Auth::user()->isSuperAdmin())
                                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $item->id }}" title="Hapus Kosan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="bi bi-houses display-6 d-block mb-2 text-secondary opacity-50"></i>
                                        <h6 class="fw-bold text-dark mb-1">
                                            @if(Auth::user()->isSuperAdmin())
                                                Belum Ada Data Kos-Kosan Terdaftar
                                            @else
                                                Belum Ada Properti Kos yang Ditugaskan
                                            @endif
                                        </h6>
                                        <p class="text-muted fs-8 mb-3" style="max-width: 480px; margin: auto;">
                                            @if(Auth::user()->isSuperAdmin())
                                                Mulai tambahkan kos-kosan baru dengan mengklik tombol <strong>Tambah Kosan Baru</strong> di atas.
                                            @else
                                                Akun Anda berstatus <strong>Admin Cabang</strong>. Anda hanya dapat mengelola properti kos yang telah ditugaskan secara resmi oleh Super Admin. Silakan hubungi Super Admin untuk penugasan properti cabang.
                                            @endif
                                        </p>
                                        @if(Auth::user()->isSuperAdmin())
                                            <button type="button" class="btn btn-sm btn-primary px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                                <i class="bi bi-plus-lg me-1"></i> Tambah Kosan Sekarang
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- END TABEL UTAMA -->
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted fs-8">
                Menampilkan {{ $product_kosan->firstItem() ?? 0 }} - {{ $product_kosan->lastItem() ?? 0 }} dari {{ $product_kosan->total() }} kosan
            </small>
            <div>
                {{ $product_kosan->appends(request()->except('kosan_page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- KUMPULAN MODAL -->
        @foreach ($product_kosan as $item)

            <!-- MODAL EDIT KOSAN -->
            <div class="modal fade modalEditKosan" id="modalEdit{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header modal-header-primary text-white">
                            <h5 class="modal-title fs-6 fw-bold" id="modalEditLabel{{ $item->id }}">
                                <i class="bi bi-pencil-square me-2"></i> Edit Kosan: {{ $item->title }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.product.kosan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-3 p-md-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="edit_title_{{ $item->id }}" class="form-label">
                                            Nama Kos / Judul <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="title" id="edit_title_{{ $item->id }}" class="form-control" value="{{ old('title', $item->title) }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="select_wilayah_edit_{{ $item->id }}" class="form-label">
                                            Wilayah (Bali) <span class="text-danger">*</span>
                                        </label>
                                        <select name="wilayah" id="select_wilayah_edit_{{ $item->id }}" class="form-select select-wilayah-edit" data-current-value="{{ $item->wilayah }}" required>
                                            <option value="{{ $item->wilayah }}" selected>{{ $item->wilayah ?? '-- Pilih Wilayah --' }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit_alamat_{{ $item->id }}" class="form-label">Alamat Lengkap</label>
                                        <input type="text" name="alamat" id="edit_alamat_{{ $item->id }}" class="form-control" value="{{ old('alamat', $item->alamat) }}" placeholder="Jl. Contoh No. 123...">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit_google_maps_{{ $item->id }}" class="form-label">Google Maps URL / Embed</label>
                                        <input type="text" name="google_maps" id="edit_google_maps_{{ $item->id }}" class="form-control" value="{{ old('google_maps', $item->google_maps) }}" placeholder="https://maps.app.goo.gl/...">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit_tersedia_{{ $item->id }}" class="form-label">Jumlah Kamar Tersedia</label>
                                        <input type="number" name="tersedia" id="edit_tersedia_{{ $item->id }}" class="form-control" value="{{ old('tersedia', $item->tersedia) }}" min="0" placeholder="0">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit_view_{{ $item->id }}" class="form-label">Jumlah Views Awal</label>
                                        <input type="number" name="view" id="edit_view_{{ $item->id }}" class="form-control" value="{{ old('view', $item->view ?? 0) }}" min="0" placeholder="0">
                                    </div>

                                    <div class="col-12">
                                        <label for="edit_deskripsi_{{ $item->id }}" class="form-label">Deskripsi Lengkap Kosan</label>
                                        <textarea name="deskripsi" id="edit_deskripsi_{{ $item->id }}" class="form-control summernote-edit" rows="3">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label mb-1">Fasilitas Bersama / Properti Kos</label>
                                        <p class="text-muted fs-8 mb-2">Centang fasilitas umum yang tersedia di properti kos ini:</p>
                                        <div class="p-3 rounded-3 bg-body-tertiary border">
                                            @php
                                                $selectedFasilitas = $item->fasilitas ? array_map('trim', explode(',', $item->fasilitas)) : [];
                                                $daftarFasilitas = [
                                                    'WiFi Cepat / Internet', 'Dapur Bersama', 'Parkir Motor Luas', 'Parkir Mobil',
                                                    'CCTV 24 Jam', 'Penjaga Kos / Security', 'Mesin Cuci Bersama', 'Kulkas Bersama',
                                                    'Ruang Tamu / Santai', 'Akses Kunci 24 Jam', 'Dispenser Air Minum', 'Area Jemuran Luas'
                                                ];
                                            @endphp
                                            <div class="row g-2">
                                                @foreach($daftarFasilitas as $fIndex => $fas)
                                                    <div class="col-md-4 col-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas }}" id="edit_fas_{{ $item->id }}_{{ $fIndex }}" {{ in_array($fas, $selectedFasilitas) ? 'checked' : '' }}>
                                                            <label class="form-check-label fs-8" for="edit_fas_{{ $item->id }}_{{ $fIndex }}">
                                                                {{ $fas }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Gambar Denah Kosan (Opsional)</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        @if($item->image)
                                            <div class="mt-2">
                                                <small class="text-muted d-block mb-1">Denah Saat Ini:</small>
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="Denah" class="rounded-2 border" style="max-height: 100px; object-fit: contain;">
                                            </div>
                                        @endif
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

            <!-- MODAL DELETE KOSAN -->
            @if(Auth::user()->isSuperAdmin())
            <div class="modal fade" id="modalDelete{{ $item->id }}" tabindex="-1" aria-labelledby="modalDeleteLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-body p-4 text-center">
                            <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-flex mb-3">
                                <i class="bi bi-trash3-fill fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Hapus Properti Kosan?</h6>
                            <p class="text-muted fs-8 mb-0">
                                Kosan <strong>"{{ $item->title }}"</strong> beserta seluruh kamar di dalamnya akan dihapus secara permanen.
                            </p>
                        </div>
                        <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
                            <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.product.kosan.delete', $item->id) }}" method="POST" class="d-inline">
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
            @endif

            <!-- MODAL IMAGE GALLERY KOSAN -->
            <div class="modal fade" id="modalImageKosan{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="modal-header modal-header-modern text-white p-3 px-4 position-relative">
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded-3 bg-white bg-opacity-10 text-warning d-flex align-items-center justify-content-center">
                                    <i class="bi bi-images fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="modal-title fs-6 fw-bold mb-0 text-white">
                                        Galeri Foto Kosan: {{ $item->title }}
                                    </h5>
                                    <small class="text-white text-opacity-75 fs-8">Kelola dan unggah koleksi foto promosi untuk unit kosan ini</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <!-- Header Info / Stats -->
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-1 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="fw-bold fs-7 mb-0 text-dark">Foto Galeri Tersimpan</h6>
                                    <span class="badge badge-subtle-primary rounded-pill px-2.5">
                                        {{ $item->productImageKosan ? $item->productImageKosan->count() : 0 }} Foto
                                    </span>
                                </div>
                                <span class="fs-8 text-muted">
                                    <i class="bi bi-shield-check text-success me-1"></i>Tampil di detail publik kos
                                </span>
                            </div>

                            <!-- Existing Images Gallery Grid -->
                            @if($item->productImageKosan && $item->productImageKosan->count() > 0)
                                <div class="gallery-grid mb-4">
                                    @foreach($item->productImageKosan as $imgIndex => $img)
                                        <div class="gallery-item-card">
                                            <span class="badge-photo-num">#{{ $imgIndex + 1 }}</span>
                                            <img src="{{ asset('storage/' . $img->image) }}" alt="Foto Kosan {{ $imgIndex + 1 }}" loading="lazy">
                                            <div class="gallery-item-overlay">
                                                <a href="{{ asset('storage/' . $img->image) }}" target="_blank" class="btn btn-sm btn-light py-1 px-2 rounded-circle shadow-sm" title="Lihat Foto Ukuran Penuh">
                                                    <i class="bi bi-arrows-fullscreen fs-8"></i>
                                                </a>
                                                <form action="{{ route('admin.product.kosan.image.delete', $img->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto galeri ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger py-1 px-2 rounded-circle shadow-sm" title="Hapus Foto">
                                                        <i class="bi bi-trash fs-8"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 px-3 mb-4 rounded-3 border bg-light bg-opacity-50">
                                    <div class="d-inline-flex p-3 rounded-circle bg-white text-secondary shadow-sm mb-2">
                                        <i class="bi bi-images fs-2 text-muted"></i>
                                    </div>
                                    <h6 class="fs-7 fw-bold text-dark mb-1">Belum Ada Foto Galeri</h6>
                                    <p class="fs-8 text-muted mb-0" style="max-width: 400px; margin: auto;">
                                        Unggah foto-foto terbaik properti kos Anda (tampak fasad depan, lorong, area santai, dapur umum) untuk menarik calon penyewa.
                                    </p>
                                </div>
                            @endif

                            <!-- Form Upload Foto Baru (Modern Drag & Drop Zone) -->
                            <div class="d-flex align-items-center justify-content-between mb-2 pt-2">
                                <h6 class="fw-bold fs-7 mb-0 text-dark">
                                    <i class="bi bi-cloud-arrow-up-fill text-teal me-1"></i> Unggah Foto Baru
                                </h6>
                                <span class="badge badge-subtle-success fs-8">Maks. 5 MB / File • Multi-Upload</span>
                            </div>

                            <form action="{{ route('admin.product.kosan.image.insert', $item->id) }}" method="POST" enctype="multipart/form-data" class="gallery-upload-form" id="galleryUploadForm{{ $item->id }}">
                                @csrf
                                <div class="gallery-dropzone" onclick="document.getElementById('fileInputKosan{{ $item->id }}').click()">
                                    <div class="dropzone-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </div>
                                    <h6 class="fw-bold fs-7 text-dark mb-1">
                                        Klik untuk Pilih Foto atau Seret & Lepas File ke Sini
                                    </h6>
                                    <p class="fs-8 text-muted mb-0">
                                        Bisa memilih lebih dari 1 file foto sekaligus. Format: <strong>JPG, PNG, WEBP</strong>
                                    </p>
                                    <input type="file" name="images[]" id="fileInputKosan{{ $item->id }}" class="d-none gallery-file-input" multiple accept="image/jpeg,image/png,image/webp,image/jpg" data-target="previewContainerKosan{{ $item->id }}" data-btn="btnUploadKosan{{ $item->id }}">
                                </div>

                                <!-- Live Preview Thumbnails -->
                                <div id="previewContainerKosan{{ $item->id }}" class="preview-grid d-none"></div>

                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2">
                                    <small class="text-muted fs-8" id="fileCountTextKosan{{ $item->id }}">Belum ada file dipilih.</small>
                                    <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-pill shadow-sm" id="btnUploadKosan{{ $item->id }}" disabled>
                                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Foto Sekarang
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer bg-light py-2">
                            <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
</div>
