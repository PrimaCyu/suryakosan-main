@extends('backend.dashboard.main')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="page-header-box">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-credit-card-2-front text-secondary"></i> Metode Pembayaran
                </h1>
                <p class="page-subtitle">Kelola opsi pembayaran untuk transaksi sewa kamar.</p>
            </div>
            <div class="page-header-actions">
                <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTambahMetode">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Metode</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @include('backend.dashboard.kosan.kamar.partials.alerts')

        <div class="card mb-4">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-title mb-0">Daftar Metode Pembayaran</h5>
                    <span class="badge badge-subtle-secondary">{{ $paymentMethods->total() }}</span>
                </div>

                <div class="search-box-responsive">
                    <form action="{{ route('admin.payment-methods.index') }}" method="GET" class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama atau rekening..." value="{{ request('search') }}" autocomplete="off">
                        @if(request('search'))
                            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 48px;" class="text-center ps-3">No</th>
                                <th>Metode Pembayaran</th>
                                <th>Rekening / Tujuan</th>
                                <th class="text-center" style="width: 90px;">QRIS</th>
                                <th class="text-center" style="width: 100px;">Status</th>
                                <th class="text-end pe-4" style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paymentMethods as $index => $item)
                                <tr>
                                    <td class="text-center text-muted ps-3 tabular-nums fs-8">
                                        {{ $paymentMethods->firstItem() + $index }}
                                    </td>

                                    <!-- Metode & Tipe -->
                                    <td>
                                        <div class="fw-semibold text-dark fs-8">{{ $item->name }}</div>
                                        <div class="text-muted fs-9">
                                            {{ $item->type_label }}
                                            @if($item->bank_code)
                                                &bull; {{ $item->bank_code }}
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Rekening / Tujuan -->
                                    <td>
                                        @if($item->account_number)
                                            <span class="fw-medium text-dark fs-8 tabular-nums">{{ $item->account_number }}</span>
                                        @elseif($item->type === 'qris')
                                            <span class="text-dark fs-8">Scan Barcode QRIS</span>
                                        @elseif($item->type === 'cash')
                                            <span class="text-dark fs-8">Tunai di Tempat</span>
                                        @else
                                            <span class="text-muted fs-8">-</span>
                                        @endif
                                    </td>

                                    <!-- QRIS Thumbnail -->
                                    <td class="text-center">
                                        @if($item->qr_image_url)
                                            <button type="button" class="btn btn-xs btn-light border p-1 rounded" data-bs-toggle="modal" data-bs-target="#modalViewQr{{ $item->id }}" title="Lihat Gambar QR">
                                                <img src="{{ $item->qr_image_url }}" alt="QR" style="width: 22px; height: 22px; object-fit: contain;">
                                            </button>
                                        @else
                                            <span class="text-muted fs-8">—</span>
                                        @endif
                                    </td>

                                    <!-- Status Toggle -->
                                    <td class="text-center">
                                        <form action="{{ route('admin.payment-methods.toggle', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($item->is_active)
                                                <button type="submit" class="badge badge-subtle-success border-0 cursor-pointer px-2.5 py-1" title="Klik untuk nonaktifkan">
                                                    Aktif
                                                </button>
                                            @else
                                                <button type="submit" class="badge badge-subtle-secondary border-0 cursor-pointer px-2.5 py-1" title="Klik untuk aktifkan">
                                                    Nonaktif
                                                </button>
                                            @endif
                                        </form>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="text-end pe-4">
                                        <div class="action-btn-group">
                                            <button type="button" class="btn btn-xs btn-light border px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalEditMetode{{ $item->id }}" title="Edit Metode">
                                                <i class="bi bi-pencil text-secondary"></i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalDeleteMetode{{ $item->id }}" title="Hapus Metode">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="empty-state-box">
                                            <div class="empty-state-icon">
                                                <i class="bi bi-credit-card"></i>
                                            </div>
                                            <h6>Belum Ada Metode Pembayaran</h6>
                                            <p>Tambahkan metode pembayaran baru untuk mempermudah transaksi penyewa.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($paymentMethods->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted fs-8">
                        Menampilkan {{ $paymentMethods->firstItem() ?? 0 }} - {{ $paymentMethods->lastItem() ?? 0 }} dari {{ $paymentMethods->total() }} metode
                    </small>
                    <div>
                        {{ $paymentMethods->links('pagination::bootstrap-5') }}
                    </div>
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
        <div class="modal-content">
            <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Metode Pembayaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori Metode <span class="text-danger">*</span></label>
                            <select name="type" id="create-type-select" class="form-select" required onchange="handleTypeChange('create')">
                                <option value="bank" selected>Transfer Bank (BCA, Mandiri, BRI, dll)</option>
                                <option value="qris">QRIS (Scan Barcode)</option>
                                <option value="ewallet">E-Wallet (DANA, GoPay, OVO, ShopeePay)</option>
                                <option value="cash">Tunai / COD (Bayar di Lokasi)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Layanan / Bank <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Bank BRI / DANA / QRIS" required>
                        </div>

                        <div class="col-md-4" id="create-field-code">
                            <label class="form-label">Kode Singkat</label>
                            <input type="text" name="bank_code" class="form-control text-uppercase" placeholder="Contoh: BRI, DANA" maxlength="10">
                        </div>

                        <div class="col-md-4" id="create-field-rek">
                            <label class="form-label">No. Rekening / No. Akun</label>
                            <input type="text" name="account_number" class="form-control tabular-nums" placeholder="Contoh: 0123456789">
                        </div>

                        <div class="col-md-4" id="create-field-holder">
                            <label class="form-label">Atas Nama (a/n)</label>
                            <input type="text" name="account_holder" class="form-control" placeholder="Contoh: PT Sinar Citra Lestari">
                        </div>

                        <!-- UPLOAD QRIS IMAGE -->
                        <div class="col-12" id="create-field-qr">
                            <label class="form-label">Upload Gambar QRIS / Barcode (Opsional)</label>
                            <input type="file" name="qr_image" class="form-control" accept="image/jpeg,image/png,image/webp,image/jpg" onchange="previewQrUpload(event, 'create-preview-box')">
                            <div class="form-text">Gambar rasio 1:1 (persegi), maks 2 MB.</div>

                            <div id="create-preview-box" class="mt-2 text-center d-none p-2 bg-light border rounded">
                                <img src="" alt="Pratinjau QR" class="img-thumbnail rounded p-1 bg-white" style="max-height: 140px; aspect-ratio: 1/1; object-fit: contain;">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Instruksi Pembayaran (Opsional)</label>
                            <textarea name="instructions" class="form-control" rows="2" placeholder="Catatan atau panduan bagi calon penyewa..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="create-status-active" checked>
                                <label class="form-check-label fw-semibold fs-8 text-dark cursor-pointer" for="create-status-active">
                                    Aktifkan metode ini
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-3">Simpan Metode</button>
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
            <div class="modal-content">
                <form action="{{ route('admin.payment-methods.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit: {{ $item->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kategori Metode <span class="text-danger">*</span></label>
                                <select name="type" class="form-select edit-type-select" data-prefix="edit{{ $item->id }}" required onchange="handleTypeChange('edit{{ $item->id }}')" id="edit{{ $item->id }}-type-select">
                                    <option value="bank" {{ $item->type === 'bank' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="qris" {{ $item->type === 'qris' ? 'selected' : '' }}>QRIS</option>
                                    <option value="ewallet" {{ $item->type === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                                    <option value="cash" {{ $item->type === 'cash' ? 'selected' : '' }}>Tunai / COD</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Layanan / Bank <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Kode Singkat</label>
                                <input type="text" name="bank_code" class="form-control text-uppercase" value="{{ $item->bank_code }}" maxlength="10">
                            </div>

                            <div class="col-md-4" id="edit{{ $item->id }}-field-rek">
                                <label class="form-label">No. Rekening / No. Akun</label>
                                <input type="text" name="account_number" class="form-control tabular-nums" value="{{ $item->account_number }}" placeholder="Contoh: 1234567890">
                            </div>

                            <div class="col-md-4" id="edit{{ $item->id }}-field-holder">
                                <label class="form-label">Atas Nama (a/n)</label>
                                <input type="text" name="account_holder" class="form-control" value="{{ $item->account_holder }}" placeholder="Contoh: PT Sinar Citra Lestari">
                            </div>

                            <input type="hidden" name="code" value="{{ $item->code }}">

                            <!-- EDIT QRIS IMAGE -->
                            <div class="col-12" id="edit{{ $item->id }}-field-qr">
                                <label class="form-label">Gambar QRIS / Barcode</label>

                                @if($item->qr_image_url)
                                    <div class="d-flex align-items-center gap-3 mb-2 p-2 bg-light rounded border">
                                        <img src="{{ $item->qr_image_url }}" alt="QR" class="rounded border bg-white p-1" style="width: 44px; height: 44px; object-fit: contain;">
                                        <div class="flex-grow-1">
                                            <span class="fs-8 text-dark fw-semibold d-block">QR Terpasang</span>
                                            <div class="form-check mt-0.5">
                                                <input class="form-check-input" type="checkbox" name="delete_qr_image" value="1" id="delQr{{ $item->id }}">
                                                <label class="form-check-label fs-9 text-danger cursor-pointer" for="delQr{{ $item->id }}">
                                                    Hapus QR ini
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <input type="file" name="qr_image" class="form-control" accept="image/jpeg,image/png,image/webp,image/jpg" onchange="previewQrUpload(event, 'edit{{ $item->id }}-preview-box')">
                                <div class="form-text">Unggah file baru jika ingin mengganti QR (Maks 2 MB).</div>

                                <div id="edit{{ $item->id }}-preview-box" class="mt-2 text-center d-none p-2 bg-light border rounded">
                                    <img src="" alt="Pratinjau QR" class="img-thumbnail rounded p-1 bg-white" style="max-height: 140px; aspect-ratio: 1/1; object-fit: contain;">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Instruksi Pembayaran</label>
                                <textarea name="instructions" class="form-control" rows="2">{{ $item->instructions }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Urutan Tampil</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ $item->sort_order }}" min="0">
                            </div>

                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit-active-{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold fs-8 text-dark cursor-pointer" for="edit-active-{{ $item->id }}">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-3">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. MODAL VIEW QR LIGHTBOX -->
    @if($item->qr_image_url)
    <div class="modal fade" id="modalViewQr{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-header py-2 px-3">
                    <h6 class="modal-title fs-7 fw-bold mb-0">{{ $item->name }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="p-2 bg-white border rounded mb-2 d-inline-block">
                        <img src="{{ $item->qr_image_url }}" alt="QRIS {{ $item->name }}" class="img-fluid rounded" style="max-height: 200px; aspect-ratio: 1/1; object-fit: contain;">
                    </div>
                    @if($item->account_holder)
                        <div class="fs-8 text-muted">a/n <strong class="text-dark">{{ $item->account_holder }}</strong></div>
                    @endif
                </div>
                <div class="modal-footer py-2 px-3 d-flex justify-content-between">
                    <a href="{{ $item->qr_image_url }}" download="QRIS-{{ $item->code }}.png" class="btn btn-xs btn-outline-primary">
                        <i class="bi bi-download"></i> Unduh
                    </a>
                    <button type="button" class="btn btn-xs btn-light border" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 3. MODAL DELETE -->
    <div class="modal fade" id="modalDeleteMetode{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="empty-state-icon bg-danger-subtle text-danger mx-auto mb-3">
                        <i class="bi bi-trash3"></i>
                    </div>
                    <h6 class="fw-bold mb-1 text-dark">Hapus Metode?</h6>
                    <p class="text-muted fs-8 mb-3">
                        Hapus <strong>{{ $item->name }}</strong> dari opsi pembayaran?
                    </p>
                    <form action="{{ route('admin.payment-methods.destroy', $item->id) }}" method="POST" class="d-flex gap-2 justify-content-center">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-light border flex-grow-1" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger flex-grow-1">Ya, Hapus</button>
                    </form>
                </div>
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

document.addEventListener('DOMContentLoaded', function() {
    handleTypeChange('create');
    document.querySelectorAll('.edit-type-select').forEach(function(selectEl) {
        const prefix = selectEl.getAttribute('data-prefix');
        if (prefix) handleTypeChange(prefix);
    });
});
</script>

@endsection
