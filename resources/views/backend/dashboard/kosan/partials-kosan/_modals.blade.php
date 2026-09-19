<!-- ========================================== -->
<!-- MODAL TAMBAH KOSAN (MODERN & TERPADU)       -->
<!-- ========================================== -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('admin.product.kosan.insert') }}" method="POST" enctype="multipart/form-data" id="formTambahKosan" class="modal-content border-0 shadow-lg">
            @csrf
            <!-- Modal Header -->
            <div class="modal-header border-bottom pb-2">
                <div>
                    <h5 class="modal-title fs-6 fw-bold" id="modalTambahLabel">
                        <i class="bi bi-building-add text-primary me-2"></i> Tambah Properti Kos-Kosan Baru
                    </h5>
                    <p class="text-muted fs-8 mb-0">Daftarkan properti kosan baru, tentukan wilayah, fasilitas, dan unggah foto-foto properti.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                
                <!-- Bagian 1: Identitas & Lokasi Properti -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 fs-8">1</span>
                        <h6 class="fw-bold mb-0 fs-7">Identitas & Wilayah Properti</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label for="title" class="form-label fs-8 fw-semibold mb-1">
                                Nama Kos / Judul Properti <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   placeholder="Contoh: Kos Exclusive Renon Denpasar"
                                   value="{{ old('title') }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-5">
                            <label for="select_wilayah_tambah" class="form-label fs-8 fw-semibold mb-1">
                                Wilayah (Bali) <span class="text-danger">*</span>
                            </label>
                            <select name="wilayah" id="select_wilayah_tambah" class="form-select @error('wilayah') is-invalid @enderror" required>
                                <option value="" disabled {{ old('wilayah') ? '' : 'selected' }}>-- Pilih Kab / Kota --</option>
                                @php
                                    $baliRegencies = ['Denpasar', 'Badung', 'Gianyar', 'Tabanan', 'Buleleng', 'Karangasem', 'Klungkung', 'Bangli', 'Jembrana'];
                                @endphp
                                @foreach($baliRegencies as $reg)
                                    <option value="{{ $reg }}" {{ old('wilayah') == $reg ? 'selected' : '' }}>{{ $reg }}</option>
                                @endforeach
                            </select>
                            @error('wilayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="gmaps" class="form-label fs-8 fw-semibold mb-1">
                                Link Lokasi Google Maps <span class="badge badge-subtle-secondary fs-8 ms-1">Opsional</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-muted"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                                <input type="text"
                                       name="gmaps"
                                       id="gmaps"
                                       class="form-control @error('gmaps') is-invalid @enderror"
                                       placeholder="https://maps.google.com/..."
                                       value="{{ old('gmaps') }}">
                            </div>
                            <div class="form-text fs-8 text-muted">Salin tautan bagikan dari Google Maps untuk memudahkan calon penyewa menemukan lokasi.</div>
                            @error('gmaps')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-3 opacity-25">

                <!-- Bagian 2: Kapasitas Awal & Foto-Foto Kosan (Multi-Image) -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fs-8">2</span>
                        <h6 class="fw-bold mb-0 fs-7">Kapasitas & Koleksi Foto Properti</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="card border p-3 h-100 rounded-3 bg-body-tertiary">
                                <label for="tersedia" class="form-label fs-8 fw-semibold mb-1">
                                    Kapasitas Unit Awal <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           name="tersedia"
                                           id="tersedia"
                                           class="form-control @error('tersedia') is-invalid @enderror"
                                           placeholder="Jumlah unit"
                                           value="{{ old('tersedia', 1) }}"
                                           min="0"
                                           required>
                                    <span class="input-group-text bg-transparent text-muted fs-8">Unit</span>
                                </div>
                                <div class="form-text fs-8 text-muted mt-2">
                                    <i class="bi bi-info-circle me-1 text-primary"></i>Kapasitas awal. Jumlah kamar kosong akan otomatis tersinkron saat Anda mendaftarkan unit kamar.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card border p-3 h-100 rounded-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fs-8 fw-semibold mb-0">
                                        Foto Properti Kos <span class="badge bg-primary-subtle text-primary fs-8 ms-1">Multi-Foto</span>
                                    </label>
                                    <span class="fs-8 text-muted">Bisa pilih banyak foto</span>
                                </div>
                                <div class="multi-image-dropzone" id="kosanDropzone">
                                    <input type="file" name="images[]" id="kosanImageInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*" multiple>
                                    <div id="kosanPlaceholder">
                                        <i class="bi bi-images display-6 text-primary mb-1 d-block opacity-75"></i>
                                        <p class="fs-8 fw-semibold mb-0">Klik atau seret foto-foto kosan ke sini</p>
                                        <p class="text-muted fs-8 mb-0">Pilih 1 atau lebih foto (JPG, PNG, WEBP, Maks 5MB/foto)</p>
                                    </div>
                                    <div id="kosanPreviewWrap" class="d-none mt-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="multi-image-badge-count" id="kosanBadgeCount">
                                                <i class="bi bi-check2-circle text-success"></i> <span id="kosanFileName">0 Foto Dipilih</span>
                                            </span>
                                            <button type="button" class="btn btn-xs btn-outline-danger" id="btnCancelKosanImg">
                                                <i class="bi bi-x-circle me-1"></i> Reset / Pilih Ulang
                                            </button>
                                        </div>
                                        <div class="multi-image-grid" id="kosanImagesGrid"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3 opacity-25">

                <!-- Bagian 3: Fasilitas Kosan -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 fs-8">3</span>
                        <h6 class="fw-bold mb-0 fs-7">Fasilitas Bersama Properti</h6>
                    </div>
                    <div class="p-3 rounded-3 bg-body-tertiary border">
                        @php
                            $listFasilitas = [
                                'Wi-Fi / Internet',
                                'Parkir Mobil',
                                'Parkir Motor',
                                'Dapur Bersama',
                                'CCTV 24 Jam',
                                'Keamanan / Satpam',
                                'Ruang Tamu Bersama',
                                'Ruang Jemur',
                                'Mesin Cuci Bersama',
                                'Kulkas Bersama',
                                'Air Minum / Dispenser',
                                'Penjaga Kos',
                                'Listrik Gratis / Included',
                                'Bebas Jam Malam',
                                'Akses Kartu / Smart Lock',
                                'Balkon / Rooftop',
                                'Musholla',
                                'Gazebo / Area Santai'
                            ];
                        @endphp
                        <div class="row g-2">
                            @foreach($listFasilitas as $fasKey => $fasName)
                                <div class="col-md-4 col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fasName }}" id="fasilitas_tambah_{{ $fasKey }}">
                                        <label class="form-check-label fs-8 user-select-none" for="fasilitas_tambah_{{ $fasKey }}">
                                            {{ $fasName }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('fasilitas')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Bagian 4: Deskripsi Kosan -->
                <div class="mb-2">
                    <label for="summernote_description" class="form-label fs-8 fw-semibold mb-1">
                        Deskripsi Lengkap Properti Kos <span class="badge badge-subtle-secondary fs-8 ms-1">Opsional</span>
                    </label>
                    <textarea name="description"
                              id="summernote_description"
                              class="form-control"
                              rows="3"
                              placeholder="Tuliskan gambaran umum suasana kosan, aturan khusus, atau akses strategis ke kampus/perkantoran...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <!-- Modal Footer (Selalu Terlihat & Sticky di Bawah) -->
            <div class="modal-footer bg-body-tertiary">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Properti Kos
                </button>
            </div>
        </form>
    </div>
</div>
