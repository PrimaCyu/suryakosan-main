@extends('backend.dashboard.main')

@section('content')

@php
    $hour = (int)date('H');
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
@endphp

<style>
  :root {
    --dash-serif: 'Lora', Georgia, 'Times New Roman', serif;
    --dash-sans: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    --dash-accent-normal: #0f172a;
    --dash-accent-urgent: #b91c1c;
    --dash-border: #e2e8f0;
    --dash-bg-subtle: #f8fafc;
    --dash-card-bg: #ffffff;
    --dash-text-main: #0f172a;
    --dash-text-muted: #64748b;
    --dash-radius: 8px;
    --dash-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
  }

  .dash-view {
    font-family: var(--dash-sans);
    color: var(--dash-text-main);
  }

  .dash-heading-serif {
    font-family: var(--dash-serif);
    letter-spacing: -0.015em;
  }

  .dash-card {
    background-color: var(--dash-card-bg);
    border: 1px solid var(--dash-border);
    border-radius: var(--dash-radius);
    box-shadow: var(--dash-shadow);
  }

  .dash-card-urgent {
    background-color: #ffffff;
    border: 1px solid rgba(185, 28, 28, 0.2);
    border-left: 4px solid var(--dash-accent-urgent);
    border-radius: var(--dash-radius);
    box-shadow: var(--dash-shadow);
  }

  .dash-btn-primary {
    background-color: var(--dash-accent-normal);
    color: #ffffff;
    border: 1px solid var(--dash-accent-normal);
    border-radius: var(--dash-radius);
    font-size: 0.8125rem;
    font-weight: 600;
    padding: 0.42rem 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    text-decoration: none;
    transition: all 0.15s ease;
  }
  .dash-btn-primary:hover {
    background-color: #1e293b;
    color: #ffffff;
  }

  .dash-btn-urgent {
    background-color: var(--dash-accent-urgent);
    color: #ffffff;
    border: 1px solid var(--dash-accent-urgent);
    border-radius: var(--dash-radius);
    font-size: 0.8125rem;
    font-weight: 600;
    padding: 0.42rem 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    text-decoration: none;
    transition: all 0.15s ease;
  }
  .dash-btn-urgent:hover {
    background-color: #991b1b;
    color: #ffffff;
  }

  .dash-btn-secondary {
    background-color: #ffffff;
    color: var(--dash-text-main);
    border: 1px solid var(--dash-border);
    border-radius: var(--dash-radius);
    font-size: 0.8125rem;
    font-weight: 500;
    padding: 0.42rem 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    text-decoration: none;
    transition: all 0.15s ease;
  }
  .dash-btn-secondary:hover {
    background-color: var(--dash-bg-subtle);
    color: var(--dash-text-main);
  }

  .dash-table {
    width: 100%;
    border-collapse: collapse;
  }
  .dash-table th {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--dash-text-muted);
    border-bottom: 1px solid var(--dash-border);
    padding: 0.65rem 0.85rem;
    text-align: left;
    background-color: var(--dash-bg-subtle);
  }
  .dash-table td {
    font-size: 0.8125rem;
    color: var(--dash-text-main);
    border-bottom: 1px solid var(--dash-border);
    padding: 0.75rem 0.85rem;
    vertical-align: middle;
  }
  .dash-table tr:last-child td {
    border-bottom: none;
  }

  .progress-thin {
    height: 6px;
    background-color: #e2e8f0;
    border-radius: 9999px;
    overflow: hidden;
  }
  .progress-bar-normal {
    background-color: var(--dash-accent-normal);
    height: 100%;
    border-radius: 9999px;
  }
</style>

<div class="dash-view py-3 px-2 px-md-3">
  <div class="container-fluid">

    <!-- 1. HEADER SECTION (Tenang, Bersih & Profesional) -->
    <div class="dash-card p-4 mb-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="fs-7 text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
            <span class="text-muted">•</span>
            <span class="fs-7 fw-semibold text-secondary">
              {{ Auth::user()->isSuperAdmin() ? 'Semua Cabang Properti' : 'Cabang Ditugaskan' }}
            </span>
          </div>
          <h1 class="dash-heading-serif h3 mb-0 text-dark">
            {{ $greeting }}, {{ Auth::user()->name ?? 'Pengelola' }}
          </h1>
          <p class="fs-7 text-muted mb-0 mt-1">
            Sinar Citra Lestari • Sistem Pengelolaan Operasional Kos Terpadu
          </p>
        </div>

        <div class="d-flex align-items-center gap-2">
          <a href="{{ route('home') }}" target="_blank" class="dash-btn-secondary" title="Buka website pencarian kos">
            <i class="bi bi-box-arrow-up-right"></i>
            <span>Lihat Website</span>
          </a>
          <a href="{{ route('admin.booking.index') }}" class="dash-btn-primary" title="Halaman kelola booking">
            <i class="bi bi-calendar-check"></i>
            <span>Kelola Booking</span>
          </a>
        </div>
      </div>
    </div>

    <!-- 2. PRIORITAS UTAMA: PERLU TINDAKAN HARI INI (Visual Urgency Focus) -->
    <div class="mb-4">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="d-flex align-items-center gap-2">
          <h2 class="dash-heading-serif h5 mb-0 text-dark">Perlu Tindakan Hari Ini</h2>
          @if($totalUrgentActions > 0)
            <span class="badge" style="background-color: rgba(185,28,28,0.1); color: var(--dash-accent-urgent); border: 1px solid rgba(185,28,28,0.25); font-weight: 600; font-size: 0.75rem; border-radius: var(--dash-radius);">
              {{ $totalUrgentActions }} item memerlukan perhatian
            </span>
          @else
            <span class="badge" style="background-color: var(--dash-bg-subtle); color: var(--dash-text-muted); border: 1px solid var(--dash-border); font-weight: 500; font-size: 0.75rem; border-radius: var(--dash-radius);">
              Status normal
            </span>
          @endif
        </div>
        <span class="fs-8 text-muted d-none d-sm-inline">
          Ditinjau otomatis berdasarkan tanggal jatuh tempo dan antrean reservasi.
        </span>
      </div>

      @if($totalUrgentActions > 0)
        <div class="row g-3">
          <!-- A. Sewa & Pembayaran Jatuh Tempo -->
          <div class="col-12 col-lg-7">
            <div class="dash-card-urgent h-100 p-3 p-sm-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-clock-history" style="color: var(--dash-accent-urgent);"></i>
                  <h3 class="fs-6 fw-bold mb-0 text-dark">Sewa & Pembayaran Jatuh Tempo</h3>
                </div>
                <span class="fs-8 text-muted">{{ $expiringOrOverdueCount }} penghuni</span>
              </div>

              @if($expiringOrOverdueCount > 0)
                <div class="table-responsive">
                  <table class="dash-table">
                    <thead>
                      <tr>
                        <th>Penghuni</th>
                        <th>Kamar & Cabang</th>
                        <th>Jatuh Tempo</th>
                        <th>Tagihan</th>
                        <th class="text-end">Tindakan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($expiringOrOverdueTenancies as $tenancy)
                        @php
                            $endDate = \Carbon\Carbon::parse($tenancy->end_date);
                            $diffDays = (int)now()->startOfDay()->diffInDays($endDate->startOfDay(), false);
                            $cleanPhone = preg_replace('/[^0-9]/', '', $tenancy->telp);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                            $kosanTitle = $tenancy->productKamarKosan->productKosan->title ?? 'Sinar Citra Lestari';
                            $kamarRoom = $tenancy->productKamarKosan->room ?? 'Kamar';
                            $waMessage = urlencode("Halo Kak {$tenancy->name}, kami dari pengelola Sinar Citra Lestari menginformasikan mengenai masa sewa {$kamarRoom} di {$kosanTitle} yang jatuh tempo pada tanggal {$endDate->format('d/m/Y')}. Mohon konfirmasi mengenai rencana perpanjangan sewa. Terima kasih.");
                        @endphp
                        <tr>
                          <td>
                            <div class="fw-semibold text-dark">{{ $tenancy->name }}</div>
                            <span class="fs-8 text-muted">{{ $tenancy->telp }}</span>
                          </td>
                          <td>
                            <div class="fw-medium">{{ $kamarRoom }}</div>
                            <span class="fs-8 text-muted">{{ $kosanTitle }}</span>
                          </td>
                          <td>
                            @if($diffDays < 0)
                              <span class="fw-bold" style="color: var(--dash-accent-urgent);">
                                Lewat {{ abs($diffDays) }} hari
                              </span>
                            @elseif($diffDays === 0)
                              <span class="fw-bold" style="color: var(--dash-accent-urgent);">
                                Hari ini ({{ $endDate->format('d M') }})
                              </span>
                            @else
                              <span class="fw-semibold text-dark">
                                {{ $diffDays }} hari lagi ({{ $endDate->format('d M') }})
                              </span>
                            @endif
                          </td>
                          <td>
                            <span class="fw-medium">Rp {{ number_format($tenancy->total_price, 0, ',', '.') }}</span>
                          </td>
                          <td class="text-end">
                            <div class="d-inline-flex gap-1">
                              <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMessage }}" target="_blank" class="dash-btn-secondary" title="Kirim pengingat WhatsApp">
                                <i class="bi bi-whatsapp" style="color: #16a34a;"></i>
                                <span class="d-none d-sm-inline">Ingatkan</span>
                              </a>
                              <a href="{{ route('admin.booking.index') }}" class="dash-btn-primary" title="Buka kelola tamu">
                                <i class="bi bi-arrow-repeat"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="p-3 text-center text-muted fs-8">
                  Tidak ada sewa yang jatuh tempo dalam 7 hari ke depan.
                </div>
              @endif
            </div>
          </div>

          <!-- B. Booking Baru Menunggu Konfirmasi -->
          <div class="col-12 col-lg-5">
            <div class="dash-card-urgent h-100 p-3 p-sm-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-inbox-fill" style="color: var(--dash-accent-urgent);"></i>
                  <h3 class="fs-6 fw-bold mb-0 text-dark">Booking Baru (Pending)</h3>
                </div>
                <span class="fs-8 text-muted">{{ $pendingBookingsCount }} menunggu</span>
              </div>

              @if($pendingBookingsCount > 0)
                <div class="table-responsive">
                  <table class="dash-table">
                    <thead>
                      <tr>
                        <th>Pemesan</th>
                        <th>Unit Kamar</th>
                        <th>Biaya</th>
                        <th class="text-end">Tindakan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($pendingBookings as $booking)
                        <tr>
                          <td>
                            <div class="fw-semibold text-dark">{{ $booking->name }}</div>
                            <span class="fs-8 text-muted">{{ $booking->created_at ? $booking->created_at->diffForHumans() : 'Baru saja' }}</span>
                          </td>
                          <td>
                            <div class="fw-medium">{{ $booking->productKamarKosan->room ?? 'Unit' }}</div>
                            <span class="fs-8 text-muted">{{ $booking->productKamarKosan->productKosan->title ?? 'SCL' }}</span>
                          </td>
                          <td>
                            <span class="fw-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                            <div class="fs-8 text-muted">{{ ucfirst($booking->payment_method) }}</div>
                          </td>
                          <td class="text-end">
                            <a href="{{ route('admin.booking.index') }}" class="dash-btn-urgent" title="Tinjau dan verifikasi booking">
                              <span>Tinjau</span>
                              <i class="bi bi-chevron-right"></i>
                            </a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="p-3 text-center text-muted fs-8">
                  Semua reservasi telah diverifikasi. Tidak ada booking pending saat ini.
                </div>
              @endif
            </div>
          </div>
        </div>
      @else
        <!-- State Tenang Jika Tidak Ada Hal Mendesak -->
        <div class="dash-card p-4 text-center">
          <div class="d-inline-flex align-items-center justify-content-center mb-2" style="color: var(--dash-accent-normal);">
            <i class="bi bi-check2-circle fs-3"></i>
          </div>
          <h3 class="fs-6 fw-bold mb-1 text-dark">Semua Operasional Terkendali</h3>
          <p class="fs-7 text-muted mb-0" style="max-width: 600px; margin: 0 auto;">
            Tidak ada pembayaran sewa jatuh tempo ataupun booking baru yang tertunda pagi ini. Seluruh unit kamar dan administrasi penyewa dalam status normal.
          </p>
        </div>
      @endif
    </div>

    <!-- 3. RINGKASAN ANGKA OPERASIONAL (Tenang, Scannable, Tanpa Warna Pelangi) -->
    <div class="mb-4">
      <div class="mb-2">
        <h2 class="dash-heading-serif h5 mb-0 text-dark">Ringkasan Operasional</h2>
        <span class="fs-8 text-muted">Kapasitas hunian dan akumulasi pendapatan resmi.</span>
      </div>

      <div class="row g-3">
        <!-- Metrik 1: Okupansi Keseluruhan -->
        <div class="col-6 col-lg-3">
          <div class="dash-card p-3 p-sm-4 h-100">
            <span class="fs-8 text-muted d-block mb-1">Tingkat Okupansi</span>
            <div class="dash-heading-serif h3 mb-2 text-dark">{{ $occupancyRate }}%</div>
            <div class="progress-thin mb-2">
              <div class="progress-bar-normal" style="width: {{ min(100, $occupancyRate) }}%;"></div>
            </div>
            <span class="fs-8 text-secondary">
              {{ $occupiedKamar }} dari {{ $totalKamar }} unit terisi
            </span>
          </div>
        </div>

        <!-- Metrik 2: Kamar Kosong / Siap Huni -->
        <div class="col-6 col-lg-3">
          <div class="dash-card p-3 p-sm-4 h-100">
            <span class="fs-8 text-muted d-block mb-1">Kamar Siap Huni</span>
            <div class="dash-heading-serif h3 mb-2 text-dark">{{ $availableKamar }} unit</div>
            <div class="fs-8 text-secondary mt-auto">
              Tersedia untuk disewakan
            </div>
          </div>
        </div>

        <!-- Metrik 3: Pendapatan Bulan Ini -->
        <div class="col-6 col-lg-3">
          <div class="dash-card p-3 p-sm-4 h-100">
            <span class="fs-8 text-muted d-block mb-1">Pendapatan Bulan Ini</span>
            <div class="dash-heading-serif h3 mb-2 text-dark">
              Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}
            </div>
            <div class="fs-8 text-secondary mt-auto">
              Bulan {{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}
            </div>
          </div>
        </div>

        <!-- Metrik 4: Total Properti Kosan Cabang -->
        <div class="col-6 col-lg-3">
          <div class="dash-card p-3 p-sm-4 h-100">
            <span class="fs-8 text-muted d-block mb-1">Cabang Properti</span>
            <div class="dash-heading-serif h3 mb-2 text-dark">{{ $totalKosan }} lokasi</div>
            <div class="fs-8 text-secondary mt-auto">
              Aktif dalam operasional SCL
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. DAFTAR PROPERTI CABANG (Tabel Performa Bersih) -->
    <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h2 class="dash-heading-serif h5 mb-0 text-dark">Daftar Properti Cabang</h2>
          <span class="fs-8 text-muted">Kapasitas hunian dan performa unit per cabang kos.</span>
        </div>
        <a href="{{ route('admin.product.kosan.index') }}" class="dash-btn-secondary fs-8">
          <span>Kelola Semua Properti</span>
          <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <div class="dash-card overflow-hidden">
        <div class="table-responsive">
          <table class="dash-table">
            <thead>
              <tr>
                <th>Nama Cabang Properti</th>
                <th>Wilayah</th>
                <th>Total Kamar</th>
                <th>Kamar Terisi</th>
                <th>Kamar Siap Huni</th>
                <th style="min-width: 140px;">Tingkat Okupansi</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($propertiesOverview as $prop)
                <tr>
                  <td>
                    <div class="fw-semibold text-dark">{{ $prop['title'] }}</div>
                    <span class="fs-8 text-muted">ID Cabang #{{ $prop['id'] }}</span>
                  </td>
                  <td>
                    <span class="text-secondary">{{ $prop['wilayah'] }}</span>
                  </td>
                  <td>
                    <span class="fw-medium">{{ $prop['rooms_count'] }} unit</span>
                  </td>
                  <td>
                    <span class="fw-medium text-dark">{{ $prop['occupied_count'] }} unit</span>
                  </td>
                  <td>
                    @if($prop['available_count'] > 0)
                      <span class="fw-semibold" style="color: var(--dash-accent-normal);">
                        {{ $prop['available_count'] }} unit kosong
                      </span>
                    @else
                      <span class="fs-8 text-muted">Penuh</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <div class="progress-thin flex-grow-1">
                        <div class="progress-bar-normal" style="width: {{ $prop['occupancy_rate'] }}%;"></div>
                      </div>
                      <span class="fs-8 fw-semibold text-dark">{{ $prop['occupancy_rate'] }}%</span>
                    </div>
                  </td>
                  <td class="text-end">
                    <div class="d-inline-flex gap-1">
                      <a href="{{ route('admin.product.kosan.kamar.index', $prop['id']) }}" class="dash-btn-secondary" title="Buka daftar kamar kos ini">
                        <i class="bi bi-door-open"></i>
                        <span>Kamar</span>
                      </a>
                      <a href="{{ route('admin.product.kosan.index') }}" class="dash-btn-secondary" title="Kelola data properti">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted fs-8">
                    Belum ada data properti kosan yang terdaftar.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection
