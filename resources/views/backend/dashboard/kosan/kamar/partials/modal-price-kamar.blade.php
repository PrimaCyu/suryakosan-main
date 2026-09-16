<!-- MODAL PRICE KAMAR -->
<div class="modal fade" id="modalPriceKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-tags text-secondary me-2"></i> Pengaturan Tarif Kamar: {{ $item->room }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Daftar Kategori Harga</h6>
                        <small class="text-muted">Kelola tarif sewa khusus bulanan atau tahunan</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddPriceKamar{{ $item->id }}">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori Harga
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Kategori Sewa</th>
                                <th>Nominal Tarif</th>
                                <th>Diskon Khusus</th>
                                <th style="width: 150px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($item->priceKamar as $prcIndex => $prc)
                                <tr>
                                    <td class="text-center fw-semibold text-muted">{{ $prcIndex + 1 }}</td>
                                    <td>
                                        <span class="badge badge-subtle-primary text-capitalize">
                                            <i class="bi bi-calendar-check me-1"></i>{{ $prc->kategori === 'bulan' ? 'Bulanan' : ($prc->kategori === 'tahun' ? 'Tahunan' : $prc->kategori) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">Rp {{ number_format($prc->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($prc->discount > 0)
                                            <span class="badge badge-subtle-success">{{ $prc->discount }}%</span>
                                        @else
                                            <span class="text-muted fs-7">0%</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditPriceKamar{{ $prc->id }}" title="Edit Harga">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeletePriceKamar{{ $prc->id }}" title="Hapus Harga">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-tag display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada data harga untuk kamar ini.
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

<!-- MODAL ADD PRICE KAMAR (BULK INSERT) -->
<div class="modal fade" id="modalAddPriceKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle text-secondary me-2"></i> Tambah Tarif Kamar: {{ $item->room }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('admin.product.kosan.kamar.price.kamar.insert',['product_kosan' => $product_kosan, 'product_kamar_kosan' => $item->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                    <p class="text-muted fs-7 mb-3">
                        <i class="bi bi-info-circle me-1"></i> Tentukan kategori tarif sewa (khusus <strong>Bulanan</strong> atau <strong>Tahunan</strong>).
                    </p>
                    <div class="price-kamar-container" id="priceKamarContainer{{ $item->id }}">
                        <div class="price-kamar-row card border rounded p-3 mb-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-5">
                                    <label class="form-label mb-1">Kategori Sewa <span class="text-danger">*</span></label>
                                    <select name="priceKamar[0][kategori]" class="form-select" required>
                                        <option value="bulan">Bulanan (Per Bulan)</option>
                                        <option value="tahun">Tahunan (Per Tahun)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label mb-1">Nominal Harga (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" name="priceKamar[0][price]" class="form-control" placeholder="Contoh: 1500000" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label mb-1">Diskon (%)</label>
                                    @if(Auth::user()->isSuperAdmin())
                                        <input type="number" step="0.01" name="priceKamar[0][discount]" class="form-control" placeholder="0">
                                    @else
                                        <input type="number" step="0.01" name="priceKamar[0][discount]" class="form-control bg-light" value="0" readonly disabled title="Hanya diatur langsung oleh Super Admin">
                                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-lock-fill text-warning me-1"></i>Khusus Super Admin</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-1 btn-add-price-row" data-kamar-id="{{ $item->id }}">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori Harga Lain
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalPriceKamar{{ $item->id }}">Kembali</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Harga
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT & DELETE PRICE KAMAR FOR EACH ITEM -->
@foreach ($item->priceKamar as $prc)
    <div class="modal fade" id="modalEditPriceKamar{{ $prc->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-secondary me-2"></i> Edit Tarif Kamar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('admin.product.kosan.kamar.price.kamar.update',['product_kosan' => $product_kosan, 'product_kamar_kosan' => $item->id, 'price_kamar' => $prc->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Kategori Sewa <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select" required>
                                <option value="bulan" {{ in_array(strtolower($prc->kategori), ['bulan', 'bulanan']) ? 'selected' : '' }}>Bulanan (Per Bulan)</option>
                                <option value="tahun" {{ in_array(strtolower($prc->kategori), ['tahun', 'tahunan']) ? 'selected' : '' }}>Tahunan (Per Tahun)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nominal Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control" value="{{ $prc->price }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Diskon (%)</label>
                            @if(Auth::user()->isSuperAdmin())
                                <input type="number" step="0.01" name="discount" class="form-control" value="{{ $prc->discount }}">
                            @else
                                <input type="number" step="0.01" class="form-control bg-light" value="{{ $prc->discount }}" readonly disabled>
                                <small class="text-muted d-block mt-1"><i class="bi bi-lock-fill text-warning me-1"></i>Hanya dapat diatur langsung oleh Super Admin</small>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalPriceKamar{{ $item->id }}">Kembali</button>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDeletePriceKamar{{ $prc->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body p-4">
                    <h6 class="fw-bold text-dark mb-2">Hapus Tarif?</h6>
                    <p class="text-muted fs-8 mb-0">Hapus tarif kategori <strong>"{{ $prc->kategori }}"</strong>?</p>
                </div>
                <div class="modal-footer d-flex justify-content-end border-top-0 pt-0 pb-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-toggle="modal" data-bs-target="#modalPriceKamar{{ $item->id }}">Batal</button>
                    <form action="{{ route('admin.product.kosan.kamar.price.kamar.delete',['product_kosan' => $product_kosan, 'product_kamar_kosan' => $item->id, 'price_kamar' => $prc->id]) }}" method="POST" class="d-inline">
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
