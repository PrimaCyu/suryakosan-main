<!-- ========================================== -->
<!-- MODAL TAMBAH KOSAN                         -->
<!-- ========================================== -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header modal-header-primary text-white">
                <h5 class="modal-title fs-6 fw-bold" id="modalTambahLabel">
                    <i class="bi bi-building-fill-add me-2"></i> Tambah Properti Kos-Kosan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('admin.product.kosan.insert') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-3 p-md-4">
                    <div class="row g-3">

                        <!-- Title & Slug Row -->
                        <div class="col-md-6">
                            <label for="title" class="form-label">
                                Nama Kos / Judul <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   placeholder="Contoh: Kos Exclusive Renon Denpasar"
                                   value="{{ old('title') }}"
                                   onkeyup="generateSlug()"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Wilayah (Dropdown Filter Bali) -->
                        <div class="col-md-6">
                            <label for="select_wilayah_tambah" class="form-label">
                                Wilayah (Bali) <span class="text-danger">*</span>
                            </label>
                            <select name="wilayah" id="select_wilayah_tambah" class="form-select @error('wilayah') is-invalid @enderror" required>
                                <option value="" selected disabled>Loading data wilayah Bali...</option>
                            </select>
                            @error('wilayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Kamar Tersedia -->
                        <div class="col-md-6">
                            <label for="tersedia" class="form-label">
                                Kamar Tersedia (Unit) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   name="tersedia"
                                   id="tersedia"
                                   class="form-control @error('tersedia') is-invalid @enderror"
                                   placeholder="Jumlah kamar kosong, misal: 5"
                                   value="{{ old('tersedia', 1) }}"
                                   min="0"
                                   required>
                            @error('tersedia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input View -->
                        <div class="col-md-6">
                            <label for="view" class="form-label">
                                Inisial View Count
                            </label>
                            <input type="number"
                                   name="view"
                                   id="view"
                                   class="form-control @error('view') is-invalid @enderror"
                                   value="{{ old('view', 0) }}"
                                   min="0"
                                   required>
                            @error('view')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Link Google Maps -->
                        <div class="col-md-12">
                            <label for="gmaps" class="form-label">
                                Link Google Maps (Share Link / Embed)
                            </label>
                            <input type="text"
                                   name="gmaps"
                                   id="gmaps"
                                   class="form-control @error('gmaps') is-invalid @enderror"
                                   placeholder="https://maps.google.com/..."
                                   value="{{ old('gmaps') }}">
                            @error('gmaps')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fasilitas Kosan -->
                        <div class="col-12">
                            <label class="form-label mb-1">
                                Fasilitas Properti Kos <span class="text-danger">*</span>
                            </label>
                            <p class="text-muted fs-8 mb-2">Pilih fasilitas bersama yang tersedia di kosan ini:</p>
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
                                                <label class="form-check-label fs-8" for="fasilitas_tambah_{{ $fasKey }}">
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

                        <!-- Description -->
                        <div class="col-md-12">
                            <label for="summernote_description" class="form-label">
                                Deskripsi Properti Kos <span class="text-danger">*</span>
                            </label>
                            <textarea name="description"
                                      id="summernote_description"
                                      class="form-control summernote-init @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Properti Kos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
