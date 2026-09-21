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
    --dash-navy: #0f172a;
    --dash-navy-subtle: #1e293b;
    --dash-teal: #0f766e;
    --dash-teal-light: #f0fdfa;
    --dash-border: #e2e8f0;
    --dash-bg-subtle: #f8fafc;
    --dash-card-bg: #ffffff;
    --dash-text-main: #0f172a;
    --dash-text-muted: #64748b;
    --dash-radius: 8px;
    --dash-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04);
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

  .dash-btn-primary {
    background-color: var(--dash-navy);
    color: #ffffff;
    border: 1px solid var(--dash-navy);
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
    background-color: var(--dash-navy-subtle);
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

  .dash-action-link {
    color: var(--dash-teal);
    font-size: 0.8125rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    white-space: nowrap;
    transition: color 0.15s ease;
  }
  .dash-action-link:hover {
    color: #115e59;
    text-decoration: underline;
  }

  .dash-alert-indicator {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
  }
  .bg-amber-subtle { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
  .bg-danger-subtle { background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
  .bg-warning-subtle { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
  .bg-blue-subtle { background-color: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }

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
    height: 5px;
    background-color: #e2e8f0;
    border-radius: 9999px;
    overflow: hidden;
  }
  .progress-bar-normal {
    background-color: var(--dash-teal);
    height: 100%;
    border-radius: 9999px;
  }

  /* Compact Timeline */
  .dash-timeline {
    position: relative;
    padding-left: 1.25rem;
  }
  .dash-timeline::before {
    content: '';
    position: absolute;
    top: 8px;
    bottom: 8px;
    left: 4px;
    width: 1.5px;
    background-color: var(--dash-border);
  }
  .dash-timeline-item {
    position: relative;
    margin-bottom: 1.15rem;
  }
  .dash-timeline-item:last-child {
    margin-bottom: 0;
  }
  .dash-timeline-dot {
    position: absolute;
    left: -1.25rem;
    top: 5px;
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background-color: var(--dash-teal);
    border: 2px solid #ffffff;
    box-shadow: 0 0 0 1px var(--dash-border);
  }
  .dash-timeline-time {
    font-size: 0.72rem;
    color: var(--dash-text-muted);
    font-weight: 500;
    margin-bottom: 0.1rem;
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

    <!-- 2. PERLU TINDAKAN HARI INI (Dynamic Operational Alerts) -->
    <div class="mb-4">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="d-flex align-items-center gap-2">
          <h2 class="dash-heading-serif h5 mb-0 text-dark">Perlu Tindakan Hari Ini</h2>
          @if(!empty($operationalAlerts) && count($operationalAlerts) > 0)
            <span class="badge" style="background-color: rgba(15, 118, 110, 0.08); color: var(--dash-teal); border: 1px solid rgba(15, 118, 110, 0.2); font-weight: 600; font-size: 0.75rem; border-radius: var(--dash-radius);">
              {{ count($operationalAlerts) }} perhatian operasional
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

      @if(!empty($operationalAlerts) && count($operationalAlerts) > 0)
        <!-- Dynamic Concise Operational Alerts -->
        <div class="d-flex flex-column gap-2.5">
          @foreach($operationalAlerts as $alert)
            <div class="dash-card p-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
              <div class="d-flex align-items-center gap-3">
                <div class="dash-alert-indicator {{ $alert['badge_class'] ?? 'bg-amber-subtle' }}">
                  <i class="bi {{ $alert['icon'] ?? 'bi-info-circle' }}"></i>
                </div>
                <div>
                  <h3 class="fs-7 fw-bold text-dark mb-0.5">{{ $alert['title'] }}</h3>
                  <p class="fs-8 text-muted mb-0">{{ $alert['subtitle'] }}</p>
                </div>
              </div>
              <div class="align-self-end align-self-sm-center">
                <a href="{{ $alert['action_url'] }}" class="dash-action-link">
                  <span>{{ $alert['action_label'] }}</span>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <!-- State Tenang Jika Tidak Ada Hal Mendesak -->
        <div class="dash-card p-4 text-center">
          <div class="d-inline-flex align-items-center justify-content-center mb-2" style="color: var(--dash-teal);">
            <i class="bi bi-check2-circle fs-3"></i>
          </div>
          <h3 class="fs-6 fw-bold mb-1 text-dark">✓ Semua Operasional Terkendali</h3>
          <p class="fs-7 text-muted mb-0" style="max-width: 600px; margin: 0 auto;">
            Tidak ada pembayaran sewa jatuh tempo ataupun booking baru yang tertunda pagi ini.
          </p>
        </div>
      @endif
    </div>

    <!-- 3. RINGKASAN OPERASIONAL (4 Cards Kompak & Berhierarki Jelas) -->
    <div class="mb-4">
      <div class="mb-2">
        <h2 class="dash-heading-serif h5 mb-0 text-dark">Ringkasan Operasional</h2>
        <span class="fs-8 text-muted">Kapasitas hunian dan akumulasi pendapatan resmi.</span>
      </div>

      <div class="row g-3">
        <!-- Metrik 1: Tingkat Okupansi -->
        <div class="col-6 col-lg-3">
          <div class="dash-card p-3 p-sm-4 h-100 d-flex flex-column">
            <span class="fs-8 text-muted d-block mb-1">Tingkat Okupansi</span>
            <div class="dash-heading-serif h3 mb-2 text-dark">{{ $occupancyRate }}%</div>
            <div class="progress-thin mb-2">
              <div class="progress-bar-normal" style="width: {{ min(100, $occupancyRate) }}%;"></div>
            </div>
            <span class="fs-8 text-secondary mt-auto">
              {{ $occupiedKamar }} dari {{ $totalKamar }} unit terisi
            </span>
          </div>
        </div>

        <!-- Metrik 2: Kamar Siap Huni -->
        <div class="col-6 col-lg-3">
          <div class="dash-card p-3 p-sm-4 h-100 d-flex flex-column">
            <span class="fs-8 text-muted d-block mb-1">Kamar Siap Huni</span>
            <div class="dash-heading-serif h3 mb-2 text-dark">{{ $availableKamar }} unit</div>
            <span class="fs-8 text-secondary mt-auto">
              Tersedia untuk disewakan
            </span>
          </div>
        </div>

        <!-- Metrik 3: Pendapatan Bulan Ini -->
        <div class="col-6 col-lg-3">
          <div class="dash-card p-3 p-sm-4 h-100 d-flex flex-column">
            <span class="fs-8 text-muted d-block mb-1">Pendapatan Bulan Ini</span>
            <div class="dash-heading-serif h3 mb-2 text-dark">
              Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}
            </div>
            <span class="fs-8 text-secondary mt-auto">
              {{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}
            </span>
          </div>
        </div>

        <!-- Metrik 4: Cabang Properti -->
        <div class="col-6 col-lg-3">
          <div class="dash-card p-3 p-sm-4 h-100 d-flex flex-column">
            <span class="fs-8 text-muted d-block mb-1">Cabang Properti</span>
            <div class="dash-heading-serif h3 mb-2 text-dark">{{ $totalKosan }} lokasi</div>
            <span class="fs-8 text-secondary mt-auto">
              Aktif dalam operasional SCL
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. REVENUE TREND (6 BULAN TERAKHIR) & AKTIVITAS TERBARU -->
    <div class="row g-3 mb-4">
      <!-- A. Pendapatan 6 Bulan Terakhir -->
      <div class="col-12 col-lg-7 col-xl-8">
        <div class="dash-card p-3 p-sm-4 h-100 d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h2 class="dash-heading-serif h5 mb-0 text-dark">Pendapatan 6 Bulan Terakhir</h2>
              <span class="fs-8 text-muted">Tren akumulasi penerimaan sewa kamar per bulan</span>
            </div>
            <span class="fs-8 fw-semibold text-secondary d-none d-sm-inline">
              Rp (Juta)
            </span>
          </div>
          <div class="flex-grow-1" style="min-height: 220px; position: relative;">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>
      </div>

      <!-- B. Aktivitas Terbaru -->
      <div class="col-12 col-lg-5 col-xl-4">
        <div class="dash-card p-3 p-sm-4 h-100 d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h2 class="dash-heading-serif h5 mb-0 text-dark">Aktivitas Terbaru</h2>
              <span class="fs-8 text-muted">Perkembangan operasional terkini</span>
            </div>
            <i class="bi bi-activity text-muted fs-7"></i>
          </div>

          <div class="dash-timeline flex-grow-1 mt-1">
            @foreach($recentActivities as $act)
              <div class="dash-timeline-item">
                <div class="dash-timeline-dot"></div>
                <div class="dash-timeline-time">{{ $act['time'] }}</div>
                <div class="fs-8 fw-semibold text-dark">{{ $act['title'] }}</div>
                <div class="fs-8 text-muted">{{ $act['property'] }}</div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- 5. DAFTAR PROPERTI CABANG (Tabel Performa Bersih) -->
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
                      <span class="fw-semibold" style="color: var(--dash-teal);">
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
                    <div class="d-inline-flex gap-1.5">
                      <a href="{{ route('admin.product.kosan.kamar.index', $prop['id']) }}" class="dash-btn-primary py-1 px-2.5" title="Buka daftar kamar kos ini">
                        <i class="bi bi-door-open"></i>
                        <span>Kelola Kamar</span>
                      </a>
                      <a href="{{ route('admin.product.kosan.index') }}" class="dash-btn-secondary py-1 px-2.5" title="Edit data properti">
                        <i class="bi bi-pencil"></i>
                        <span>Edit</span>
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

<!-- Chart.js for Minimalist Revenue Line Chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    const labels = {!! json_encode(array_column($revenueTrend, 'month')) !!};
    const values = {!! json_encode(array_column($revenueTrend, 'revenue')) !!};

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Pendapatan',
          data: values,
          borderColor: '#0f766e',
          borderWidth: 2,
          backgroundColor: 'rgba(15, 118, 110, 0.04)',
          fill: true,
          tension: 0.3,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: '#0f766e',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
          pointHoverBackgroundColor: '#0f766e',
          pointHoverBorderColor: '#ffffff',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#0f172a',
            titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 600 },
            bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
            padding: 10,
            cornerRadius: 6,
            displayColors: false,
            callbacks: {
              label: function(context) {
                return 'Rp ' + Number(context.raw).toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          x: {
            grid: { display: false },
            border: { color: '#e2e8f0' },
            ticks: {
              color: '#64748b',
              font: { family: 'Plus Jakarta Sans', size: 11 }
            }
          },
          y: {
            grid: {
              color: '#f1f5f9',
              drawBorder: false
            },
            border: { dash: [4, 4], color: 'transparent' },
            ticks: {
              color: '#64748b',
              font: { family: 'Plus Jakarta Sans', size: 11 },
              callback: function(value) {
                return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
              }
            }
          }
        }
      }
    });
  });
</script>

@endsection
