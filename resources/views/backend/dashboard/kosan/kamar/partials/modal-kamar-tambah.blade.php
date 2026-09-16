<!-- MODAL TAMBAH KAMAR (BULK INSERT) -->
<div class="modal fade" id="modalTambahKamar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('admin.product.kosan.kamar.insert', $product_kosan) }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-door-closed text-secondary me-2"></i> Tambah Unit / Tipe Kamar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                <p class="text-muted fs-7 mb-3">
                    <i class="bi bi-info-circle me-1"></i> Masukkan data tipe/nama kamar, fasilitas, diskon akumulasi, dan deskripsi kamar.
                </p>

                <div id="kamarInputContainer">
                    <div class="kamar-row card border rounded p-3 mb-3" id="kamar-row-0">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label mb-1">Nama / Tipe Kamar <span class="text-danger">*</span></label>
                                <input type="text" name="dataKamar[0][room]" class="form-control" placeholder="Contoh: Kamar Deluxe A" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1">Diskon Akumulatif (%)</label>
                                @if(Auth::user()->isSuperAdmin())
                                    <input type="number" step="0.01" name="dataKamar[0][cumulative_discount]" class="form-control" placeholder="0">
                                @else
                                    <input type="number" step="0.01" name="dataKamar[0][cumulative_discount]" class="form-control bg-light" value="0" readonly disabled title="Hanya diatur langsung oleh Super Admin">
                                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-lock-fill text-warning me-1"></i>Khusus Super Admin</small>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1"><i class="bi bi-eye me-1"></i> Views Count</label>
                                <input type="number" min="0" name="dataKamar[0][views]" class="form-control" placeholder="0" value="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-2">Fasilitas Kamar</label>
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
                                                    <input class="form-check-input" type="checkbox" name="dataKamar[0][fasilitas][]" value="{{ $fasName }}" id="fasilitas_tambah_kamar_0_{{ $fasKey }}">
                                                    <label class="form-check-label fs-7" for="fasilitas_tambah_kamar_0_{{ $fasKey }}">
                                                        {{ $fasName }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-1">Deskripsi Kamar</label>
                                <textarea name="dataKamar[0][description]" class="form-control summernote-edit" rows="2" placeholder="Deskripsi kamar..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-1 btn-add-kamar-row" id="btnAddKamarRow">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Baris Kamar Lain
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Data Kamar
                </button>
            </div>
        </form>
    </div>
</div>
