@extends('backend.dashboard.main')

@section('content')

@php
    $hour = (int)date('H');
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $sixMonthTotal = collect($revenueTrend ?? [])->sum('revenue');
@endphp

<div class="app-content-header">
  <div class="container-fluid">
    <div class="page-header-box">
      <div>
        <div class="d-flex align-items-center flex-wrap gap-2 mb-1.5">
          <span class="fs-8 text-muted d-inline-flex align-items-center gap-1.5">
            <i class="bi bi-calendar3 text-secondary"></i>
            <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
          </span>
          <span class="text-muted opacity-50">&bull;</span>
          <span class="badge badge-subtle-secondary">
            {{ Auth::user()->isSuperAdmin() ? 'Semua Cabang' : 'Cabang Ditugaskan' }}
          </span>
          @if(!empty($operationalAlerts) && count($operationalAlerts) > 0)
            <span class="badge badge-subtle-warning">
              <i class="bi bi-exclamation-circle me-1"></i>{{ count($operationalAlerts) }} Perlu Tindakan
            </span>
          @else
            <span class="badge badge-subtle-success">
              <i class="bi bi-check2-circle me-1"></i>Operasional Normal
            </span>
          @endif
        </div>
        <h3 class="mb-0">{{ $greeting }}, {{ Auth::user()->name ?? 'Pengelola' }}</h3>
        <p class="text-muted">Ringkasan kapasitas hunian, tren pendapatan sewa, dan aktivitas operasional cabang.</p>
      </div>
      <div class="page-header-actions">
        <a href="{{ route('admin.product.kosan.index') }}" class="btn btn-light border px-3">
          <i class="bi bi-buildings"></i>
          <span>Kelola Properti</span>
        </a>
        <a href="{{ route('admin.booking.index') }}" class="btn btn-primary px-3">
          <i class="bi bi-calendar-check"></i>
          <span>Kelola Booking</span>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="app-content">
  <div class="container-fluid">

    <!-- 1. PERLU TINDAKAN HARI INI (Hanya tampil jika ada item mendesak) -->
    @if(!empty($operationalAlerts) && count($operationalAlerts) > 0)
      <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center gap-2">
            <span class="fs-8 fw-bold text-uppercase text-muted" style="letter-spacing: 0.04em;">Perhatian Operasional</span>
            <span class="badge badge-subtle-warning">{{ count($operationalAlerts) }} item</span>
          </div>
          <span class="fs-8 text-muted d-none d-sm-inline">Prioritas tindak lanjut hari ini</span>
        </div>
        <div class="d-flex flex-column gap-2">
          @foreach($operationalAlerts as $alert)
            <div class="card p-3">
              <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2.5">
                <div class="d-flex align-items-center gap-2.5">
                  <div class="dash-alert-indicator {{ $alert['badge_class'] ?? 'bg-amber-subtle' }}">
                    <i class="bi {{ $alert['icon'] ?? 'bi-info-circle' }}"></i>
                  </div>
                  <div>
                    <div class="fs-7 fw-bold text-dark">{{ $alert['title'] }}</div>
                    <div class="fs-8 text-muted">{{ $alert['subtitle'] }}</div>
                  </div>
                </div>
                <a href="{{ $alert['action_url'] }}" class="btn btn-xs btn-light border align-self-end align-self-sm-center px-2.5 py-1.5">
                  <span>{{ $alert['action_label'] }}</span>
                  <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- 2. RINGKASAN STATISTIK UTAMA (4 KPI Cards) -->
    <div class="kpi-stat-grid">
      <!-- KPI 1: Tingkat Okupansi -->
      <div class="kpi-stat-card">
        <div class="kpi-stat-header">
          <span class="kpi-stat-label">Tingkat Okupansi</span>
          <span class="kpi-stat-icon">
            <i class="bi bi-pie-chart"></i>
          </span>
        </div>
        <div class="kpi-stat-value">{{ $occupancyRate }}%</div>
        <div class="progress-thin mb-2">
          <div class="progress-bar-normal" style="width: {{ min(100, $occupancyRate) }}%;"></div>
        </div>
        <div class="kpi-stat-meta">
          <span><strong class="text-dark">{{ $occupiedKamar }}</strong> dari {{ $totalKamar }} kamar terisi</span>
        </div>
      </div>

      <!-- KPI 2: Kamar Siap Huni -->
      <div class="kpi-stat-card">
        <div class="kpi-stat-header">
          <span class="kpi-stat-label">Kamar Siap Huni</span>
          <span class="kpi-stat-icon">
            <i class="bi bi-door-open"></i>
          </span>
        </div>
        <div class="kpi-stat-value">{{ $availableKamar }} <span class="fs-6 fw-medium text-muted">unit</span></div>
        <div class="kpi-stat-meta">
          @if($availableKamar > 0)
            <span class="badge badge-subtle-success">Tersedia disewa</span>
          @else
            <span class="badge badge-subtle-secondary">Kapasitas penuh</span>
          @endif
        </div>
      </div>

      <!-- KPI 3: Pendapatan Bulan Ini -->
      <div class="kpi-stat-card">
        <div class="kpi-stat-header">
          <span class="kpi-stat-label">Pendapatan Bulan Ini</span>
          <span class="kpi-stat-icon">
            <i class="bi bi-wallet2"></i>
          </span>
        </div>
        <div class="kpi-stat-value">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</div>
        <div class="kpi-stat-meta">
          <span>Periode {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y') }}</span>
        </div>
      </div>

      <!-- KPI 4: Cabang Properti -->
      <div class="kpi-stat-card">
        <div class="kpi-stat-header">
          <span class="kpi-stat-label">Cabang Properti</span>
          <span class="kpi-stat-icon">
            <i class="bi bi-buildings"></i>
          </span>
        </div>
        <div class="kpi-stat-value">{{ $totalKosan }} <span class="fs-6 fw-medium text-muted">lokasi</span></div>
        <div class="kpi-stat-meta">
          <span>Total <strong class="text-dark">{{ $totalKamar }}</strong> unit kamar aktif</span>
        </div>
      </div>
    </div>

    <!-- 3. GRAFIK PENDAPATAN & AKTIVITAS TERBARU -->
    <div class="row g-3 mb-4">
      <!-- Grafik Pendapatan 6 Bulan Terakhir -->
      <div class="col-12 col-lg-8">
        <div class="card h-100">
          <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
              <h5 class="card-title mb-0">Pendapatan 6 Bulan Terakhir</h5>
              <span class="fs-8 text-muted">Tren akumulasi penerimaan sewa kamar terkonfirmasi</span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="badge badge-subtle-secondary tabular-nums">
                Total 6 Bln: Rp {{ number_format($sixMonthTotal, 0, ',', '.') }}
              </span>
            </div>
          </div>
          <div class="card-body d-flex flex-column justify-content-center">
            <div style="min-height: 240px; position: relative; width: 100%;">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Aktivitas Operasional Terbaru -->
      <div class="col-12 col-lg-4">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div>
              <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
              <span class="fs-8 text-muted">Log transaksi & pembaruan unit</span>
            </div>
            <a href="{{ route('admin.booking.index') }}" class="fs-8 fw-semibold text-primary text-decoration-none">
              Semua
            </a>
          </div>
          <div class="card-body">
            <div class="dash-timeline">
              @forelse($recentActivities as $act)
                <div class="dash-timeline-item">
                  <div class="dash-timeline-dot"></div>
                  <div class="dash-timeline-time">{{ $act['time'] }}</div>
                  <div class="fs-8 fw-semibold text-dark">{{ $act['title'] }}</div>
                  <div class="fs-8 text-muted">{{ $act['property'] }}</div>
                </div>
              @empty
                <div class="text-center py-4 text-muted fs-8">
                  <i class="bi bi-clock-history fs-3 d-block mb-1.5 text-secondary opacity-50"></i>
                  Belum ada aktivitas operasional tercatat.
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. DAFTAR PROPERTI CABANG (Tabel Ringkasan Kapasitas) -->
    <div class="card mb-2">
      <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h5 class="card-title mb-0">Daftar Properti Cabang</h5>
          <span class="fs-8 text-muted">Kapasitas hunian dan ketersediaan kamar per lokasi kos</span>
        </div>
        <a href="{{ route('admin.product.kosan.index') }}" class="btn btn-xs btn-light border px-2.5 py-1.5">
          <span>Kelola Semua Properti</span>
          <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0 table-min-md">
            <thead>
              <tr>
                <th class="ps-4">Nama Cabang Properti</th>
                <th>Wilayah</th>
                <th>Total Unit</th>
                <th>Terisi</th>
                <th>Ketersediaan</th>
                <th style="min-width: 150px;">Okupansi</th>
                <th class="text-end pe-4">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($propertiesOverview as $prop)
                <tr>
                  <td class="ps-4">
                    <div class="fw-semibold text-dark">{{ $prop['title'] }}</div>
                    <span class="fs-9 text-muted">ID Cabang #{{ $prop['id'] }}</span>
                  </td>
                  <td>
                    <span class="text-secondary fs-8">{{ $prop['wilayah'] }}</span>
                  </td>
                  <td class="tabular-nums">
                    <span class="fw-medium">{{ $prop['rooms_count'] }} kamar</span>
                  </td>
                  <td class="tabular-nums">
                    <span class="fw-medium text-dark">{{ $prop['occupied_count'] }} kamar</span>
                  </td>
                  <td>
                    @if($prop['available_count'] > 0)
                      <span class="badge badge-subtle-success">
                        {{ $prop['available_count'] }} unit kosong
                      </span>
                    @else
                      <span class="badge badge-subtle-secondary">Penuh</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <div class="progress-thin flex-grow-1">
                        <div class="progress-bar-normal" style="width: {{ $prop['occupancy_rate'] }}%;"></div>
                      </div>
                      <span class="fs-8 fw-semibold text-dark tabular-nums" style="min-width: 34px;">{{ $prop['occupancy_rate'] }}%</span>
                    </div>
                  </td>
                  <td class="text-end pe-4">
                    <div class="action-btn-group">
                      <a href="{{ route('admin.product.kosan.kamar.index', $prop['id']) }}" class="btn btn-xs btn-primary px-2.5 py-1" title="Buka daftar kamar kos ini">
                        <i class="bi bi-door-open"></i>
                        <span>Kelola Kamar</span>
                      </a>
                      <a href="{{ route('admin.product.kosan.index') }}" class="btn btn-xs btn-light border px-2 py-1" title="Kelola data properti">
                        <i class="bi bi-sliders"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <div class="empty-state-box">
                      <div class="empty-state-icon">
                        <i class="bi bi-buildings"></i>
                      </div>
                      <h6>Belum Ada Properti Terdaftar</h6>
                      <p>Tambahkan cabang properti kosan pertama Anda untuk mulai memantau kapasitas kamar.</p>
                    </div>
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
          backgroundColor: 'rgba(15, 118, 110, 0.05)',
          fill: true,
          tension: 0.3,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: '#0f766e',
          pointBorderWidth: 2,
          pointRadius: 3.5,
          pointHoverRadius: 5.5,
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
            beginAtZero: true,
            grid: {
              color: '#f1f5f9',
              drawBorder: false
            },
            border: { dash: [4, 4], color: 'transparent' },
            ticks: {
              color: '#64748b',
              font: { family: 'Plus Jakarta Sans', size: 11 },
              callback: function(value) {
                if (value >= 1000000) {
                  return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                }
                if (value >= 1000) {
                  return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                }
                return 'Rp ' + value;
              }
            }
          }
        }
      }
    });
  });
</script>

@endsection
