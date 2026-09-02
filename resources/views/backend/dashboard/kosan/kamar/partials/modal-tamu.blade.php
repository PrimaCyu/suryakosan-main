<!-- MODAL TAMU KAMAR -->
<div class="modal fade" id="modalTamuKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-modern text-white">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="bi bi-people-fill me-2 text-info"></i> Data Tamu / Penghuni: {{ $item->room }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Daftar Penghuni Aktif & Disetujui</h6>
                        <small class="text-muted">Riwayat tamu yang memesan kamar {{ $item->room }}</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 40px;" class="text-center">No</th>
                                <th>Nama Tamu</th>
                                <th>Kontak</th>
                                <th>Periode Sewa</th>
                                <th>Metode Bayar</th>
                                <th class="text-center">Bukti Transfer</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th style="width: 170px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($item->tamu->where('status','approved') as $tamuIndex => $tamu)
                                <tr>
                                    <td class="text-center fw-semibold text-muted">{{ $tamuIndex + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark fs-7">{{ $tamu->name }}</div>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tamu->telp) }}" target="_blank" class="text-decoration-none text-success fw-semibold fs-7">
                                                <i class="bi bi-whatsapp me-1"></i>{{ $tamu->telp }}
                                            </a>
                                        </div>
                                        <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $tamu->email }}</small>
                                    </td>
                                    <td>
                                        <div><i class="bi bi-calendar-event me-1 text-primary"></i>{{ \Carbon\Carbon::parse($tamu->start_date)->isoFormat('D MMM Y') }}</div>
                                        <small class="text-muted"><i class="bi bi-calendar-check me-1 text-danger"></i>s/d {{ \Carbon\Carbon::parse($tamu->end_date)->isoFormat('D MMM Y HH:mm') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-subtle-secondary">{{ $tamu->payment_method ?? 'Manual' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($tamu->proof_of_transfer)
                                            <a href="{{ asset('storage/' . $tamu->proof_of_transfer) }}" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2">
                                                <i class="bi bi-eye"></i> Bukti
                                            </a>
                                        @else
                                            <span class="text-muted fs-7">-</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-dark fs-7">
                                        Rp {{ number_format($tamu->total_price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge badge-subtle-success">
                                            <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <!-- Tombol Perpanjang / Renew Tamu -->
                                            <button type="button" class="btn btn-sm btn-outline-success py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalRenewTamu{{ $tamu->id }}" title="Perpanjang Sewa">
                                                <i class="bi bi-arrow-repeat"></i> Perpanjang
                                            </button>
                                            <!-- Tombol Hapus Tamu -->
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDeleteTamu{{ $tamu->id }}" title="Hapus Data Tamu">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-people display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada data penghuni aktif untuk kamar ini.
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

<!-- MODAL RENEW & DELETE TAMU FOR EACH TAMU -->
@foreach ($item->tamu as $tamu)
    <!-- MODAL PERPANJANG (RENEW) TAMU -->
    <div class="modal fade" id="modalRenewTamu{{ $tamu->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header modal-header-success text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="bi bi-arrow-repeat me-2"></i> Perpanjang Sewa: {{ $tamu->name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.product.kosan.kamar.tamu.renew',['product_kosan' => $product_kosan, 'product_kamar_kosan' => $item->id, 'tamu' => $tamu->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">

                        <!-- SEKSI INFORMASI TAMU -->
                        <h6 class="fw-bold text-dark mb-3 pb-1 border-bottom">
                            <i class="bi bi-person-badge text-primary me-1"></i> Informasi Data Tamu
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Tamu <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $tamu->name }}" placeholder="Nama Lengkap" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" name="telp" class="form-control" value="{{ $tamu->telp }}" placeholder="08xxxxxxxxxx" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ $tamu->email }}" placeholder="tamu@example.com" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Waktu Masuk <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control" value="{{ $tamu->start_time }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai Perpanjangan <span class="text-danger">*</span></label>
                                @php
                                    $endDate = $tamu->end_date;
                                    $parts = explode(' ',$endDate);
                                @endphp
                                <input type="date" name="start_date" class="form-control" value="{{ $parts[0] }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Total Harga Tambahan (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="total_price" class="form-control" value="{{ $tamu->total_price }}" required>
                            </div>
                        </div>

                        <!-- SEKSI DURASI PERPANJANGAN -->
                        <h6 class="fw-bold text-dark mb-3 pb-1 border-bottom">
                            <i class="bi bi-hourglass-split text-warning me-1"></i> Durasi Perpanjangan Sewa
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tahun</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="tahun" class="form-control" placeholder="0" min="0" value="0">
                                    <span class="input-group-text bg-body-tertiary">Thn</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Bulan</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="bulan" class="form-control" placeholder="0" min="0" value="0">
                                    <span class="input-group-text bg-body-tertiary">Bln</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Minggu</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="minggu" class="form-control" placeholder="0" min="0" value="0">
                                    <span class="input-group-text bg-body-tertiary">Mgg</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hari</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="hari" class="form-control" placeholder="0" min="0" value="0">
                                    <span class="input-group-text bg-body-tertiary">Hari</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jam</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="jam" class="form-control" placeholder="0" min="0" value="0">
                                    <span class="input-group-text bg-body-tertiary">Jam</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalTamuKamar{{ $item->id }}">Kembali</button>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-save me-1"></i> Simpan Perpanjangan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE TAMU -->
    <div class="modal fade" id="modalDeleteTamu{{ $tamu->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-flex mb-3">
                        <i class="bi bi-trash3-fill fs-2"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Hapus Data Tamu?</h6>
                    <p class="text-muted fs-7 mb-0">Hapus riwayat tamu <strong>"{{ $tamu->name }}"</strong>?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-toggle="modal" data-bs-target="#modalTamuKamar{{ $item->id }}">Batal</button>
                    <form action="{{ route('admin.product.kosan.kamar.tamu.delete', [$product_kosan, $item->id, $tamu->id]) }}" method="POST" class="d-inline">
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
