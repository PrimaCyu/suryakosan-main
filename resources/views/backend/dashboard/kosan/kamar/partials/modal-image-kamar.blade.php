<!-- MODAL IMAGE KAMAR -->
<div class="modal fade" id="modalImageKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-modern text-white">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="bi bi-images me-2 text-warning"></i> Galeri Foto Kamar: {{ $item->room }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Daftar Foto Kamar</h6>
                        <small class="text-muted">Total {{ count($item->productKamarImageKosan) }} foto</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalAddImageKamar{{ $item->id }}">
                        <i class="bi bi-plus-lg me-1"></i> Upload Foto Baru
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th style="width: 150px;">Tipe Kamar</th>
                                <th>Preview Foto</th>
                                <th style="width: 120px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($item->productKamarImageKosan as $imgIndex => $img)
                                <tr>
                                    <td class="text-center fw-semibold text-muted">{{ $imgIndex + 1 }}</td>
                                    <td class="fw-semibold text-dark">{{ $img->productKamarKosan->room }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $img->image) }}" alt="Foto Kamar" class="rounded-3 border shadow-sm" style="height: 60px; width: 90px; object-fit: cover;">
                                        <small class="d-block text-muted mt-1 fs-7">{{ $img->image }}</small>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeleteImageKamar{{ $img->id }}" title="Hapus Foto">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-image-alt display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada foto galeri untuk kamar ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ADD IMAGE KAMAR (MULTIPLE FILE UPLOAD) -->
<div class="modal fade" id="modalAddImageKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-success text-white">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Foto Kamar: {{ $item->room }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.product.kosan.kamar.image.insert',['product_kosan' => $product_kosan, 'product_kamar_kosan' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted fs-7 mb-3">
                        <i class="bi bi-info-circle me-1"></i> Pilih satu atau beberapa file gambar sekaligus untuk kamar ini.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Pilih Foto Kamar <span class="text-danger">*</span></label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple required>
                        <small class="text-muted fs-7 mt-2 d-block">Pilih banyak foto sekaligus (Bulk Upload). Format: JPG, PNG, WEBP.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalImageKamar{{ $item->id }}">Kembali</button>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="bi bi-upload me-1"></i> Upload Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DELETE IMAGE KAMAR FOR EACH IMAGE -->
@foreach ($item->productKamarImageKosan as $img)
    <div class="modal fade" id="modalDeleteImageKamar{{ $img->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-flex mb-3">
                        <i class="bi bi-trash3-fill fs-2"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Hapus Foto?</h6>
                    <p class="text-muted fs-7 mb-0">Apakah Anda yakin ingin menghapus foto kamar ini?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.product.kosan.kamar.image.delete', ['product_kosan' => $product_kosan, 'product_kamar_kosan' => $item->id, 'product_kamar_image_kosan' => $img->id]) }}" method="POST" class="d-inline">
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
