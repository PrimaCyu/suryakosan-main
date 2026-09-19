<!-- MODAL TAMU KAMAR -->
<div class="modal fade" id="modalTamuKamar{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-people text-secondary me-2"></i> Riwayat Penghuni: {{ $item->room }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
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
                                            <a href="https://wa.me/{{ $tamu->whatsapp_number }}" target="_blank" class="text-decoration-none text-success fw-semibold fs-7" title="Hubungi via WhatsApp">
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
                                            @php
                                                $kosanTitle = $kosan->title ?? 'Sinar Citra Lestari';
                                                $endDateStr = \Carbon\Carbon::parse($tamu->end_date)->isoFormat('D MMMM Y');
                                                $priceStr = number_format($tamu->total_price, 0, ',', '.');
                                                $pesanWA = "Halo Kak *{$tamu->name}*,\n\n"
                                                         . "Kami dari pengelola *{$kosanTitle}* (Sinar Citra Lestari).\n"
                                                         . "Mengingatkan bahwa masa sewa kamar *{$item->room}* Anda akan berakhir pada *{$endDateStr}*.\n\n"
                                                         . "Apakah Kakak berencana untuk memperpanjang sewa untuk periode berikutnya?\n"
                                                         . "Biaya sewa: *Rp {$priceStr}*.\n\n"
                                                         . "Mohon konfirmasinya ya Kak. Terima kasih! 🙏";
                                                $waHref = "https://wa.me/{$tamu->whatsapp_number}?text=" . rawurlencode($pesanWA);
                                            @endphp
                                            <!-- Tombol Chat WA -->
                                            <a href="{{ $waHref }}" target="_blank" class="btn btn-sm btn-outline-success py-1 px-2" title="Kirim Tagihan via WhatsApp">
                                                <i class="bi bi-whatsapp"></i> Chat
                                            </a>
                                            <!-- Tombol Perpanjang / Renew Tamu -->
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalRenewTamu{{ $tamu->id }}" title="Perpanjang Sewa">
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
            <form action="{{ route('admin.product.kosan.kamar.tamu.renew',['product_kosan' => $product_kosan, 'product_kamar_kosan' => $item->id, 'tamu' => $tamu->id]) }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-success text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="bi bi-arrow-repeat me-2"></i> Perpanjang Sewa: {{ $tamu->name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-1">
                            <i class="bi bi-hourglass-split text-warning me-1"></i> Durasi Perpanjangan Sewa
                        </h6>
                        <small class="text-muted">Pilih durasi sewa tambahan</small>
                    </div>

                    <!-- PILIHAN DURASI CEPAT -->
                    <div class="mb-3 p-2.5 rounded-3 bg-body-tertiary border">
                        <label class="form-label d-block mb-1.5 fs-8 fw-semibold text-secondary">
                            Pilih Durasi Tambahan:
                        </label>
                        <div class="d-flex flex-wrap gap-1.5">
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1" onclick="document.getElementById('renew_tahun_{{ $tamu->id }}').value=0; document.getElementById('renew_bulan_{{ $tamu->id }}').value=1;">
                                +1 Bulan
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1" onclick="document.getElementById('renew_tahun_{{ $tamu->id }}').value=0; document.getElementById('renew_bulan_{{ $tamu->id }}').value=3;">
                                +3 Bulan
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1" onclick="document.getElementById('renew_tahun_{{ $tamu->id }}').value=0; document.getElementById('renew_bulan_{{ $tamu->id }}').value=6;">
                                +6 Bulan
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1" onclick="document.getElementById('renew_tahun_{{ $tamu->id }}').value=1; document.getElementById('renew_bulan_{{ $tamu->id }}').value=0;">
                                +1 Tahun
                            </button>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tahun</label>
                            <div class="input-group input-group-sm">
                                <input type="number" id="renew_tahun_{{ $tamu->id }}" name="tahun" class="form-control" placeholder="0" min="0" value="0">
                                <span class="input-group-text bg-body-tertiary">Thn</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Bulan</label>
                            <div class="input-group input-group-sm">
                                <input type="number" id="renew_bulan_{{ $tamu->id }}" name="bulan" class="form-control" placeholder="0" min="0" value="1">
                                <span class="input-group-text bg-body-tertiary">Bln</span>
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI METODE PEMBAYARAN -->
                    <h6 class="fw-bold text-dark mb-3 pb-1 border-bottom mt-4">
                        <i class="bi bi-credit-card text-secondary me-1"></i> Pembayaran Perpanjangan
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required>
                                <option value="cash" selected>Tunai / Cash (Di Tempat)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unggah Bukti Pembayaran (Opsional)</label>
                            <input type="file" name="proof_of_transfer" class="form-control" accept="image/jpeg,image/png,image/webp,image/jpg">
                            <small class="text-muted fs-9">Format JPG, PNG, WEBP (Maks. 2MB)</small>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalTamuKamar{{ $item->id }}">Kembali</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perpanjangan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DELETE TAMU -->
    <div class="modal fade" id="modalDeleteTamu{{ $tamu->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body p-4">
                    <h6 class="fw-bold text-dark mb-2">Hapus Data Tamu?</h6>
                    <p class="text-muted fs-8 mb-0">Hapus riwayat tamu <strong>"{{ $tamu->name }}"</strong>?</p>
                </div>
                <div class="modal-footer d-flex justify-content-end border-top-0 pt-0 pb-3">
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
