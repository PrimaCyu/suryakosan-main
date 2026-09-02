<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NemuKOS - Temukan Kamar Kost Impianmu</title>
  <link rel="stylesheet" href="{{ asset('build/assets/app-D-OnOQL0.css') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <link rel="icon" href="{{ asset('logo.png') }}">


  <style>
    /* Keyframe animasi slider horizontal tak terbatas KHUSUS REVIEWS */
    @keyframes infinite-scroll {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    .animate-infinite-scroll {
      display: flex;
      width: max-content;
      animation: infinite-scroll 30s linear infinite;
    }
    .animate-infinite-scroll:hover {
      animation-play-state: paused;
    }

    /* Utilitas untuk menyembunyikan scrollbar pada container kos yang di-slide manual */
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    /* Initial state untuk efek animasi reveal saat halaman di-scroll */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }

    /* Animasi Pop-up Menu Need Help */
    .help-menu-enter {
      opacity: 0;
      transform: translateY(20px) scale(0.95);
      pointer-events: none;
    }
    .help-menu-active {
      opacity: 1;
      transform: translateY(0) scale(1);
      pointer-events: auto;
    }
  </style>

</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

    @if (session('success'))
      <!-- MODAL POPUP SUCCESS BOOKING -->
      <div id="flash-success-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[999] flex items-center justify-center p-4 transition-all">
        <div class="bg-white w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl relative space-y-5 border border-slate-100 animate-in fade-in zoom-in duration-200">

          <!-- Tombol Close Modal -->
          <button type="button" onclick="closeSuccessModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-200 hover:text-slate-700 transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
          </button>

          <div class="space-y-1 text-center">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3">
              <i class="fa-solid fa-circle-check text-3xl"></i>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900">Booking Berhasil!</h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ session('success') }}</p>
          </div>

          <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 flex items-start gap-3 text-amber-900">
            <i class="fa-regular fa-clock text-lg text-amber-600 mt-0.5 shrink-0"></i>
            <div class="text-xs space-y-1">
              <span class="font-bold block">Mohon Tunggu Konfirmasi (1 x 24 Jam)</span>
              <p class="text-[11px] text-amber-800 leading-relaxed">
                Tim kami akan memverifikasi pengajuan sewa Anda. Konfirmasi akan dikirim melalui <strong>WhatsApp</strong> atau <strong>Email</strong>.
              </p>
            </div>
          </div>

          <button type="button" onclick="closeSuccessModal()" class="w-full py-3.5 bg-cyan-600 hover:bg-cyan-700 text-white font-extrabold rounded-2xl transition-all shadow-md active:scale-95 text-xs sm:text-sm">
            Mengerti & Tutup
          </button>

        </div>
      </div>
    @endif

    <!-- 1. BAR ANIMASI LOADING HALAMAN (TOP) -->
    <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-cyan-500 z-[100] transition-all duration-500 ease-out"></div>

    @include('frontend.navbar')

    <!-- 3. HERO SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6 reveal">
            <span class="inline-block px-4 py-1.5 bg-cyan-100 text-cyan-700 rounded-full text-xs font-semibold tracking-wide uppercase shadow-sm">
            ★ Premium Living Experience
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight">
            Temukan Kamar <br><span class="text-cyan-600">Kost Impianmu.</span>
            </h1>
            <p class="text-slate-600 text-base sm:text-lg max-w-md leading-relaxed">
            Hunian nyaman, strategis, dan fasilitas lengkap untuk mendukung produktivitas harianmu.
            </p>

            <!-- Search Bar komponen pengganti tombol Cari Kos -->
            <form action="{{ route('kosan.index') }}" method="GET" class="flex items-center bg-white border border-slate-200 rounded-full p-2 shadow-lg hover:shadow-xl transition-shadow max-w-md">
                <div class="pl-4 pr-2 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" placeholder="Cari nama kos..." class="w-full bg-transparent text-slate-800 placeholder-slate-400 text-sm focus:outline-none py-2" required>
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-6 py-2.5 rounded-full text-sm transition-all shadow-md shrink-0">
                    Cari
                </button>
            </form>
        </div>

        <!-- Hero Gallery Grid -->
        <div class="grid grid-cols-2 gap-4 reveal">
            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80" alt="Kamar Utama" loading="lazy" class="rounded-3xl shadow-xl w-full h-80 lg:h-96 object-cover hover:scale-[1.02] transition-transform duration-300">
            <div class="space-y-4">
            <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80" alt="Ruang Tamu" loading="lazy" class="rounded-3xl shadow-lg w-full h-36 lg:h-44 object-cover hover:scale-[1.02] transition-transform duration-300">
            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=600&q=80" alt="Interior Kos" loading="lazy" class="rounded-3xl shadow-lg w-full h-36 lg:h-44 object-cover hover:scale-[1.02] transition-transform duration-300">
            </div>
        </div>
        </div>
    </section>

    <!-- 4. SECTION BARU: MENGAPA MEMILIH NEMUKOS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 reveal">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
        <!-- Sisi Kiri: Deskripsi Keunggulan -->
        <div class="lg:col-span-6 space-y-6">
            <span class="inline-block px-3.5 py-1.5 bg-cyan-100 text-cyan-700 rounded-full text-xs font-semibold tracking-wide">
            • Mengapa Memilih Kami
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
            Solusi Pencarian Kos Modern & Terpercaya
            </h2>
            <div class="space-y-4 pt-2">
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0 mt-0.5">
                <i class="fa-solid fa-check text-xs"></i>
                </div>
                <div>
                <h4 class="font-bold text-slate-900 text-sm">Informasi Terverifikasi & Akurat</h4>
                <p class="text-slate-500 text-xs mt-0.5">Seluruh data foto, harga, dan fasilitas kos divalidasi langsung untuk menghindari ketidaksesuaian.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0 mt-0.5">
                <i class="fa-solid fa-check text-xs"></i>
                </div>
                <div>
                <h4 class="font-bold text-slate-900 text-sm">Pencarian Cepat & Ringkas</h4>
                <p class="text-slate-500 text-xs mt-0.5">Sistem navigasi interaktif memudahkan pemesan menemukan hunian yang sesuai kriteria lokasi dan budget.</p>
                </div>
            </div>
            </div>
        </div>

        <!-- Sisi Kanan: Grid Statistik Keunggulan NemuKos -->
        <div class="lg:col-span-6 grid grid-cols-2 gap-4 sm:gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-building-user text-lg"></i>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">500+</div>
            <p class="text-slate-500 text-xs font-medium mt-1">Pilihan Kos Terdaftar</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-map-location-dot text-lg"></i>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">15+</div>
            <p class="text-slate-500 text-xs font-medium mt-1">Area Strategis</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">100%</div>
            <p class="text-slate-500 text-xs font-medium mt-1">Transaksi Transparan</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-face-smile text-lg"></i>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">98%</div>
            <p class="text-slate-500 text-xs font-medium mt-1">Tingkat Kepuasan</p>
            </div>
        </div>
        </div>
    </section>

    <!-- 5. REKOMENDASI KOS TERPOPULER (SLIDE MANUAL USER) -->
    <section class="bg-cyan-100/70 py-16 overflow-hidden reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Rekomendasi Kos Terpopuler</h2>
            <p class="text-slate-600 text-sm mt-1">Pilihan terbaik berdasarkan fasilitas dan ulasan penghuni (Geser kanan/kiri).</p>
            </div>
            <!-- Filter Wilayah Buttons -->
            <div class="flex flex-wrap gap-2" id="filter-buttons">
                <button data-filter="semua" class="filter-btn active px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-full shadow-md transition-all">Semua Wilayah</button>
                @php
                    $wilayahList = $kamarList->pluck('productKosan.wilayah')->filter()->unique();
                @endphp
                @foreach($wilayahList as $w)
                    <button data-filter="{{ Str::slug($w) }}" class="filter-btn px-4 py-2 bg-white text-slate-700 hover:bg-slate-900 hover:text-white text-xs font-semibold rounded-full shadow-md transition-all">
                        {{ $w }}
                    </button>
                @endforeach
            </div>
        </div>
        </div>

        <!-- Container Card Slider Manual (Horizontal Scroll) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex overflow-x-auto space-x-6 pb-6 pt-2 no-scrollbar scroll-smooth cursor-grab active:cursor-grabbing" id="kos-card-container">

            @php
                $iconMap = [
                    // Fasilitas Kamar Admin
                    'AC' => 'fa-snowflake',
                    'Kamar Mandi Dalam' => 'fa-bath',
                    'Water Heater / Air Hangat' => 'fa-temperature-arrow-up',
                    'Water Heater' => 'fa-temperature-arrow-up',
                    'Kasur Springbed' => 'fa-bed',
                    'Kasur' => 'fa-bed',
                    'Lemari Pakaian' => 'fa-door-closed',
                    'Lemari' => 'fa-door-closed',
                    'Meja & Kursi Belajar' => 'fa-chair',
                    'Meja' => 'fa-table',
                    'TV / Smart TV' => 'fa-tv',
                    'Wastafel' => 'fa-sink',
                    'Balkon Kamar' => 'fa-person-through-window',
                    'Jendela / Ventilasi Bagus' => 'fa-wind',
                    'Kipas Angin' => 'fa-fan',
                    'Kulkas Mini' => 'fa-box',

                    // Fasilitas Properti Kos Admin
                    'Wi-Fi / Internet' => 'fa-wifi',
                    'Parkir Mobil' => 'fa-car',
                    'Parkir Motor' => 'fa-motorcycle',
                    'Dapur Bersama' => 'fa-kitchen-set',
                    'CCTV 24 Jam' => 'fa-video',
                    'Keamanan / Satpam' => 'fa-user-shield',
                    'Ruang Tamu Bersama' => 'fa-couch',
                    'Ruang Jemur' => 'fa-shirt',
                    'Mesin Cuci Bersama' => 'fa-soap',
                    'Kulkas Bersama' => 'fa-box',
                    'Air Minum / Dispenser' => 'fa-glass-water',
                    'Penjaga Kos' => 'fa-user-clock',
                    'Listrik Gratis / Included' => 'fa-bolt',
                    'Bebas Jam Malam' => 'fa-key',
                    'Akses Kartu / Smart Lock' => 'fa-id-card',
                    'Balkon / Rooftop' => 'fa-building',
                    'Musholla' => 'fa-mosque',
                    'Gazebo / Area Santai' => 'fa-umbrella-beach'
                ];
            @endphp

            @foreach ($kamarList as $kamar)
                @php
                    // Ambil gambar kamar dulu, jika tidak ada fallback ke gambar kosan utama
                    $firstImg = $kamar->productKamarImageKosan->first();
                    if ($firstImg) {
                        $imgUrl = asset('storage/' . $firstImg->image);
                    } elseif ($kamar->productKosan && $kamar->productKosan->productImageKosan->first()) {
                        $imgUrl = asset('storage/' . $kamar->productKosan->productImageKosan->first()->image);
                    } else {
                        $imgUrl = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=600&q=80';
                    }

                    // Gabungkan fasilitas kamar & fasilitas kosan
                    $fasKamarArr = array_filter(array_map('trim', explode(',', $kamar->fasilitas ?? '')));
                    $fasKosanArr = array_filter(array_map('trim', explode(',', $kamar->productKosan->fasilitas ?? '')));
                    $fasilitasArr = array_unique(array_merge($fasKamarArr, $fasKosanArr));

                    // Ambil langsung harga kategori 'bulan' dari kamar ini
                    $monthlyPriceObj = $kamar->priceKamar->first(function($price) {
                        return strtolower($price->kategori) === 'bulan';
                    });
                    $monthlyPrice = $monthlyPriceObj ? $monthlyPriceObj->price : null;

                    $wilayahNama = $kamar->productKosan->wilayah ?? '-';
                    $kosanJudul = $kamar->productKosan->title ?? '';
                @endphp

                <!-- CARD KAMAR -->
                <div class="kos-card w-72 sm:w-80 bg-white rounded-2xl shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 shrink-0" data-wilayah="{{ Str::slug($wilayahNama) }}">
                    <img src="{{ $imgUrl }}" alt="{{ $kamar->room }}" loading="lazy" class="w-full h-48 object-cover rounded-t-2xl">
                    <div class="p-5">
                        <div class="text-[11px] font-semibold text-cyan-600 uppercase tracking-wider mb-1 truncate" title="{{ $kosanJudul }}">{{ $kosanJudul }}</div>
                        <h3 class="font-bold text-slate-900 text-lg truncate" title="{{ $kamar->room }}">{{ $kamar->room }}</h3>
                        <p class="text-xs text-slate-400 flex items-center gap-1 mt-1"><i class="fa-solid fa-location-dot text-rose-500"></i> {{ $wilayahNama }}</p>

                        <!-- Fasilitas dengan Icon (Maksimal 4) -->
                        <div class="flex flex-wrap items-center gap-1.5 my-3 text-[11px] text-cyan-600 font-medium min-h-[28px]">
                            @forelse(array_slice($fasilitasArr, 0, 4) as $fas)
                                @php
                                    $iconClass = $iconMap[$fas] ?? 'fa-check';
                                @endphp
                                <span class="bg-cyan-50 border border-cyan-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                                    <i class="fa-solid {{ $iconClass }}"></i> {{ $fas }}
                                </span>
                            @empty
                                <span class="text-slate-400 text-xs font-normal">Fasilitas standar</span>
                            @endforelse
                            @if(count($fasilitasArr) > 4)
                                <span class="text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded-md border border-slate-200">+{{ count($fasilitasArr) - 4 }}</span>
                            @endif
                        </div>

                        <!-- Harga Bulanan Kamar & View Stat -->
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <div>
                                @if($monthlyPrice)
                                    <span class="text-lg font-bold text-slate-900">Rp {{ number_format($monthlyPrice, 0, ',', '.') }}</span><span class="text-[10px] text-slate-400">/bulan</span>
                                @else
                                    <span class="text-sm font-semibold text-slate-500">Harga N/A</span>
                                @endif
                                <div class="text-[11px] text-slate-400 font-medium flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-eye text-cyan-600"></i> {{ number_format($kamar->views ?? 0, 0, ',', '.') }} x dilihat
                                </div>
                            </div>
                            <a href="{{ route('kamar.detail', $kamar->id) }}" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-700 flex items-center justify-center transition-all shadow-sm">
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        </div>
    </section>

    <!-- 6. NEWS & EVENTS SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 reveal">
        <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">News & Events</h2>
            <p class="text-slate-600 text-sm mt-1">Update terbaru seputar properti dan promo menarik.</p>
        </div>
        <a href="news.html" class="text-xs font-semibold text-slate-900 hover:text-cyan-600 flex items-center gap-1.5 transition-colors">
            Lihat Semua Artikel <i class="fa-solid fa-arrow-right"></i>
        </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80" alt="Artikel 1" loading="lazy" class="w-full h-44 object-cover">
            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-wider">Tips & Trik</span>
                <h3 class="font-bold text-slate-900 text-base mt-1 hover:text-cyan-600 transition-colors">Cara Hemat Listrik di Kamar Kos</h3>
                <p class="text-slate-500 text-xs mt-2 line-clamp-2">Pelajari langkah-langkah sederhana untuk menekan biaya listrik bulanan kosanmu.</p>
            </div>
            <a href="detail-news.html" class="inline-flex items-center gap-1.5 text-xs font-semibold text-cyan-600 hover:text-cyan-700 transition-colors">
                Baca Artikel <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&w=600&q=80" alt="Artikel 2" loading="lazy" class="w-full h-44 object-cover">
            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-wider">Gaya Hidup</span>
                <h3 class="font-bold text-slate-900 text-base mt-1 hover:text-cyan-600 transition-colors">Menu Masakan Praktis Anak Kos</h3>
                <p class="text-slate-500 text-xs mt-2 line-clamp-2">Resep masakan sehat dan murah yang bisa dibuat hanya dengan rice cooker.</p>
            </div>
            <a href="detail-news.html" class="inline-flex items-center gap-1.5 text-xs font-semibold text-cyan-600 hover:text-cyan-700 transition-colors">
                Baca Artikel <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80" alt="Artikel 3" loading="lazy" class="w-full h-44 object-cover">
            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-wider">Info Properti</span>
                <h3 class="font-bold text-slate-900 text-base mt-1 hover:text-cyan-600 transition-colors">Tren Co-Living di Jakarta 2026</h3>
                <p class="text-slate-500 text-xs mt-2 line-clamp-2">Mengapa konsep hunian bersama semakin diminati oleh generasi milenial.</p>
            </div>
            <a href="detail-news.html" class="inline-flex items-center gap-1.5 text-xs font-semibold text-cyan-600 hover:text-cyan-700 transition-colors">
                Baca Artikel <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
            </div>
        </div>
        </div>
    </section>

    <!-- 7. TESTIMONIAL / REVIEWS SECTION (TETAP SLIDE OTOMATIS INFINITE) -->
    <section class="bg-cyan-100/70 py-16 overflow-hidden reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-12">

        <!-- Headline Kiri -->
        <div class="lg:w-1/3 space-y-3 shrink-0 text-center lg:text-left">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
            Apa Kata Mereka <br class="hidden lg:block">Tentang Kami?
            </h2>
            <p class="text-slate-600 text-sm max-w-sm">
            Lebih dari 12rb penghuni merasa puas dengan fasilitas kami.
            </p>
        </div>

        <!-- Slider Cards Kanan (Otomatis) -->
        <div class="lg:w-2/3 overflow-hidden relative w-full">
            <div class="animate-infinite-scroll space-x-6 py-2">

            @forelse($testimonis as $t)
                @php
                    $profileImg = $t->image_profile ? asset('storage/' . $t->image_profile) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80';
                    $ratingVal = max(1, min(5, (int) $t->rating));
                @endphp
                <!-- REVIEW CARD DYNAMIC -->
                <div class="w-80 bg-white p-6 rounded-2xl shadow-xl shrink-0 space-y-4 border border-slate-100">
                    <div class="flex text-amber-400 text-xs space-x-1">
                        @for($i = 1; $i <= $ratingVal; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                        @for($j = $ratingVal + 1; $j <= 5; $j++)
                            <i class="fa-regular fa-star text-slate-300"></i>
                        @endfor
                    </div>
                    <p class="text-slate-600 text-xs leading-relaxed font-normal">
                        "{{ $t->review }}"
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <img src="{{ $profileImg }}" alt="{{ $t->name }}" loading="lazy" class="w-10 h-10 rounded-full object-cover shadow-sm">
                        <div>
                            <h4 class="font-bold text-slate-900 text-xs">{{ $t->name }}</h4>
                        </div>
                    </div>
                </div>
            @empty
                <!-- DEFAULT FALLBACK TESTIMONI -->
                <div class="w-80 bg-white p-6 rounded-2xl shadow-xl shrink-0 space-y-4 border border-slate-100">
                    <div class="flex text-amber-400 text-xs space-x-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-slate-600 text-xs leading-relaxed font-normal">
                        "Investasi di NemuKOS sangat menguntungkan. Fasilitasnya lengkap dan lokasinya sangat strategis."
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Siska Amelia" loading="lazy" class="w-10 h-10 rounded-full object-cover shadow-sm">
                        <div>
                            <h4 class="font-bold text-slate-900 text-xs">Siska Amelia</h4>
                        </div>
                    </div>
                </div>
            @endforelse

            </div>
        </div>

        </div>
    </section>

    <!-- detail-kamar- 8. FOOTER CONTAINER -->
    @include('frontend.footer')

    <!-- 9. NEED HELP CONTAINER (MEMANGGIL FILE EXTERNAL `need-help.html`) -->
    @include('frontend.need-help')


    <!-- 10. MODAL POP-UP SUCCESS BOOKING -->
    @if(session('success'))
        <div id="success-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[999] hidden items-center justify-center p-4 transition-all">
            <div class="bg-white w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl relative space-y-5 border border-slate-100 animate-in fade-in zoom-in duration-200">

            <!-- Tombol X: Mengarahkan pengguna kembali ke halaman index.html -->
            <button type="button" onclick="goToHome()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <div class="space-y-1">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                <i class="fa-solid fa-circle-check text-2xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900">{{ session('success') }}</h3>
                <p class="text-xs text-slate-400">Pengajuan sewa Anda telah berhasil dikirim ke sistem kami.</p>
            </div>

            <hr class="border-slate-100">

            <div class="p-4 bg-amber-50/80 rounded-2xl border border-amber-200/80 flex items-start gap-3 text-amber-900">
                <i class="fa-regular fa-clock text-base text-amber-600 mt-0.5 shrink-0"></i>
                <div class="text-xs space-y-1">
                <span class="font-bold block">Mohon Tunggu Konfirmasi (1 x 24 Jam)</span>
                <p class="text-[11px] text-amber-800 leading-relaxed">
                    Tim kami akan memverifikasi pesanan Anda. Balasan pesan konfirmasi akan dikirimkan melalui <strong>WhatsApp</strong> atau <strong>Email</strong> Anda.
                </p>
                </div>
            </div>

            </div>
        </div>
    @endif

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Function untuk mengaktifkan tombol toggle Need Help
        function initHelpToggle() {
        const helpToggleBtn = document.getElementById('help-toggle-btn');
        const helpMenu = document.getElementById('help-menu');
        const helpBtnIcon = document.getElementById('help-btn-icon');
        const helpBtnText = document.getElementById('help-btn-text');

        if (!helpToggleBtn || !helpMenu) return;

        let isHelpOpen = false;

        helpToggleBtn.addEventListener('click', () => {
            isHelpOpen = !isHelpOpen;
            if (isHelpOpen) {
            helpMenu.classList.remove('help-menu-enter');
            helpMenu.classList.add('help-menu-active');
            helpBtnIcon.className = 'fa-solid fa-xmark text-base';
            helpBtnText.textContent = 'Close';
            } else {
            helpMenu.classList.remove('help-menu-active');
            helpMenu.classList.add('help-menu-enter');
            helpBtnIcon.className = 'fa-solid fa-comment-dots text-base';
            helpBtnText.textContent = 'Need Help?';
            }
        });
        }

        // 2. LOADING BAR ANIMATION
        window.addEventListener('load', () => {
        const loader = document.getElementById('page-loader');
        loader.style.width = '100%';
        setTimeout(() => { loader.style.opacity = '0'; }, 400);
        });

        // 3. SCROLL REVEAL ANIMATION
        const revealElements = document.querySelectorAll('.reveal');
        const revealOnScroll = () => {
        const windowHeight = window.innerHeight;
        revealElements.forEach(el => {
            const elementTop = el.getBoundingClientRect().top;
            if (elementTop < windowHeight - 100) {
            el.classList.add('active');
            }
        });
        };
        window.addEventListener('scroll', revealOnScroll);
        window.addEventListener('load', revealOnScroll);

        // 4. MOBILE MENU TOGGLE (Delegasi Event)
        document.addEventListener('click', (e) => {
        if (e.target.closest('#mobile-menu-btn')) {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) {
            mobileMenu.classList.toggle('hidden');
            }
        }
        });

        document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('kos-card-container');
        if (!container) return;

        // 1. Ambil semua elemen card kos yang ada di dalam container
        const cards = Array.from(container.children);

        // 2. Acak urutan array card menggunakan algoritma Fisher-Yates
        for (let i = cards.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [cards[i], cards[j]] = [cards[j], cards[i]];
        }

        // 3. Masukkan kembali elemen yang sudah diacak ke dalam container
        cards.forEach(card => container.appendChild(card));
        });

            // 5. FILTER WILAYAH KOS
            const filterBtns = document.querySelectorAll('.filter-btn');
            const kosCards = document.querySelectorAll('.kos-card');

            filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                b.classList.remove('bg-slate-900', 'text-white');
                b.classList.add('bg-white', 'text-slate-700');
                });
                btn.classList.remove('bg-white', 'text-slate-700');
                btn.classList.add('bg-slate-900', 'text-white');

                const filter = btn.getAttribute('data-filter');
                kosCards.forEach(card => {
                if (filter === 'semua' || card.getAttribute('data-wilayah') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
                });
            });
            });

        // 6. FUNCTION UNTUK MENUTUP MODAL POPUP FLASH SESSION
        function closeSuccessModal() {
            const modal = document.getElementById('flash-success-modal');
            if (modal) modal.remove();
        }

        function closeFailedModal() {
            const modal = document.getElementById('flash-failed-modal');
            if (modal) modal.remove();
        }
    </script>


</body>
</html>
