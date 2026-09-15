<!-- MODAL EDIT KAMAR -->
<div class="modal fade" id="modalEditKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-primary text-white">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="bi bi-pencil-square me-2"></i> Edit Data Kamar: {{ $item->room }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.product.kosan.kamar.update', [$product_kosan, $item->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
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

<!-- MODAL DELETE KAMAR -->
<div class="modal fade" id="modalDeleteKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-4">
                <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-flex mb-3">
                    <i class="bi bi-trash3-fill fs-2"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Hapus Kamar?</h6>
                <p class="text-muted fs-7 mb-0">Apakah Anda yakin ingin menghapus <strong>"{{ $item->room }}"</strong> beserta seluruh galeri & harganya?</p>
            </div>
            <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
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
