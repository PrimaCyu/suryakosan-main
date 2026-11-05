<!-- MODAL IMAGE KAMAR -->
<div class="modal fade" id="modalImageKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="modal-title mb-0">
                        <i class="bi bi-images text-secondary me-2"></i> Galeri Foto Kamar: {{ $item->room }}
                    </h5>
                    <p class="text-muted fs-8 mb-0">Kelola dan unggah foto unit tipe kamar ini</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Header Info / Stats -->
                <div class="d-flex align-items-center justify-content-between mb-3 pb-1 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="fw-bold fs-7 mb-0 text-dark">Foto Kamar Tersimpan</h6>
                        <span class="badge badge-subtle-primary rounded-pill px-2.5">
                            {{ count($item->productKamarImageKosan) }} Foto
                        </span>
                    </div>
                    <span class="fs-8 text-muted">
                        <i class="bi bi-shield-check text-success me-1"></i>Tampil di detail booking kamar
                    </span>
                </div>

                <!-- Existing Images Gallery Grid -->
                @if(count($item->productKamarImageKosan) > 0)
                    <div class="gallery-grid mb-4">
                        @foreach($item->productKamarImageKosan as $imgIndex => $img)
                            <div class="gallery-item-card">
                                <span class="badge-photo-num">#{{ $imgIndex + 1 }}</span>
                                <img src="{{ asset('storage/' . $img->image) }}" alt="Foto Kamar {{ $imgIndex + 1 }}" loading="lazy">
                                <div class="gallery-item-overlay">
                                    <a href="{{ asset('storage/' . $img->image) }}" target="_blank" class="btn btn-sm btn-light py-1 px-2 rounded-circle shadow-sm" title="Lihat Foto Ukuran Penuh">
                                        <i class="bi bi-arrows-fullscreen fs-8"></i>
                                    </a>
                                    <form action="{{ route('admin.product.kosan.kamar.image.delete', ['product_kosan' => $product_kosan, 'product_kamar_kosan' => $item->id, 'product_kamar_image_kosan' => $img->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto kamar ini?')">
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
                            <i class="bi bi-door-closed fs-2 text-muted"></i>
                        </div>
                        <h6 class="fs-7 fw-bold text-dark mb-1">Belum Ada Foto Kamar</h6>
                        <p class="fs-8 text-muted mb-0" style="max-width: 380px; margin: auto;">
                            Unggah foto sudut kamar (kasur, meja, kamar mandi dalam, ventilasi/jendela) agar calon penyewa tertarik.
                        </p>
                    </div>
                @endif

                <!-- Form Upload Foto Baru (Dropzone) -->
                <div class="d-flex align-items-center justify-content-between mb-2 pt-2">
                    <h6 class="fw-bold fs-7 mb-0 text-dark">
                        <i class="bi bi-cloud-arrow-up-fill text-teal me-1"></i> Unggah Foto Kamar Baru
                    </h6>
                    <span class="badge badge-subtle-success fs-8">Maks. 5 MB / File • Multi-Upload</span>
                </div>

                <form action="{{ route('admin.product.kosan.kamar.image.insert',['product_kosan' => $product_kosan, 'product_kamar_kosan' => $item->id]) }}" method="POST" enctype="multipart/form-data" class="gallery-upload-form" id="galleryUploadFormKamar{{ $item->id }}">
                    @csrf
                    <div class="gallery-dropzone" onclick="document.getElementById('fileInputKamar{{ $item->id }}').click()">
                        <div class="dropzone-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <h6 class="fw-bold fs-7 text-dark mb-1">
                            Klik untuk Pilih Foto Kamar atau Seret File ke Sini
                        </h6>
                        <p class="fs-8 text-muted mb-0">
                            Bisa memilih lebih dari 1 file sekaligus. Format: <strong>JPG, PNG, WEBP</strong>
                        </p>
                        <input type="file" name="images[]" id="fileInputKamar{{ $item->id }}" class="d-none gallery-file-input" multiple accept="image/jpeg,image/png,image/webp,image/jpg" data-target="previewContainerKamar{{ $item->id }}" data-btn="btnUploadKamar{{ $item->id }}">
                    </div>

                    <!-- Live Preview Thumbnails -->
                    <div id="previewContainerKamar{{ $item->id }}" class="preview-grid d-none"></div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2">
                        <small class="text-muted fs-8" id="fileCountTextKamar{{ $item->id }}">Belum ada file dipilih.</small>
                        <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-pill shadow-sm" id="btnUploadKamar{{ $item->id }}" disabled>
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Foto Kamar
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
