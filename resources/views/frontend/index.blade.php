<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sinar Citra Lestari - Platform Pencarian & Sewa Kos Terpercaya</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" href="{{ asset('logo.png') }}">

  <style>
    /* Infinite horizontal scroll for testimonials */
    @keyframes infinite-scroll {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    .animate-infinite-scroll {
      display: flex;
      width: max-content;
      animation: infinite-scroll 35s linear infinite;
    }
    .animate-infinite-scroll:hover {
      animation-play-state: paused;
    }

    /* Scroll reveal effect */
    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-cyan-500 selection:text-white">

    <!-- FLASH MESSAGE MODAL -->
    @if (session('success'))
      <div id="flash-success-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[999] flex items-center justify-center p-4 transition-all">
        <div class="bg-white w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl relative space-y-5 border border-slate-100 animate-in fade-in zoom-in duration-200">
          <button type="button" onclick="closeSuccessModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-200 hover:text-slate-700 transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
          </button>

          <div class="space-y-1 text-center">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-inner">
              <i class="fa-solid fa-circle-check text-3xl"></i>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900">Booking Berhasil!</h3>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">{{ session('success') }}</p>
          </div>

          <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 flex items-start gap-3 text-amber-900">
            <i class="fa-regular fa-clock text-lg text-amber-600 mt-0.5 shrink-0"></i>
            <div class="text-xs space-y-1">
              <span class="font-bold block">Bukti Booking PDF Telah Dikirim!</span>
              <p class="text-[11px] text-amber-800 leading-relaxed">
                Silakan cek kotak masuk email Anda untuk melihat rincian booking & tiket PDF. Konfirmasi lanjutan akan diproses oleh pengelola kos.
              </p>
            </div>
          </div>

          <button type="button" onclick="closeSuccessModal()" class="w-full py-3.5 bg-cyan-600 hover:bg-cyan-700 text-white font-extrabold rounded-2xl transition-all shadow-md active:scale-95 text-xs sm:text-sm">
            Mengerti & Tutup
          </button>
        </div>
      </div>
    @endif

    @if (session('failed'))
      <div id="flash-failed-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[999] flex items-center justify-center p-4 transition-all">
        <div class="bg-white w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl relative space-y-5 border border-slate-100 animate-in fade-in zoom-in duration-200">
          <button type="button" onclick="closeFailedModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-200 hover:text-slate-700 transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
          </button>
          <div class="space-y-1 text-center">
            <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3 shadow-inner">
              <i class="fa-solid fa-circle-exclamation text-3xl"></i>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900">Pemesanan Belum Berhasil</h3>
            <p class="text-xs sm:text-sm text-rose-600 mt-1">{{ session('failed') }}</p>
          </div>
          <button type="button" onclick="closeFailedModal()" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold rounded-2xl transition-all shadow-md active:scale-95 text-xs sm:text-sm">
            Coba Lagi
          </button>
        </div>
      </div>
    @endif

    <!-- 1. BAR LOADING HALAMAN -->
    <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-500 to-blue-600 z-[100] transition-all duration-500 ease-out"></div>

    <!-- 2. NAVBAR -->
    @include('frontend.navbar')

    <!-- 3. HERO SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6 reveal">
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-cyan-100/80 text-cyan-800 rounded-full text-xs font-bold tracking-wide uppercase shadow-sm border border-cyan-200/50">
                    <i class="fa-solid fa-sparkles text-cyan-600"></i> Platform Kos Modern #1
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.15] tracking-tight">
                    Temukan Kamar <br><span class="bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">Kost Impianmu.</span>
                </h1>
                <p class="text-slate-600 text-base sm:text-lg max-w-md leading-relaxed font-normal">
                    Hunian nyaman, strategis, dan fasilitas terlengkap untuk mendukung kenyamanan & produktivitas harianmu.
                </p>

                <!-- Search Bar Komponen -->
                <form action="{{ route('kosan.index') }}" method="GET" class="flex items-center bg-white border border-slate-200/80 rounded-full p-2 shadow-xl hover:shadow-2xl hover:border-cyan-400 transition-all max-w-md">
                    <div class="pl-4 pr-2 text-cyan-600 text-base">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="search" placeholder="Cari nama kos, lokasi, fasilitas..." class="w-full bg-transparent text-slate-800 placeholder-slate-400 text-xs sm:text-sm focus:outline-none py-2 font-medium" required>
                    <button type="submit" class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-bold px-6 py-2.5 rounded-full text-xs sm:text-sm transition-all shadow-md shrink-0 hover:scale-105 active:scale-95">
                        Cari Kos
                    </button>
                </form>

                <!-- Quick Tags -->
                <div class="flex flex-wrap items-center gap-2 pt-1 text-xs text-slate-500">
                    <span class="font-semibold text-slate-600">Populer:</span>
                    <a href="{{ route('kosan.index', ['search' => 'AC']) }}" class="px-2.5 py-1 bg-white hover:bg-cyan-50 hover:text-cyan-700 border border-slate-200 rounded-full transition-colors">AC</a>
                    <a href="{{ route('kosan.index', ['search' => 'WiFi']) }}" class="px-2.5 py-1 bg-white hover:bg-cyan-50 hover:text-cyan-700 border border-slate-200 rounded-full transition-colors">WiFi</a>
                    <a href="{{ route('kosan.index', ['search' => 'Kamar Mandi Dalam']) }}" class="px-2.5 py-1 bg-white hover:bg-cyan-50 hover:text-cyan-700 border border-slate-200 rounded-full transition-colors">KM Dalam</a>
                </div>
            </div>

            <!-- Hero Gallery Grid -->
            <div class="grid grid-cols-2 gap-4 reveal">
                <div class="relative group overflow-hidden rounded-3xl shadow-xl h-80 lg:h-96">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80" alt="Kamar Utama" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent flex items-end p-5">
                        <span class="text-white font-bold text-sm">Kamar Nyaman & Bersih</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="relative group overflow-hidden rounded-3xl shadow-lg h-36 lg:h-44">
                        <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80" alt="Ruang Tamu" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent flex items-end p-4">
                            <span class="text-white font-bold text-xs">Area Bersama Asri</span>
                        </div>
                    </div>
                    <div class="relative group overflow-hidden rounded-3xl shadow-lg h-36 lg:h-44">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=600&q=80" alt="Interior Kos" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent flex items-end p-4">
                            <span class="text-white font-bold text-xs">Fasilitas Lengkap</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. SECTION: MENGAPA MEMILIH SINAR CITRA LESTARI -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 reveal">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <!-- Sisi Kiri: Deskripsi Keunggulan -->
            <div class="lg:col-span-6 space-y-6">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-cyan-100 text-cyan-700 rounded-full text-xs font-bold tracking-wide">
                    <i class="fa-solid fa-circle-check text-cyan-600"></i> Mengapa Memilih Kami
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
                    Solusi Pencarian Kos Modern & Terpercaya
                </h2>
                <div class="space-y-4 pt-2">
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fa-solid fa-shield-check text-base"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Informasi Terverifikasi & Akurat</h4>
                            <p class="text-slate-500 text-xs mt-1 leading-relaxed">Seluruh data foto, harga sewa, dan fasilitas kamar divalidasi langsung oleh tim pengelola.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fa-solid fa-bolt-lightning text-base"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Pencarian Cepat & Booking Instan</h4>
                            <p class="text-slate-500 text-xs mt-1 leading-relaxed">Pesan kamar secara online, dapatkan bukti reservasi PDF dan konfirmasi langsung ke email Anda.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan: Grid Statistik Keunggulan -->
            <div class="lg:col-span-6 grid grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-4 shadow-sm">
                        <i class="fa-solid fa-building-user text-xl"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900">500+</div>
                    <p class="text-slate-500 text-xs font-semibold mt-1">Pilihan Kos Terdaftar</p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4 shadow-sm">
                        <i class="fa-solid fa-map-location-dot text-xl"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900">15+</div>
                    <p class="text-slate-500 text-xs font-semibold mt-1">Area Lokasi Strategis</p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 shadow-sm">
                        <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900">100%</div>
                    <p class="text-slate-500 text-xs font-semibold mt-1">Tanpa Biaya Tersembunyi</p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 shadow-sm">
                        <i class="fa-solid fa-face-smile text-xl"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900">99%</div>
                    <p class="text-slate-500 text-xs font-semibold mt-1">Kepuasan Penghuni</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. REKOMENDASI KOS TERPOPULER -->
    <section class="bg-gradient-to-b from-cyan-50/70 to-slate-100/80 py-16 overflow-hidden reveal border-y border-cyan-100/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="text-cyan-700 text-xs font-bold uppercase tracking-wider">Pilihan Terbaik</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Rekomendasi Kamar Kos Populer</h2>
                    <p class="text-slate-600 text-xs sm:text-sm mt-1">Pilihan favorit dengan ulasan terbaik dan fasilitas lengkap (geser untuk melihat lainnya).</p>
                </div>

                <!-- Filter Wilayah Buttons -->
                <div class="flex flex-wrap gap-2" id="filter-buttons">
                    <button data-filter="semua" class="filter-btn active px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-full shadow-md transition-all">
                        Semua Wilayah
                    </button>
                    @php
                        $wilayahList = $kamarList->pluck('productKosan.wilayah')->filter()->unique();
                    @endphp
                    @foreach($wilayahList as $w)
                        <button data-filter="{{ Str::slug($w) }}" class="filter-btn px-4 py-2 bg-white text-slate-700 hover:bg-slate-900 hover:text-white text-xs font-bold rounded-full shadow-sm transition-all border border-slate-200">
                            {{ $w }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Container Card Slider Manual -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $iconMap = [
                    'AC' => 'fa-snowflake',
                    'Kamar Mandi Dalam' => 'fa-bath',
                    'Water Heater' => 'fa-temperature-arrow-up',
                    'Kasur Springbed' => 'fa-bed',
                    'Kasur' => 'fa-bed',
                    'Lemari Pakaian' => 'fa-door-closed',
                    'Lemari' => 'fa-door-closed',
                    'Meja & Kursi Belajar' => 'fa-chair',
                    'Meja' => 'fa-table',
                    'TV / Smart TV' => 'fa-tv',
                    'Wastafel' => 'fa-sink',
                    'Wi-Fi / Internet' => 'fa-wifi',
                    'Parkir Mobil' => 'fa-car',
                    'Parkir Motor' => 'fa-motorcycle',
                    'Dapur Bersama' => 'fa-kitchen-set',
                    'CCTV 24 Jam' => 'fa-video',
                    'Keamanan / Satpam' => 'fa-user-shield',
                ];
            @endphp

            @if($kamarList->count() > 0)
                <div class="flex overflow-x-auto space-x-6 pb-6 pt-2 no-scrollbar scroll-smooth" id="kos-card-container">
                    @foreach ($kamarList as $kamar)
                        @php
                            $firstImg = $kamar->productKamarImageKosan->first();
                            if ($firstImg) {
                                $imgUrl = asset('storage/' . $firstImg->image);
                            } elseif ($kamar->productKosan && $kamar->productKosan->productImageKosan->first()) {
                                $imgUrl = asset('storage/' . $kamar->productKosan->productImageKosan->first()->image);
                            } else {
                                $imgUrl = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=600&q=80';
                            }

                            $fasKamarArr = array_filter(array_map('trim', explode(',', $kamar->fasilitas ?? '')));
                            $fasKosanArr = array_filter(array_map('trim', explode(',', $kamar->productKosan->fasilitas ?? '')));
                            $fasilitasArr = array_unique(array_merge($fasKamarArr, $fasKosanArr));

                            $monthlyPriceObj = $kamar->priceKamar->first(function($price) {
                                return strtolower($price->kategori) === 'bulan';
                            });
                            $monthlyPrice = $monthlyPriceObj ? $monthlyPriceObj->price : null;
                            $wilayahNama = $kamar->productKosan->wilayah ?? '-';
                            $kosanJudul = $kamar->productKosan->title ?? 'Kost Properti';
                        @endphp

                        <!-- CARD KAMAR -->
                        <div class="kos-card w-72 sm:w-80 bg-white rounded-3xl shadow-md hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 shrink-0 border border-slate-100 flex flex-col justify-between overflow-hidden group" data-wilayah="{{ Str::slug($wilayahNama) }}">
                            <div class="relative overflow-hidden h-48 bg-slate-100">
                                <img src="{{ $imgUrl }}" alt="{{ $kamar->room }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Tersedia
                                </div>
                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-md text-slate-700 text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                                    <i class="fa-solid fa-eye text-cyan-600"></i> {{ number_format($kamar->views ?? 0) }}
                                </div>
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="text-[11px] font-bold text-cyan-600 uppercase tracking-wider mb-1 truncate" title="{{ $kosanJudul }}">
                                        {{ $kosanJudul }}
                                    </div>
                                    <h3 class="font-extrabold text-slate-900 text-lg truncate hover:text-cyan-600 transition-colors" title="{{ $kamar->room }}">
                                        <a href="{{ route('kamar.detail', $kamar->id) }}">{{ $kamar->room }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-500 flex items-center gap-1 mt-1 font-medium">
                                        <i class="fa-solid fa-location-dot text-rose-500"></i> {{ $wilayahNama }}
                                    </p>

                                    <!-- Fasilitas -->
                                    <div class="flex flex-wrap items-center gap-1.5 my-3 text-[11px] text-slate-600 font-medium min-h-[28px]">
                                        @forelse(array_slice($fasilitasArr, 0, 3) as $fas)
                                            @php $iconClass = $iconMap[$fas] ?? 'fa-check'; @endphp
                                            <span class="bg-cyan-50/80 text-cyan-800 border border-cyan-100 px-2.5 py-0.5 rounded-lg flex items-center gap-1 text-[10px] font-semibold">
                                                <i class="fa-solid {{ $iconClass }} text-cyan-600"></i> {{ $fas }}
                                            </span>
                                        @empty
                                            <span class="text-slate-400 text-xs">Fasilitas lengkap</span>
                                        @endforelse
                                        @if(count($fasilitasArr) > 3)
                                            <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg font-bold border border-slate-200">+{{ count($fasilitasArr) - 3 }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Harga & Aksi -->
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100 mt-2">
                                    <div>
                                        @if($monthlyPrice)
                                            <span class="text-base sm:text-lg font-extrabold text-slate-900">Rp {{ number_format($monthlyPrice, 0, ',', '.') }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">/bulan</span>
                                        @else
                                            <span class="text-xs font-bold text-slate-600">Hubungi Admin</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('kamar.detail', $kamar->id) }}" class="w-10 h-10 rounded-2xl bg-cyan-50 hover:bg-cyan-600 hover:text-white text-cyan-700 flex items-center justify-center transition-all shadow-sm group-hover:bg-cyan-600 group-hover:text-white">
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl p-12 text-center max-w-lg mx-auto shadow-sm border border-slate-100 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-cyan-50 text-cyan-600 flex items-center justify-center mx-auto text-2xl shadow-inner">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900">Belum Ada Kamar Tersedia</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Kamar kos sedang dalam pembaruan data oleh pengelola. Silakan cek kembali dalam waktu dekat.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- 6. NEWS & EVENTS SECTION (DYNAMIC FROM DATABASE) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 reveal">
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="text-cyan-600 text-xs font-bold uppercase tracking-wider">Informasi & Update</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">News & Events</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Artikel terkini seputar panduan kos, gaya hidup, dan info promo.</p>
            </div>
            <a href="{{ route('news.index') }}" class="text-xs sm:text-sm font-bold text-cyan-600 hover:text-cyan-700 flex items-center gap-1.5 transition-colors group">
                <span>Lihat Semua</span> <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($artikels as $art)
                @php
                    $artImg = $art->image ? asset('storage/' . $art->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
                @endphp
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group">
                    <div class="relative overflow-hidden h-48 bg-slate-100">
                        <img src="{{ $artImg }}" alt="{{ $art->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            News & Event
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="text-[11px] text-slate-400 font-semibold mb-1">
                                {{ $art->created_at ? $art->created_at->translatedFormat('d F Y') : 'Terbaru' }}
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-base hover:text-cyan-600 transition-colors line-clamp-2">
                                <a href="{{ route('news.detail', $art->slug) }}">{{ $art->title }}</a>
                            </h3>
                            <p class="text-slate-500 text-xs mt-2 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($art->deskripsi ?? 'Baca selengkapnya artikel menarik ini.'), 90) }}
                            </p>
                        </div>
                        <a href="{{ route('news.detail', $art->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-600 hover:text-cyan-700 transition-colors pt-2 border-t border-slate-100">
                            Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <!-- Fallback Mock Cards -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden p-6 text-center md:col-span-3 py-12">
                    <i class="fa-solid fa-newspaper text-3xl text-slate-300 mb-3 block"></i>
                    <h4 class="font-bold text-slate-700 text-sm">Belum Ada Artikel Berita</h4>
                    <p class="text-xs text-slate-400 mt-1">Artikel dan tips seputar kos akan segera hadir.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- 7. TESTIMONIAL / REVIEWS SECTION -->
    <section class="bg-gradient-to-b from-cyan-50/70 to-slate-100/80 py-16 overflow-hidden reveal border-t border-cyan-100/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-12">
            <!-- Headline Kiri -->
            <div class="lg:w-1/3 space-y-3 shrink-0 text-center lg:text-left">
                <span class="text-cyan-700 text-xs font-bold uppercase tracking-wider">Ulasan Penghuni</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
                    Apa Kata Mereka Tentang Sinar Citra Lestari?
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm max-w-sm leading-relaxed">
                    Lebih dari ribuan penyewa telah menemukan hunian idaman mereka dengan mudah dan aman.
                </p>
            </div>

            <!-- Slider Cards Kanan (Infinite Smooth Scroll) -->
            <div class="lg:w-2/3 overflow-hidden relative w-full">
                <div class="animate-infinite-scroll space-x-6 py-2">
                    @forelse($testimonis as $t)
                        @php
                            $profileImg = $t->image_profile ? asset('storage/' . $t->image_profile) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80';
                            $ratingVal = max(1, min(5, (int) $t->rating));
                        @endphp
                        <div class="w-80 bg-white p-6 rounded-3xl shadow-xl shrink-0 space-y-4 border border-slate-100">
                            <div class="flex text-amber-400 text-xs space-x-1">
                                @for($i = 1; $i <= $ratingVal; $i++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                                @for($j = $ratingVal + 1; $j <= 5; $j++)
                                    <i class="fa-regular fa-star text-slate-300"></i>
                                @endfor
                            </div>
                            <p class="text-slate-600 text-xs leading-relaxed font-medium">
                                "{{ $t->review }}"
                            </p>
                            <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                                <img src="{{ $profileImg }}" alt="{{ $t->name }}" loading="lazy" class="w-10 h-10 rounded-full object-cover shadow-sm border border-slate-200">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">{{ $t->name }}</h4>
                                    <span class="text-[10px] text-emerald-600 font-semibold">Penyewa Terverifikasi</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-80 bg-white p-6 rounded-3xl shadow-xl shrink-0 space-y-4 border border-slate-100">
                            <div class="flex text-amber-400 text-xs space-x-1">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-slate-600 text-xs leading-relaxed font-medium">
                                "Sangat mudah menemukan kos yang nyaman dan fasilitas lengkap di Sinar Citra Lestari. Pelayanannya cepat dan terpercaya!"
                            </p>
                            <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Siska Amelia" loading="lazy" class="w-10 h-10 rounded-full object-cover shadow-sm">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">Siska Amelia</h4>
                                    <span class="text-[10px] text-emerald-600 font-semibold">Penyewa Terverifikasi</span>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    @include('frontend.footer')

    <!-- NEED HELP WIDGET -->
    @include('frontend.need-help')

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Page Loading Bar
        window.addEventListener('load', () => {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.style.width = '100%';
                setTimeout(() => { loader.style.opacity = '0'; }, 300);
            }
        });

        // Scroll Reveal Animation
        const revealElements = document.querySelectorAll('.reveal');
        const revealOnScroll = () => {
            const windowHeight = window.innerHeight;
            revealElements.forEach(el => {
                const elementTop = el.getBoundingClientRect().top;
                if (elementTop < windowHeight - 80) {
                    el.classList.add('active');
                }
            });
        };
        window.addEventListener('scroll', revealOnScroll);
        window.addEventListener('load', revealOnScroll);

        // Filter Wilayah Buttons
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
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Flash Modal Close
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
