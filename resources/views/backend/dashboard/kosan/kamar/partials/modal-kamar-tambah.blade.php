<!-- MODAL TAMBAH KAMAR (UNIFIED SINGLE + BULK TABS) -->
<div class="modal fade" id="modalTambahKamar" tabindex="-1" aria-labelledby="modalTambahKamarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom py-3">
                <div>
                    <h5 class="modal-title fw-bold fs-6" id="modalTambahKamarLabel">
                        <i class="bi bi-door-open text-primary me-2"></i>Tambah Kamar: {{ $kosan->title }}
                    </h5>
                    <p class="text-muted fs-8 mb-0">Lengkapi informasi tipe unit, tarif sewa, foto, dan fasilitas kamar.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <!-- Tab Switcher: Form Terpadu vs Input Massal -->
            <div class="px-4 pt-3 bg-light border-bottom">
                <ul class="nav nav-pills nav-fill gap-2" id="kamarAddTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-2 fs-7 fw-semibold" id="tab-single-kamar" data-bs-toggle="pill" data-bs-target="#panel-single-kamar" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-door-open me-1 text-primary"></i> Input Satuan (Lengkap)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-2 fs-7 fw-semibold text-muted" id="tab-bulk-kamar" data-bs-toggle="pill" data-bs-target="#panel-bulk-kamar" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-collection me-1"></i> Input Massal (Banyak Unit)
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <!-- PANEL 1: UNIFIED SINGLE ROOM (ROOM + PRICES + PHOTO) -->
                <div class="tab-pane fade show active" id="panel-single-kamar" role="tabpanel" tabindex="0">
                    <form action="{{ route('admin.product.kosan.kamar.insert', $product_kosan) }}" method="POST" enctype="multipart/form-data" id="formSingleKamar">
                        @csrf
                        <div class="modal-body p-4" style="max-height: 65vh; overflow-y: auto;">
                            <!-- Bagian 1: Identitas Unit -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 fs-7 text-dark">
                                    <i class="bi bi-info-circle text-primary me-1.5"></i> Identitas & Nama Kamar
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label fs-8 fw-semibold mb-1">Nama / Tipe / Nomor Kamar <span class="text-danger">*</span></label>
                                        <input type="text" name="room" class="form-control" placeholder="Contoh: Kamar 101 - Lantai 1 (Tipe Deluxe)" required>
                                        <div class="form-text fs-8 text-muted">Bisa berupa nomor kamar atau nama tipe kamar.</div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label fs-8 fw-semibold mb-1">Diskon Akumulatif (%)</label>
                                        @if(Auth::user()->isSuperAdmin())
                                            <input type="number" step="0.01" min="0" max="100" name="cumulative_discount" class="form-control" placeholder="0">
                                            <div class="form-text fs-8 text-muted">Potongan harga promo (%)</div>
                                        @else
                                            <input type="number" step="0.01" name="cumulative_discount" class="form-control bg-body-secondary" value="0" readonly disabled>
                                            <div class="form-text fs-8 text-warning"><i class="bi bi-lock-fill me-1"></i>Khusus Super Admin</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3 opacity-25">

                            <!-- Bagian 2: Tarif Sewa Langsung -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 fs-7 text-dark">
                                    <i class="bi bi-cash-stack text-success me-1.5"></i> Tarif Sewa Kamar
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold mb-1">Tarif Bulanan (Rp) <span class="text-muted fw-normal">(Utama)</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted fs-8">Rp</span>
                                            <input type="number" name="price_bulan" class="form-control fw-semibold" placeholder="Contoh: 1500000" min="0" step="50000">
                                        </div>
                                        <div class="form-text fs-8 text-muted">Tarif dasar per bulan yang paling umum dicari.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold mb-1">Tarif Tahunan (Rp) <span class="text-muted fw-normal">(Opsional)</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted fs-8">Rp</span>
                                            <input type="number" name="price_tahun" class="form-control" placeholder="Contoh: 16500000" min="0" step="100000">
                                        </div>
                                        <div class="form-text fs-8 text-muted">Dapat dikosongkan jika belum ada paket tahunan.</div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3 opacity-25">

                            <!-- Bagian 3: Foto Kamar (Multi-Image Upload & Preview) -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="fw-bold mb-0 fs-7 text-dark">
                                        <i class="bi bi-images text-primary me-1.5"></i> Foto Kamar
                                    </h6>
                                    <span class="text-muted fs-8">JPG, PNG, WEBP (Bisa pilih banyak)</span>
                                </div>
                                <div class="multi-image-dropzone" id="singleRoomDropzone">
                                    <input type="file" name="images[]" id="singleRoomImageInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*" multiple>
                                    <div id="singleRoomPlaceholder" class="text-center py-3">
                                        <i class="bi bi-cloud-arrow-up text-secondary fs-3 mb-1 d-block opacity-75"></i>
                                        <p class="fs-8 fw-semibold text-dark mb-0">Klik atau seret foto-foto unit kamar ke sini</p>
                                        <p class="text-muted fs-8 mb-0">Pilih 1 atau lebih foto sudut ruangan, kamar mandi, kasur, dll.</p>
                                    </div>
                                    <div id="singleRoomPreviewWrap" class="d-none mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="multi-image-badge-count" id="singleRoomBadgeCount">
                                                <i class="bi bi-check2-circle text-success"></i> <span id="singleRoomFileName">0 Foto Dipilih</span>
                                            </span>
                                            <button type="button" class="btn btn-xs btn-outline-danger" id="btnCancelSingleImg">
                                                <i class="bi bi-x-circle me-1"></i> Reset Foto
                                            </button>
                                        </div>
                                        <div class="multi-image-grid" id="singleRoomImagesGrid"></div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3 opacity-25">

                            <!-- Bagian 4: Fasilitas Kamar -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 fs-7 text-dark">
                                    <i class="bi bi-check2-square text-info me-1.5"></i> Fasilitas Dalam Kamar
                                </h6>
                                <div class="p-3 rounded-2 bg-light border">
                                    @php
                                        $listFasilitasKamar = [
                                            'AC',
                                            'Kamar Mandi Dalam',
                                            'Water Heater / Air Hangat',
                                            'Kasur Springbed',
                                            'Lemari Pakaian',
                                            'Meja & Kursi Belajar',
                                            'TV / Smart TV',
                                            'Wastafel',
                                            'Balkon Kamar',
                                            'Jendela / Ventilasi Bagus',
                                            'Kipas Angin',
                                            'Kulkas Mini'
                                        ];
                                    @endphp
                                    <div class="row g-2">
                                        @foreach($listFasilitasKamar as $fasKey => $fasName)
                                            <div class="col-md-4 col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fasName }}" id="fas_single_{{ $fasKey }}">
                                                    <label class="form-check-label fs-8 user-select-none text-secondary" for="fas_single_{{ $fasKey }}">
                                                        {{ $fasName }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian 5: Deskripsi Spesifikasi Kamar -->
                            <div class="mb-2">
                                <label class="form-label fs-8 fw-semibold mb-1">Deskripsi / Catatan Tambahan <span class="text-muted fw-normal">(Opsional)</span></label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Contoh: Ukuran 3x4 meter, listrik token mandiri 900 watt, view menghadap taman..."></textarea>
                            </div>
                        </div>

                        <div class="modal-footer bg-light border-top py-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Simpan Unit Kamar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PANEL 2: BULK BATCH KAMAR -->
                <div class="tab-pane fade" id="panel-bulk-kamar" role="tabpanel" tabindex="0">
                    <form action="{{ route('admin.product.kosan.kamar.insert', $product_kosan) }}" method="POST" class="modal-content border-0">
                        @csrf
                        <div class="modal-body p-4" style="max-height: 65vh; overflow-y: auto;">
                            <div class="alert alert-light border py-2 px-3 fs-8 mb-3 text-muted">
                                <i class="bi bi-info-circle me-1 text-primary"></i> Gunakan tab ini jika Anda ingin mendaftarkan banyak unit kamar sekaligus. Foto dan rincian harga dapat dilengkapi setelahnya.
                            </div>

                            <div id="kamarInputContainer">
                                <div class="kamar-row card border rounded p-3 mb-3" id="kamar-row-0">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6">
                                            <label class="form-label fs-8 fw-semibold mb-1">Nama / Tipe Kamar <span class="text-danger">*</span></label>
                                            <input type="text" name="dataKamar[0][room]" class="form-control" placeholder="Contoh: Kamar Deluxe A" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-8 fw-semibold mb-1">Diskon Akumulatif (%)</label>
                                            @if(Auth::user()->isSuperAdmin())
                                                <input type="number" step="0.01" name="dataKamar[0][cumulative_discount]" class="form-control" placeholder="0">
                                            @else
                                                <input type="number" step="0.01" name="dataKamar[0][cumulative_discount]" class="form-control bg-body-secondary" value="0" readonly disabled>
                                                <small class="text-muted d-block mt-1 fs-8"><i class="bi bi-lock-fill text-warning me-1"></i>Khusus Super Admin</small>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fs-8 fw-semibold mb-1">Fasilitas Kamar</label>
                                            <div class="p-2 rounded bg-light border">
                                                <div class="row g-2">
                                                    @foreach($listFasilitasKamar as $fasKey => $fasName)
                                                        <div class="col-md-4 col-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="dataKamar[0][fasilitas][]" value="{{ $fasName }}" id="fasilitas_batch_0_{{ $fasKey }}">
                                                                <label class="form-check-label fs-8 text-secondary" for="fasilitas_batch_0_{{ $fasKey }}">
                                                                    {{ $fasName }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fs-8 fw-semibold mb-1">Deskripsi Singkat</label>
                                            <textarea name="dataKamar[0][description]" class="form-control" rows="2" placeholder="Deskripsi singkat unit kamar..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-secondary btn-sm w-100 mt-1 btn-add-kamar-row" id="btnAddKamarRow">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Baris Unit Lain
                            </button>
                        </div>

                        <div class="modal-footer bg-light border-top py-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Data Massal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
