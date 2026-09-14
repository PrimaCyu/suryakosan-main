<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinar Citra Lestari - Platform Pencarian & Sewa kosan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="{{ asset('scl.png') }}">

    <style>
        :root {
            --scl-brown: #3B2314;
            --scl-brown-soft: #6B4630;
            --scl-red: #E60049;
            --scl-yellow: #F3A833;
            --scl-green: #00A896;
            --scl-cream: #FFF8F1;
            --scl-paper: #FFFCF8;
        }

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

        @keyframes scl-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-7px); }
        }

        .scl-float {
            animation: scl-float 4s ease-in-out infinite;
        }

        @keyframes scl-pulse-ring {
            0% { transform: scale(.92); opacity: .65; }
            70%, 100% { transform: scale(1.18); opacity: 0; }
        }

        .scl-pulse-ring {
            animation: scl-pulse-ring 2s ease-out infinite;
        }

        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-[#FFF8F1] text-[#3B2314] font-sans antialiased overflow-x-hidden selection:bg-[#E60049] selection:text-white">

    <!-- FLASH MESSAGE MODAL -->
    @if (session('success'))
        <div id="flash-success-modal" class="fixed inset-0 bg-[#3B2314]/70 backdrop-blur-sm z-[999] flex items-center justify-center p-4">
            <div class="bg-[#FFFCF8] w-full max-w-md rounded-[2rem] p-6 sm:p-8 shadow-2xl relative space-y-5 border border-[#F3A833]/30 animate-in fade-in zoom-in duration-200">
                <button type="button" onclick="closeSuccessModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-[#3B2314]/5 flex items-center justify-center text-[#6B4630] hover:bg-[#E60049] hover:text-white transition-all">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>

                <div class="space-y-1 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#00A896]/10 text-[#00A896] flex items-center justify-center mx-auto mb-3 rotate-3">
                        <i class="fa-solid fa-circle-check text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-[#3B2314]">Booking Berhasil!</h3>
                    <p class="text-xs sm:text-sm text-[#6B4630] mt-1">{{ session('success') }}</p>
                </div>

                <div class="p-4 bg-[#F3A833]/10 rounded-2xl border border-[#F3A833]/30 flex items-start gap-3 text-[#6B4630]">
                    <i class="fa-regular fa-clock text-lg text-[#F3A833] mt-0.5 shrink-0"></i>
                    <div class="text-xs space-y-1">
                        <span class="font-bold block text-[#3B2314]">Bukti Booking PDF Telah Dikirim!</span>
                        <p class="text-[11px] leading-relaxed">
                            Silakan cek kotak masuk email Anda untuk melihat rincian booking & tiket PDF. Konfirmasi lanjutan akan diproses oleh pengelola kos.
                        </p>
                    </div>
                </div>

                <button type="button" onclick="closeSuccessModal()" class="w-full py-3.5 bg-[#3B2314] hover:bg-[#E60049] text-white font-extrabold rounded-2xl transition-all shadow-md active:scale-95 text-xs sm:text-sm">
                    Mengerti & Tutup
                </button>
            </div>
        </div>
    @endif

    @if (session('failed'))
        <div id="flash-failed-modal" class="fixed inset-0 bg-[#3B2314]/70 backdrop-blur-sm z-[999] flex items-center justify-center p-4">
            <div class="bg-[#FFFCF8] w-full max-w-md rounded-[2rem] p-6 sm:p-8 shadow-2xl relative space-y-5 border border-[#E60049]/20 animate-in fade-in zoom-in duration-200">
                <button type="button" onclick="closeFailedModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-[#3B2314]/5 flex items-center justify-center text-[#6B4630] hover:bg-[#E60049] hover:text-white transition-all">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>

                <div class="space-y-1 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#E60049]/10 text-[#E60049] flex items-center justify-center mx-auto mb-3 -rotate-3">
                        <i class="fa-solid fa-circle-exclamation text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-[#3B2314]">Pemesanan Belum Berhasil</h3>
                    <p class="text-xs sm:text-sm text-[#E60049] mt-1">{{ session('failed') }}</p>
                </div>

                <button type="button" onclick="closeFailedModal()" class="w-full py-3.5 bg-[#3B2314] hover:bg-[#E60049] text-white font-extrabold rounded-2xl transition-all shadow-md active:scale-95 text-xs sm:text-sm">
                    Coba Lagi
                </button>
            </div>
        </div>
    @endif

    <!-- 1. BAR LOADING HALAMAN -->
    <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#F3A833] via-[#E60049] to-[#00A896] z-[100] transition-all duration-500 ease-out"></div>

    <!-- 2. NAVBAR -->
    @include('frontend.navbar')

    <!-- 3. HERO SECTION -->
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20 md:pt-16 md:pb-24">
        <div class="absolute -top-10 -right-20 w-72 h-72 bg-[#F3A833]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 -left-20 w-80 h-80 bg-[#00A896]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative rounded-[2.5rem] bg-[#3B2314] overflow-hidden shadow-2xl p-7 sm:p-10 lg:p-14 reveal">
            <div class="absolute right-0 top-0 w-72 h-72 rounded-full bg-[#F3A833]/15 blur-2xl"></div>
            <div class="absolute left-1/2 bottom-0 w-56 h-56 rounded-full bg-[#00A896]/10 blur-2xl"></div>

            <div class="relative grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">
                <div class="lg:col-span-7 text-white">

                    <h1 class="mt-5 text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] tracking-tight">
                        Kos-Kosan <span class="text-[#F3A833]">Nyaman</span> & <span class="text-[#00A896]">Strategis</span>
                    </h1>

                    <p class="mt-5 text-white/75 text-sm sm:text-base lg:text-lg max-w-xl leading-relaxed">
                        Kami hadirkan kosan nyaman, sehat, dan strategis untuk kehidupan yang lebih tenang setiap harinya.
                    </p>

                    <!-- Search Bar Komponen -->
                    <form action="{{ route('kosan.index') }}" method="GET" class="mt-7 bg-white rounded-2xl p-2 flex flex-col sm:flex-row gap-2 max-w-2xl shadow-xl">
                        <div class="flex items-center flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-[#00A896]/10 text-[#00A896] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <input type="text" name="search" placeholder="Cari nama kos, lokasi, fasilitas..." class="w-full bg-transparent text-[#3B2314] placeholder-[#6B4630]/60 text-xs sm:text-sm focus:outline-none py-3 px-3 font-medium" required>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-[#E60049] hover:bg-[#C9003D] text-white rounded-xl text-xs sm:text-sm font-extrabold transition-all shadow-md hover:shadow-lg active:scale-95">
                            Cari Kos
                        </button>
                    </form>
                </div>

                <!-- Hero Gallery: susunan berbeda, kartu bertumpuk -->
                <div class="lg:col-span-5 relative min-h-[360px] sm:min-h-[430px]">
                    <div class="absolute inset-x-6 top-5 bottom-5 bg-[#F3A833]/15 rounded-[2rem] rotate-3"></div>

                    <div class="absolute left-0 top-8 w-[62%] h-72 sm:h-80 rounded-[2rem] overflow-hidden border-4 border-white/10 shadow-2xl scl-float">
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80" alt="Kamar Utama" loading="lazy" class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-[#3B2314]/80 to-transparent">
                            <span class="text-white font-bold text-sm">Kamar Nyaman & Bersih</span>
                        </div>
                    </div>

                    <div class="absolute right-0 top-0 w-[48%] h-44 sm:h-52 rounded-[2rem] overflow-hidden border-4 border-white/10 shadow-xl rotate-2">
                        <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80" alt="Ruang Tamu" loading="lazy" class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-[#3B2314]/75 to-transparent">
                            <span class="text-white font-bold text-xs">Area Bersama Asri</span>
                        </div>
                    </div>

                    <div class="absolute right-2 bottom-2 w-[54%] h-48 sm:h-56 rounded-[2rem] overflow-hidden border-4 border-white/10 shadow-2xl -rotate-2">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=600&q=80" alt="Interior Kos" loading="lazy" class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-[#3B2314]/75 to-transparent">
                            <span class="text-white font-bold text-xs">Fasilitas Lengkap</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. REKOMENDASI KOS TERPOPULER -->
    <section class="py-16 md:py-20 bg-[#3B2314] overflow-hidden reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header dibuat kiri-kanan dengan filter sebagai toolbar -->
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-8">
                <div class="max-w-xl">
                    <span class="text-[#F3A833] text-xs font-extrabold uppercase tracking-[.2em]">Pilihan Terbaik</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Rekomendasi Kamar Kos</h2>
                </div>

                <!-- Filter Wilayah Buttons -->
                <div class="rounded-2xl bg-white/10 p-2 border border-white/10">
                    <div class="flex flex-wrap gap-2" id="filter-buttons">
                        <button data-filter="semua" class="filter-btn active px-4 py-2.5 bg-[#F3A833] text-[#3B2314] text-xs font-extrabold rounded-xl shadow-md transition-all hover:-translate-y-0.5">
                            Semua Wilayah
                        </button>
                        @php
                            $wilayahList = $kamarList->pluck('productKosan.wilayah')->filter()->unique();
                        @endphp
                        @foreach($wilayahList as $w)
                            <button data-filter="{{ Str::slug($w) }}" class="filter-btn px-4 py-2.5 bg-white/5 text-white/75 hover:bg-[#E60049] hover:text-white text-xs font-bold rounded-xl transition-all border border-white/10">
                                {{ $w }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

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
                <!-- Container Card Slider Manual -->
                <div class="relative">
                    <div class="absolute left-0 top-0 bottom-6 w-8 bg-gradient-to-r from-[#3B2314] to-transparent z-10 pointer-events-none"></div>
                    <div class="absolute right-0 top-0 bottom-6 w-8 bg-gradient-to-l from-[#3B2314] to-transparent z-10 pointer-events-none"></div>

                    <div class="flex overflow-x-auto gap-5 pb-6 pt-2 no-scrollbar scroll-smooth" id="kos-card-container">
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
                            <div class="kos-card w-72 sm:w-80 bg-[#FFFCF8] rounded-[2rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 shrink-0 border border-[#F3A833]/20 flex flex-col overflow-hidden group" data-wilayah="{{ Str::slug($wilayahNama) }}">
                                <div class="relative overflow-hidden h-52 bg-[#6B4630]/10">
                                    <img src="{{ $imgUrl }}" alt="{{ $kamar->room }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                                    <div class="absolute top-3 left-3 bg-[#3B2314]/90 text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#00A896]"></span> Tersedia
                                    </div>

                                    <div class="absolute top-3 right-3 bg-white/90 text-[#3B2314] text-[11px] font-bold px-2.5 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                                        <i class="fa-solid fa-eye text-[#E60049]"></i> {{ number_format($kamar->views ?? 0) }}
                                    </div>

                                    <div class="absolute bottom-3 left-3 right-3">
                                        <span class="inline-block bg-[#F3A833] text-[#3B2314] text-[10px] font-extrabold px-3 py-1 rounded-full">
                                            {{ $wilayahNama }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="text-[10px] font-extrabold text-[#00A896] uppercase tracking-wider mb-1 truncate" title="{{ $kosanJudul }}">
                                            {{ $kosanJudul }}
                                        </div>

                                        <h3 class="font-extrabold text-[#3B2314] text-lg truncate hover:text-[#E60049] transition-colors" title="{{ $kamar->room }}">
                                            <a href="{{ route('kamar.detail', $kamar->id) }}">{{ $kamar->room }}</a>
                                        </h3>

                                        <p class="text-xs text-[#6B4630] flex items-center gap-1 mt-1 font-medium">
                                            <i class="fa-solid fa-location-dot text-[#E60049]"></i> {{ $wilayahNama }}
                                        </p>

                                        <!-- Fasilitas -->
                                        <div class="flex flex-wrap items-center gap-1.5 my-4 text-[11px] font-medium min-h-[28px]">
                                            @forelse(array_slice($fasilitasArr, 0, 3) as $fas)
                                                @php $iconClass = $iconMap[$fas] ?? 'fa-check'; @endphp
                                                <span class="bg-[#00A896]/8 text-[#006F65] border border-[#00A896]/15 px-2.5 py-1 rounded-lg flex items-center gap-1 text-[10px] font-semibold">
                                                    <i class="fa-solid {{ $iconClass }} text-[#00A896]"></i> {{ $fas }}
                                                </span>
                                            @empty
                                                <span class="text-[#6B4630]/60 text-xs">Fasilitas lengkap</span>
                                            @endforelse

                                            @if(count($fasilitasArr) > 3)
                                                <span class="text-[10px] text-[#6B4630] bg-[#F3A833]/15 px-2 py-1 rounded-lg font-bold border border-[#F3A833]/20">
                                                    +{{ count($fasilitasArr) - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Harga & Aksi -->
                                    <div class="flex items-end justify-between pt-4 border-t border-[#3B2314]/10 mt-2">
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider font-bold text-[#6B4630]/60">Mulai dari</span>
                                            @if($monthlyPrice)
                                                <span class="text-base sm:text-lg font-extrabold text-[#3B2314]">Rp {{ number_format($monthlyPrice, 0, ',', '.') }}</span>
                                                <span class="text-[10px] text-[#6B4630]/60 font-medium">/bulan</span>
                                            @else
                                                <span class="text-xs font-bold text-[#6B4630]">Hubungi Admin</span>
                                            @endif
                                        </div>

                                        <a href="{{ route('kamar.detail', $kamar->id) }}" aria-label="Lihat detail {{ $kamar->room }}" class="w-11 h-11 rounded-2xl bg-[#E60049] hover:bg-[#3B2314] text-white flex items-center justify-center transition-all shadow-md group-hover:scale-105 active:scale-95">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-[#FFFCF8] rounded-[2rem] p-12 text-center max-w-lg mx-auto shadow-sm border border-[#F3A833]/20 space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-[#F3A833]/15 text-[#F3A833] flex items-center justify-center mx-auto text-2xl">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-[#3B2314]">Belum Ada Kamar Tersedia</h3>
                    <p class="text-xs text-[#6B4630] leading-relaxed">Kamar kos sedang dalam pembaruan data oleh pengelola. Silakan cek kembali dalam waktu dekat.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- 6. NEWS & EVENTS SECTION (DYNAMIC FROM DATABASE) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 reveal">
        <!-- Layout news dibuat seperti feature + list -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-5 mb-9">
            <div>
                <span class="text-[#E60049] text-xs font-extrabold uppercase tracking-[.2em]">Informasi Terbaru</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#3B2314] mt-2">Berita & Acara</h2>
            </div>

            <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#3B2314] text-white text-xs sm:text-sm font-bold hover:bg-[#E60049] transition-all shadow-md group">
                <span>Lihat Semua</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            @forelse($artikels as $index => $art)
                @php
                    $artImg = $art->image
                        ? asset('storage/' . $art->image)
                        : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
                @endphp

                @if($index === 0)
                    <div class="lg:col-span-7 bg-[#3B2314] rounded-[2rem] overflow-hidden min-h-[390px] relative group shadow-xl">
                        <img src="{{ $artImg }}" alt="{{ $art->title }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#3B2314] via-[#3B2314]/25 to-transparent"></div>
                        <div class="relative min-h-[390px] flex flex-col justify-end p-6 sm:p-8">
                            <span class="self-start bg-[#F3A833] text-[#3B2314] text-[10px] font-extrabold px-3 py-1.5 rounded-full mb-4">
                                Berita & Acara
                            </span>
                            <div class="text-white/65 text-[11px] font-semibold">
                                {{ $art->created_at ? $art->created_at->translatedFormat('d F Y') : 'Terbaru' }}
                            </div>
                            <h3 class="mt-1 font-extrabold text-white text-xl sm:text-2xl leading-tight max-w-2xl">
                                <a href="{{ route('news.detail', $art->slug) }}" class="hover:text-[#F3A833] transition-colors">{{ $art->title }}</a>
                            </h3>
                            <p class="text-white/65 text-xs mt-2 max-w-xl leading-relaxed">
                                {{ Str::limit(strip_tags($art->deskripsi ?? 'Baca selengkapnya artikel menarik ini.'), 110) }}
                            </p>
                            <a href="{{ route('news.detail', $art->slug) }}" class="mt-5 inline-flex items-center gap-2 text-xs font-bold text-[#F3A833]">
                                Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="lg:col-span-5 bg-[#FFFCF8] rounded-[2rem] border border-[#F3A833]/20 shadow-md hover:shadow-xl transition-all overflow-hidden group flex flex-col sm:flex-row lg:flex-col">
                        <div class="relative overflow-hidden h-48 sm:h-auto lg:h-44 sm:w-5/12 lg:w-full shrink-0 bg-[#3B2314]/5">
                            <img src="{{ $artImg }}" alt="{{ $art->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 bg-[#E60049] text-white text-[10px] font-bold px-3 py-1 rounded-full">Berita & Acara</span>
                        </div>

                        <div class="p-5 flex flex-col justify-center">
                            <div class="text-[10px] text-[#6B4630]/60 font-semibold mb-1">
                                {{ $art->created_at ? $art->created_at->translatedFormat('d F Y') : 'Terbaru' }}
                            </div>
                            <h3 class="font-extrabold text-[#3B2314] text-base line-clamp-2 hover:text-[#E60049] transition-colors">
                                <a href="{{ route('news.detail', $art->slug) }}">{{ $art->title }}</a>
                            </h3>
                            <p class="text-[#6B4630] text-xs mt-2 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($art->deskripsi ?? 'Baca selengkapnya artikel menarik ini.'), 90) }}
                            </p>
                            <a href="{{ route('news.detail', $art->slug) }}" class="mt-4 text-xs font-bold text-[#00A896] hover:text-[#E60049] transition-colors">
                                Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @endif
            @empty
                <div class="bg-[#FFFCF8] rounded-[2rem] border border-[#F3A833]/20 overflow-hidden p-10 text-center lg:col-span-12 shadow-sm">
                    <i class="fa-solid fa-newspaper text-3xl text-[#F3A833] mb-3 block"></i>
                    <h4 class="font-bold text-[#3B2314] text-sm">Belum Ada Artikel Berita</h4>
                    <p class="text-xs text-[#6B4630]/60 mt-1">Artikel dan tips seputar kos akan segera hadir.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- 7. TESTIMONIAL / REVIEWS SECTION -->
    <section class="bg-[#F3A833]/15 py-16 md:py-20 overflow-hidden reveal border-y border-[#F3A833]/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Headline dibuat sebagai banner atas -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-5 mb-8">
                <div class="max-w-2xl">
                    <span class="text-[#00A896] text-xs font-extrabold uppercase tracking-[.2em]">Ulasan Penghuni</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#3B2314] leading-tight mt-2">
                        Apa Kata Mereka?
                    </h2>
                </div>
            </div>

            <!-- Slider Cards dibuat full-width, bukan kolom kanan -->
            <div class="overflow-hidden relative w-full rounded-[2rem]">
                <div class="animate-infinite-scroll gap-5 py-3">
                    @forelse($testimonis as $t)
                        @php
                            $profileImg = $t->image_profile
                                ? asset('storage/' . $t->image_profile)
                                : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80';
                            $ratingVal = max(1, min(5, (int) $t->rating));
                        @endphp

                        <div class="w-80 bg-[#FFFCF8] p-6 rounded-[2rem] shadow-lg shrink-0 space-y-4 border border-[#F3A833]/20">
                            <div class="flex items-center justify-between">
                                <div class="flex text-[#F3A833] text-xs gap-1">
                                    @for($i = 1; $i <= $ratingVal; $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                    @for($j = $ratingVal + 1; $j <= 5; $j++)
                                        <i class="fa-regular fa-star text-[#6B4630]/20"></i>
                                    @endfor
                                </div>
                                <i class="fa-solid fa-quote-right text-[#E60049]/20 text-xl"></i>
                            </div>

                            <p class="text-[#6B4630] text-xs leading-relaxed font-medium">
                                "{{ $t->review }}"
                            </p>

                            <div class="flex items-center gap-3 pt-3 border-t border-[#3B2314]/10">
                                <img src="{{ $profileImg }}" alt="{{ $t->name }}" loading="lazy" class="w-10 h-10 rounded-full object-cover shadow-sm border-2 border-[#F3A833]/30">
                                <div>
                                    <h4 class="font-bold text-[#3B2314] text-xs">{{ $t->name }}</h4>
                                    <span class="text-[10px] text-[#00A896] font-semibold">Penyewa Terverifikasi</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-80 bg-[#FFFCF8] p-6 rounded-[2rem] shadow-lg shrink-0 space-y-4 border border-[#F3A833]/20">
                            <div class="flex text-[#F3A833] text-xs gap-1">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-[#6B4630] text-xs leading-relaxed font-medium">
                                "Sangat mudah menemukan kos yang nyaman dan fasilitas lengkap di NemuKOS. Pelayanannya cepat dan terpercaya!"
                            </p>
                            <div class="flex items-center gap-3 pt-3 border-t border-[#3B2314]/10">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Siska Amelia" loading="lazy" class="w-10 h-10 rounded-full object-cover shadow-sm border-2 border-[#F3A833]/30">
                                <div>
                                    <h4 class="font-bold text-[#3B2314] text-xs">Siska Amelia</h4>
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

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Page Loading Bar
        window.addEventListener('load', () => {
            const loader = document.getElementById('page-loader');

            if (loader) {
                loader.style.width = '100%';
                setTimeout(() => {
                    loader.style.opacity = '0';
                }, 300);
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
                    b.classList.remove('bg-[#F3A833]', 'text-[#3B2314]', 'shadow-md');
                    b.classList.add('bg-white/5', 'text-white/75');
                });

                btn.classList.remove('bg-white/5', 'text-white/75');
                btn.classList.add('bg-[#F3A833]', 'text-[#3B2314]', 'shadow-md');

                const filter = btn.getAttribute('data-filter');

                kosCards.forEach(card => {
                    const show = filter === 'semua' || card.getAttribute('data-wilayah') === filter;

                    if (show) {
                        card.style.display = 'flex';
                        requestAnimationFrame(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        });
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
