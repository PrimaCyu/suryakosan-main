@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-credit-card-2-front text-secondary"></i> Metode Pembayaran
                </h1>
                <p class="page-subtitle">Kelola nomor rekening bank, QRIS, e-wallet, dan opsi tunai untuk pembayaran sewa.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambahMetode">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Metode
                </button>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @include('backend.dashboard.kosan.kamar.partials.alerts')

        <div class="card mb-4 border-0 shadow-sm rounded-3">
            <div class="card-header bg-body d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 py-3">
                <div>
                    <h5 class="card-title mb-0 fs-6 fw-bold">
                        <i class="bi bi-wallet2 text-secondary me-2"></i>Daftar Metode Pembayaran
                    </h5>
                    <p class="text-muted fs-8 mb-0">Total {{ $paymentMethods->total() }} metode terdaftar ({{ $activeCount }} aktif).</p>
                </div>
                <div class="search-box-responsive">
                    <form action="{{ route('admin.payment-methods.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2 w-100">
                        <select name="type" class="form-select form-select-sm" style="min-width: 135px;" onchange="this.form.submit()">
                            <option value="">Semua Tipe</option>
                            <option value="bank" {{ request('type') === 'bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="qris" {{ request('type') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="ewallet" {{ request('type') === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="cash" {{ request('type') === 'cash' ? 'selected' : '' }}>Tunai / COD</option>
                        </select>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Cari metode..." value="{{ request('search') }}" autocomplete="off">
                            <button class="btn btn-light border" type="submit" aria-label="Cari"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-min-md">
                        <thead class="table-light fs-8 text-uppercase">
                            <tr>
                                <th style="width: 45px" class="text-center">No</th>
                                <th>Metode / Bank</th>
                                <th>Tipe</th>
                                <th>No. Rekening / Akun</th>
                                <th>Atas Nama (a/n)</th>
                                <th class="text-center" style="width: 90px">QRIS</th>
                                <th class="text-center" style="width: 100px">Status</th>
                                <th style="width: 130px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paymentMethods as $index => $item)
                                <tr class="align-middle">
                                    <td class="text-center fw-semibold text-muted">{{ $paymentMethods->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-9 fw-bold">
                                                {{ $item->bank_code ?: strtoupper(substr($item->code, 0, 3)) }}
                                            </span>
                                            <span class="fw-bold text-dark">{{ $item->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ match($item->type) { 'qris' => 'bg-danger-subtle text-danger', 'ewallet' => 'bg-info-subtle text-info', 'cash' => 'bg-success-subtle text-success', default => 'bg-primary-subtle text-primary' } }} rounded-pill px-2 py-0.5 fs-9 fw-semibold">
                                            {{ $item->type_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->account_number)
                                            <code class="fs-8 fw-bold text-dark bg-light px-2 py-0.5 rounded">{{ $item->account_number }}</code>
                                        @else
                                            <span class="text-muted fs-9">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fs-8 text-secondary">{{ $item->account_holder ?: '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->qr_image_url)
                                            <button type="button" class="btn btn-sm btn-light border p-1 rounded-2" data-bs-toggle="modal" data-bs-target="#modalViewQr{{ $item->id }}" title="Lihat QR Code">
                                                <img src="{{ $item->qr_image_url }}" alt="QR" style="width: 26px; height: 26px; object-fit: contain;">
                                            </button>
                                        @elseif($item->type === 'qris')
                                            <span class="text-muted fs-9">Default</span>
                                        @else
                                            <span class="text-muted fs-9">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.payment-methods.toggle', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($item->is_active)
                                                <button type="submit" class="badge bg-success border-0 px-2.5 py-1 rounded-pill fs-9 fw-semibold cursor-pointer" title="Klik untuk nonaktifkan">
                                                    Aktif
                                                </button>
                                            @else
                                                <button type="submit" class="badge bg-secondary border-0 px-2.5 py-1 rounded-pill fs-9 fw-semibold cursor-pointer" title="Klik untuk aktifkan">
                                                    Nonaktif
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary py-0.5 px-2 fs-8 rounded-2" data-bs-toggle="modal" data-bs-target="#modalEditMetode{{ $item->id }}">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-0.5 px-2 fs-8 rounded-2" data-bs-toggle="modal" data-bs-target="#modalDeleteMetode{{ $item->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        Belum ada metode pembayaran yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($paymentMethods->hasPages())
                <div class="card-footer bg-body py-2">
                    {{ $paymentMethods->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL TAMBAH METODE PEMBAYARAN -->
<!-- ========================================== -->
<div class="modal fade" id="modalTambahMetode" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom py-2.5 px-3">
                    <h5 class="modal-title fw-bold fs-6">
                        <i class="bi bi-plus-circle text-primary me-1.5"></i>Tambah Metode Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3">
                    <div class="row g-2.5">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-8 mb-1">Tipe Metode <span class="text-danger">*</span></label>
                            <select name="type" id="create-type-select" class="form-select form-select-sm" required onchange="handleTypeChange('create')">
                                <option value="bank" selected>Transfer Bank (BCA, Mandiri, BRI, dll)</option>
                                <option value="qris">QRIS (Scan Barcode)</option>
                                <option value="ewallet">E-Wallet (DANA, GoPay, OVO, ShopeePay)</option>
                                <option value="cash">Tunai / COD (Bayar di Tempat)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-8 mb-1">Nama Layanan / Bank <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: Bank BRI" required>
                        </div>

                        <div class="col-md-4" id="create-field-code">
                            <label class="form-label fw-semibold fs-8 mb-1">Kode Singkat</label>
                            <input type="text" name="bank_code" class="form-control form-control-sm text-uppercase" placeholder="Contoh: BRI, DANA" maxlength="10">
                        </div>

                        <div class="col-md-4" id="create-field-rek">
                            <label class="form-label fw-semibold fs-8 mb-1">No. Rekening / No. HP</label>
                            <input type="text" name="account_number" class="form-control form-control-sm" placeholder="Contoh: 0123456789">
                        </div>

                        <div class="col-md-4" id="create-field-holder">
                            <label class="form-label fw-semibold fs-8 mb-1">Atas Nama (a/n)</label>
                            <input type="text" name="account_holder" class="form-control form-control-sm" placeholder="Contoh: PT Sinar Citra Lestari">
                        </div>

                        <!-- UPLOAD QRIS IMAGE -->
                        <div class="col-12" id="create-field-qr">
                            <label class="form-label fw-semibold fs-8 mb-1">Upload Gambar QRIS</label>
                            <input type="file" name="qr_image" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp,image/jpg" onchange="previewQrUpload(event, 'create-preview-box')">
                            <div class="form-text fs-9 text-muted mt-0.5">Disarankan gambar rasio 1:1 (persegi), maks 2MB.</div>

                            <div id="create-preview-box" class="mt-2 text-center d-none">
                                <img src="" alt="Pratinjau QR" class="img-thumbnail rounded p-1 bg-white shadow-sm" style="max-height: 140px; aspect-ratio: 1/1; object-fit: contain;">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold fs-8 mb-1">Instruksi Pembayaran (Opsional)</label>
                            <textarea name="instructions" class="form-control form-control-sm fs-8" rows="2" placeholder="Catatan atau panduan bagi penyewa..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-8 mb-1">Urutan</label>
                            <input type="number" name="sort_order" class="form-control form-control-sm" value="0" min="0">
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="create-status-active" checked>
                                <label class="form-check-label fw-semibold fs-8" for="create-status-active">
                                    Status Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-2 px-3">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODALS EDIT, VIEW QR, & DELETE -->
<!-- ========================================== -->
@foreach ($paymentMethods as $item)

    <!-- 1. MODAL EDIT -->
    <div class="modal fade" id="modalEditMetode{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form action="{{ route('admin.payment-methods.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom py-2.5 px-3">
                        <h5 class="modal-title fw-bold fs-6">
                            <i class="bi bi-pencil-square text-primary me-1.5"></i>Edit: {{ $item->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-3">
                        <div class="row g-2.5">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-8 mb-1">Tipe Metode <span class="text-danger">*</span></label>
                                <select name="type" class="form-select form-select-sm" required onchange="handleTypeChange('edit{{ $item->id }}')" id="edit{{ $item->id }}-type-select">
                                    <option value="bank" {{ $item->type === 'bank' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="qris" {{ $item->type === 'qris' ? 'selected' : '' }}>QRIS</option>
                                    <option value="ewallet" {{ $item->type === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                                    <option value="cash" {{ $item->type === 'cash' ? 'selected' : '' }}>Tunai / COD</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-8 mb-1">Nama Layanan / Bank <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-sm" value="{{ $item->name }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-8 mb-1">Kode Singkat</label>
                                <input type="text" name="bank_code" class="form-control form-control-sm text-uppercase" value="{{ $item->bank_code }}" maxlength="10">
                            </div>

                            <div class="col-md-4" id="edit{{ $item->id }}-field-rek">
                                <label class="form-label fw-semibold fs-8 mb-1">No. Rekening / No. HP</label>
                                <input type="text" name="account_number" class="form-control form-control-sm" value="{{ $item->account_number }}" placeholder="Contoh: 1234567890">
                            </div>

                            <div class="col-md-4" id="edit{{ $item->id }}-field-holder">
                                <label class="form-label fw-semibold fs-8 mb-1">Atas Nama (a/n)</label>
                                <input type="text" name="account_holder" class="form-control form-control-sm" value="{{ $item->account_holder }}" placeholder="Contoh: PT Sinar Citra Lestari">
                            </div>

                            <!-- Input code hidden / auto managed -->
                            <input type="hidden" name="code" value="{{ $item->code }}">

                            <!-- EDIT QRIS IMAGE -->
                            <div class="col-12" id="edit{{ $item->id }}-field-qr">
                                <label class="form-label fw-semibold fs-8 mb-1">Gambar QRIS / Barcode</label>

                                @if($item->qr_image_url)
                                    <div class="d-flex align-items-center gap-2 mb-2 p-1.5 bg-light rounded-2 border">
                                        <img src="{{ $item->qr_image_url }}" alt="QR" class="rounded" style="width: 44px; height: 44px; object-fit: contain;">
                                        <div class="flex-grow-1">
                                            <span class="fs-9 text-dark fw-bold d-block">QR Terpasang</span>
                                            <div class="form-check mt-0.5">
                                                <input class="form-check-input" type="checkbox" name="delete_qr_image" value="1" id="delQr{{ $item->id }}">
                                                <label class="form-check-label fs-9 text-danger" for="delQr{{ $item->id }}">
                                                    Hapus QR ini
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <input type="file" name="qr_image" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp,image/jpg" onchange="previewQrUpload(event, 'edit{{ $item->id }}-preview-box')">
                                <div class="form-text fs-9 text-muted mt-0.5">Disarankan gambar rasio 1:1 (persegi), maks 2MB.</div>

                                <div id="edit{{ $item->id }}-preview-box" class="mt-2 text-center d-none">
                                    <img src="" alt="Pratinjau QR" class="img-thumbnail rounded p-1 bg-white shadow-sm" style="max-height: 140px; aspect-ratio: 1/1; object-fit: contain;">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold fs-8 mb-1">Instruksi Pembayaran</label>
                                <textarea name="instructions" class="form-control form-control-sm fs-8" rows="2">{{ $item->instructions }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-8 mb-1">Urutan</label>
                                <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $item->sort_order }}" min="0">
                            </div>

                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit-active-{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold fs-8" for="edit-active-{{ $item->id }}">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-top py-2 px-3">
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. MODAL VIEW QR LIGHTBOX -->
    @if($item->qr_image_url)
    <div class="modal fade" id="modalViewQr{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg text-center p-3 rounded-3">
                <h6 class="fw-bold mb-2 fs-7">{{ $item->name }}</h6>
                <div class="p-2 bg-white border rounded mb-2 d-inline-block shadow-sm">
                    <img src="{{ $item->qr_image_url }}" alt="QRIS {{ $item->name }}" class="img-fluid rounded" style="max-height: 220px; aspect-ratio: 1/1; object-fit: contain;">
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ $item->qr_image_url }}" download="QRIS-{{ $item->code }}.png" class="btn btn-xs btn-outline-primary fs-9">
                        <i class="bi bi-download me-1"></i> Unduh
                    </a>
                    <button type="button" class="btn btn-xs btn-secondary fs-9" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 3. MODAL DELETE -->
    <div class="modal fade" id="modalDeleteMetode{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-3 text-center p-3">
                <h6 class="fw-bold mb-1 text-dark">Hapus Metode?</h6>
                <p class="text-muted fs-8 mb-3">
                    Hapus metode <strong>{{ $item->name }}</strong>?
                </p>
                <form action="{{ route('admin.payment-methods.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-sm btn-light fs-8 px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger fs-8 px-3">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endforeach

<script>
function handleTypeChange(prefix) {
    const typeSelect = document.getElementById(prefix + '-type-select');
    if (!typeSelect) return;

    const val = typeSelect.value;
    const fieldRek = document.getElementById(prefix + '-field-rek');
    const fieldHolder = document.getElementById(prefix + '-field-holder');
    const fieldQr = document.getElementById(prefix + '-field-qr');

    if (val === 'cash') {
        if (fieldRek) fieldRek.classList.add('d-none');
        if (fieldHolder) fieldHolder.classList.add('d-none');
        if (fieldQr) fieldQr.classList.add('d-none');
    } else if (val === 'qris') {
        if (fieldRek) fieldRek.classList.add('d-none');
        if (fieldHolder) fieldHolder.classList.remove('d-none');
        if (fieldQr) fieldQr.classList.remove('d-none');
    } else {
        if (fieldRek) fieldRek.classList.remove('d-none');
        if (fieldHolder) fieldHolder.classList.remove('d-none');
        if (fieldQr) fieldQr.classList.remove('d-none');
    }
}

function previewQrUpload(event, previewBoxId) {
    const file = event.target.files[0];
    const previewBox = document.getElementById(previewBoxId);
    if (!previewBox) return;

    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2 MB!');
            event.target.value = '';
            previewBox.classList.add('d-none');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = previewBox.querySelector('img');
            if (img) img.src = e.target.result;
            previewBox.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    } else {
        previewBox.classList.add('d-none');
    }
}
</script>

@endsection
