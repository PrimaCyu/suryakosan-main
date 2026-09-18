<!-- MODAL TAMBAH KAMAR (UNIFIED SINGLE + BULK TABS) -->
<div class="modal fade" id="modalTambahKamar" tabindex="-1" aria-labelledby="modalTambahKamarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header pb-2 border-bottom">
                <div>
                    <h5 class="modal-title fw-bold fs-6" id="modalTambahKamarLabel">
                        <i class="bi bi-door-open-fill text-primary me-2"></i> Tambah Kamar Baru: {{ $kosan->title }}
                    </h5>
                    <p class="text-muted fs-8 mb-0">Lengkapi data unit kamar, tarif sewa, dan foto utama secara langsung.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <!-- Tab Switcher: Form Terpadu vs Input Massal -->
            <div class="px-4 pt-3 bg-body-tertiary border-bottom">
                <ul class="nav nav-pills nav-fill gap-2" id="kamarAddTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-2 fs-7 fw-semibold" id="tab-single-kamar" data-bs-toggle="pill" data-bs-target="#panel-single-kamar" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-lightning-charge-fill me-1 text-warning"></i> Form Terpadu (Rekomendasi)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-2 fs-7 fw-semibold text-muted" id="tab-bulk-kamar" data-bs-toggle="pill" data-bs-target="#panel-bulk-kamar" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-collection-fill me-1"></i> Input Cepat Massal (Batch)
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
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 fs-8">1</span>
                                    <h6 class="fw-bold mb-0 fs-7">Identitas & Nama Kamar</h6>
                                </div>
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

                            <!-- Bagian 2: Tarif Sewa Langsung (Solusi Alur Fragmented) -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fs-8">2</span>
                                        <h6 class="fw-bold mb-0 fs-7">Tarif Sewa (Langsung Aktif)</h6>
                                    </div>
                                    <span class="text-success fs-8 fw-medium"><i class="bi bi-check2-all me-1"></i>Otomatis masuk sistem</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card border border-success-subtle bg-success-subtle bg-opacity-10 p-3 h-100 rounded-3">
                                            <label class="form-label fs-8 fw-bold mb-1 text-success">
                                                <i class="bi bi-calendar-month me-1"></i>Tarif Sewa Bulanan (Rp)
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-success-subtle text-muted fs-8">Rp</span>
                                                <input type="number" name="price_bulan" class="form-control border-success-subtle fw-semibold" placeholder="Contoh: 1500000" min="0" step="50000">
                                            </div>
                                            <div class="form-text fs-8 text-muted mt-1">Tarif dasar per bulan yang paling umum dicari penyewa.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border p-3 h-100 rounded-3">
                                            <label class="form-label fs-8 fw-semibold mb-1">
                                                <i class="bi bi-calendar-event me-1"></i>Tarif Sewa Tahunan (Rp) <span class="badge badge-subtle-secondary fs-8 ms-1">Opsional</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent text-muted fs-8">Rp</span>
                                                <input type="number" name="price_tahun" class="form-control" placeholder="Contoh: 16500000" min="0" step="100000">
                                            </div>
                                            <div class="form-text fs-8 text-muted mt-1">Dapat dikosongkan jika belum tersedia paket tahunan.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3 opacity-25">

                            <!-- Bagian 3: Foto Utama Kamar (Upload Instan dengan Preview) -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1 fs-8">3</span>
                                        <h6 class="fw-bold mb-0 fs-7">Foto Utama Kamar</h6>
                                    </div>
                                    <span class="text-muted fs-8">JPG, PNG, WEBP (Maks 5MB)</span>
                                </div>
                                <div class="p-3 border border-2 border-dashed rounded-3 text-center position-relative bg-body-tertiary" id="singleRoomDropzone">
                                    <input type="file" name="image" id="singleRoomImageInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*">
                                    <div id="singleRoomPlaceholder">
                                        <i class="bi bi-camera display-6 text-muted mb-2 d-block"></i>
                                        <p class="fs-7 fw-semibold mb-1">Klik atau seret foto utama kamar ke sini</p>
                                        <p class="text-muted fs-8 mb-0">Foto ini akan menjadi thumbnail utama unit di katalog dan pencarian.</p>
                                    </div>
                                    <div id="singleRoomPreviewWrap" class="d-none mt-2">
                                        <img id="singleRoomPreviewImg" src="" alt="Preview Foto Kamar" class="img-fluid rounded-3 shadow-sm" style="max-height: 180px; object-fit: cover;">
                                        <div class="mt-2 d-flex justify-content-center align-items-center gap-2">
                                            <span class="badge bg-success fs-8" id="singleRoomFileName"></span>
                                            <button type="button" class="btn btn-xs btn-outline-danger" id="btnCancelSingleImg">
                                                <i class="bi bi-x-circle me-1"></i> Ganti Foto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3 opacity-25">

                            <!-- Bagian 4: Fasilitas Kamar -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 fs-8">4</span>
                                    <h6 class="fw-bold mb-0 fs-7">Fasilitas Dalam Kamar</h6>
                                </div>
                                <div class="p-3 rounded-3 bg-body-tertiary border">
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
                                                    <label class="form-check-label fs-8 user-select-none" for="fas_single_{{ $fasKey }}">
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
                                <label class="form-label fs-8 fw-semibold mb-1">Deskripsi / Spesifikasi Tambahan</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Contoh: Ukuran 3x4 meter, listrik token mandiri 900 watt, view menghadap taman..."></textarea>
                            </div>
                        </div>

                        <div class="modal-footer bg-body-tertiary">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Unit Kamar Terpadu
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PANEL 2: LEGACY BULK BATCH KAMAR -->
                <div class="tab-pane fade" id="panel-bulk-kamar" role="tabpanel" tabindex="0">
                    <form action="{{ route('admin.product.kosan.kamar.insert', $product_kosan) }}" method="POST" class="modal-content border-0">
                        @csrf
                        <div class="modal-body p-4" style="max-height: 65vh; overflow-y: auto;">
                            <div class="alert alert-info py-2 px-3 fs-8 mb-3">
                                <i class="bi bi-info-circle me-1"></i> Gunakan tab ini jika Anda ingin memasukkan banyak unit kamar sekaligus. Foto dan harga dapat diisi setelahnya melalui tombol aksi tabel.
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
                                            <div class="p-2 rounded bg-body-tertiary border">
                                                <div class="row g-2">
                                                    @foreach($listFasilitasKamar as $fasKey => $fasName)
                                                        <div class="col-md-4 col-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="dataKamar[0][fasilitas][]" value="{{ $fasName }}" id="fasilitas_batch_0_{{ $fasKey }}">
                                                                <label class="form-check-label fs-8" for="fasilitas_batch_0_{{ $fasKey }}">
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
                                            <textarea name="dataKamar[0][description]" class="form-control" rows="2" placeholder="Deskripsi kamar..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-secondary btn-sm w-100 mt-1 btn-add-kamar-row" id="btnAddKamarRow">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Baris Unit Lain
                            </button>
                        </div>

                        <div class="modal-footer bg-body-tertiary">
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
