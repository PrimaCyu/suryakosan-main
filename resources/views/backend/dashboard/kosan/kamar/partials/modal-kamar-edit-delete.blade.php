<!-- MODAL EDIT KAMAR -->
<div class="modal fade" id="modalEditKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('admin.product.kosan.kamar.update', [$product_kosan, $item->id]) }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square text-secondary me-2"></i> Edit Data Kamar: {{ $item->room }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipe / Nama Kamar <span class="text-danger">*</span></label>
                        <input type="text" name="room" class="form-control" value="{{ $item->room }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Diskon Akumulatif (%)</label>
                        @if(Auth::user()->isSuperAdmin())
                            <input type="number" step="0.01" name="cumulative_discount" class="form-control" value="{{ $item->cumulative_discount }}">
                        @else
                            <input type="number" step="0.01" class="form-control bg-light" value="{{ $item->cumulative_discount }}" readonly disabled>
                            <small class="text-muted d-block mt-1"><i class="bi bi-lock-fill text-warning me-1"></i>Hanya diatur langsung oleh Super Admin</small>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><i class="bi bi-eye me-1"></i> Views Count</label>
                        <input type="number" min="0" name="views" class="form-control" value="{{ $item->views ?? 0 }}">
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
                                $currentFasKamar = array_map('trim', explode(',', $item->fasilitas ?? ''));
                            @endphp
                            <div class="row g-2">
                                @foreach($listFasilitasKamar as $fasKey => $fasName)
                                    <div class="col-md-4 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fasName }}" id="fas_edit_{{ $item->id }}_{{ $fasKey }}" {{ in_array($fasName, $currentFasKamar) ? 'checked' : '' }}>
                                            <label class="form-check-label fs-7" for="fas_edit_{{ $item->id }}_{{ $fasKey }}">
                                                {{ $fasName }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi Kamar</label>
                        <textarea name="description" class="form-control summernote-edit" rows="3">{{ $item->description }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">Tambah Foto Kamar</label>
                            <span class="badge bg-primary-subtle text-primary fs-8">Bisa Multi-Foto</span>
                        </div>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <div class="form-text fs-8 text-muted">Pilih satu atau lebih foto baru untuk ditambahkan ke koleksi foto kamar ini.</div>
                        @if($item->productKamarImageKosan && $item->productKamarImageKosan->count() > 0)
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">Foto Kamar Saat Ini ({{ $item->productKamarImageKosan->count() }} foto):</small>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($item->productKamarImageKosan->take(4) as $photo)
                                        <img src="{{ asset('storage/' . $photo->image) }}" alt="Foto Kamar" class="rounded-2 border" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endforeach
                                </div>
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

<!-- MODAL DELETE KAMAR -->
<div class="modal fade" id="modalDeleteKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4">
                <h6 class="fw-bold text-dark mb-2">Hapus Kamar?</h6>
                <p class="text-muted fs-8 mb-0">Apakah Anda yakin ingin menghapus <strong>"{{ $item->room }}"</strong> beserta seluruh galeri & tarif harganya?</p>
            </div>
            <div class="modal-footer d-flex justify-content-end border-top-0 pt-0 pb-3">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.product.kosan.kamar.delete', [$product_kosan, $item->id]) }}" method="POST" class="d-inline">
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
