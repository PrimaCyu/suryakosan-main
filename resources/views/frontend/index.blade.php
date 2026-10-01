<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinar Citra Lestari - Platform Pencarian & Sewa Kos Modern</title>
    <meta name="description" content="Platform pencarian dan sewa kos modern Sinar Citra Lestari di Bali. Dapatkan unit kamar kos siap huni, fasilitas lengkap, lokasi strategis, dan reservasi instan resmi.">
    <meta name="keywords" content="sewa kos bali, kosan modern gianyar, kos tabanan, kost fasilitas lengkap, kosan murah nyaman, kamar kos siap huni">
    
    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Sinar Citra Lestari - Platform Pencarian & Sewa Kos Modern">
    <meta property="og:description" content="Hunian kos modern, nyaman & bebas ribet. Fasilitas lengkap siap huni dengan reservasi instan bergaransi e-Ticket resmi.">
    <meta property="og:image" content="{{ asset('scl.png') }}">

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
            --scl-line: #E9DDD2;
        }

        @keyframes scl-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .scl-float {
            animation: scl-float 5s ease-in-out infinite;
        }

        .scl-float-delayed {
            animation: scl-float 6s ease-in-out 2.5s infinite;
        }

        .shine {
            position: relative;
            overflow: hidden;
        }
        .shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(25deg);
            animation: shineAnimation 4s infinite;
        }
        @keyframes shineAnimation {
            0% { left: -60%; }
            20% { left: 140%; }
            100% { left: 140%; }
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

<body class="bg-[#FFF8F1] text-[#3B2314] font-sans antialiased overflow-x-hidden selection:bg-[#E60049] selection:text-white min-h-screen flex flex-col justify-between">

    <!-- FLASH MESSAGE MODAL -->
    @if (session('success'))
        <div id="flash-success-modal" onclick="if(event.target===this) closeSuccessModal()" class="fixed inset-0 bg-[#3B2314]/70 backdrop-blur-sm z-[999] flex items-center justify-center p-4">
            <div class="bg-[#FFFCF8] w-full max-w-md rounded-[2.5rem] p-6 sm:p-8 shadow-2xl relative space-y-5 border border-[#F3A833]/30 animate-in fade-in zoom-in duration-200">
                <button type="button" onclick="closeSuccessModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-[#3B2314]/5 flex items-center justify-center text-[#6B4630] hover:bg-[#E60049] hover:text-white transition-all" aria-label="Tutup modal">
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
        <div id="flash-failed-modal" onclick="if(event.target===this) closeFailedModal()" class="fixed inset-0 bg-[#3B2314]/70 backdrop-blur-sm z-[999] flex items-center justify-center p-4">
            <div class="bg-[#FFFCF8] w-full max-w-md rounded-[2.5rem] p-6 sm:p-8 shadow-2xl relative space-y-5 border border-[#E60049]/20 animate-in fade-in zoom-in duration-200">
                <button type="button" onclick="closeFailedModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-[#3B2314]/5 flex items-center justify-center text-[#6B4630] hover:bg-[#E60049] hover:text-white transition-all" aria-label="Tutup modal">
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

    <!-- 1. LOADING BAR -->
    <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#F3A833] via-[#E60049] to-[#00A896] z-[100] transition-all duration-500 ease-out"></div>

    <!-- 2. NAVBAR -->
    @include('frontend.navbar')

    <main class="w-full flex-grow">

        <!-- 3. HERO SECTION 2.0 (MODERN, IMPACTFUL & HIGH-CONVERTING) -->
        <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-16 md:pb-24">
            
            <!-- Atmospheric ambient glows -->
            <div class="absolute -top-12 -right-20 w-96 h-96 bg-[#F3A833]/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 -left-20 w-80 h-80 bg-[#00A896]/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative rounded-[2.5rem] sm:rounded-[3rem] overflow-hidden shadow-2xl reveal min-h-[520px] sm:min-h-[580px] border border-[#E9DDD2]">
                <!-- Hero Photo Background with warm gradient overlay -->
                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=2000&q=85" alt="Sinar Citra Lestari Modern Living" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-[#24150D]/95 via-[#24150D]/75 to-transparent"></div>

                <!-- Floating Glassmorphic Stat Chips (Desktop) -->
                <div class="hidden lg:flex absolute top-10 right-10 z-20 items-center gap-3 px-4 py-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white shadow-xl scl-float">
                    <div class="w-10 h-10 rounded-xl bg-[#F3A833] text-[#3B2314] flex items-center justify-center font-black text-sm">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div>
                        <div class="text-xs font-black">4.9 / 5.0 Rating</div>
                        <div class="text-[10px] text-white/70">500+ Review Penghuni Puas</div>
                    </div>
                </div>

                <div class="hidden lg:flex absolute bottom-12 right-12 z-20 items-center gap-3 px-4 py-2.5 rounded-2xl bg-[#00A896]/20 backdrop-blur-md border border-[#00A896]/30 text-white shadow-xl scl-float-delayed">
                    <div class="w-10 h-10 rounded-xl bg-[#00A896] text-white flex items-center justify-center text-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <div class="text-xs font-black">100% Terverifikasi</div>
                        <div class="text-[10px] text-white/70">Bebas Biaya Tersembunyi</div>
                    </div>
                </div>

                <!-- Main Hero Content -->
                <div class="relative z-10 flex flex-col justify-center items-start min-h-[520px] sm:min-h-[580px] px-6 sm:px-12 lg:px-16 py-12 text-white max-w-3xl">
                    
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[#F3A833] text-xs font-black uppercase tracking-wider mb-5">
                        <i class="fa-solid fa-sparkles text-[11px]"></i>
                        <span>Platform Sewa Kos Pilihan #1 di Bali</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black leading-[1.1] tracking-tight text-white">
                        Hunian Kos Modern, <br class="hidden sm:inline">
                        <span class="text-[#F3A833]">Nyaman</span> & Bebas Ribet.
                    </h1>

                    <p class="text-xs sm:text-sm text-white/80 mt-4 leading-relaxed max-w-xl">
                        Temukan kamar sewa idaman dengan fasilitas lengkap siap huni, lokasi strategis dekat fasilitas publik, dan sistem reservasi instan bergaransi e-Ticket resmi.
                    </p>

                    <!-- SMART SEARCH FORM -->
                    <div class="mt-8 w-full">
                        <form action="{{ route('kosan.index') }}" method="GET" class="bg-white/95 backdrop-blur-md rounded-3xl p-3 sm:p-4 shadow-2xl border border-white/40 text-[#3B2314]">
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                                
                                <!-- WILAYAH DROPDOWN -->
                                @php
                                    $wilayahList = $kamarList->pluck('productKosan.wilayah')
                                        ->merge(isset($kosanList) ? $kosanList->pluck('wilayah') : [])
                                        ->filter()
                                        ->unique()
                                        ->values();
                                @endphp
                                <div class="sm:col-span-4 relative flex items-center bg-[#FFF8F1] rounded-2xl px-3 py-2.5 border border-[#E9DDD2] hover:border-[#F3A833] transition-colors">
                                    <i class="fa-solid fa-location-dot text-[#E60049] text-sm shrink-0 mr-2.5"></i>
                                    <div class="flex-1 min-w-0">
                                        <label for="search-wilayah" class="block text-[9px] uppercase font-black text-[#8E7B6D] tracking-wider cursor-pointer">Wilayah</label>
                                        <select id="search-wilayah" name="wilayah" class="w-full bg-transparent text-xs font-bold text-[#3B2314] focus:outline-none cursor-pointer">
                                            <option value="semua">Semua Wilayah</option>
                                            @foreach($wilayahList as $w)
                                                <option value="{{ $w }}">{{ $w }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- BUDGET RANGE DROPDOWN -->
                                <div class="sm:col-span-4 relative flex items-center bg-[#FFF8F1] rounded-2xl px-3 py-2.5 border border-[#E9DDD2] hover:border-[#F3A833] transition-colors">
                                    <i class="fa-solid fa-wallet text-[#F3A833] text-sm shrink-0 mr-2.5"></i>
                                    <div class="flex-1 min-w-0">
                                        <label for="search-price-range" class="block text-[9px] uppercase font-black text-[#8E7B6D] tracking-wider cursor-pointer">Rentang Budget</label>
                                        <select id="search-price-range" name="price_range" class="w-full bg-transparent text-xs font-bold text-[#3B2314] focus:outline-none cursor-pointer">
                                            <option value="">Semua Budget</option>
                                            <option value="under-500">&lt; Rp 500rb / bln</option>
                                            <option value="500-1000">Rp 500rb - 1 Juta</option>
                                            <option value="1000-1500">Rp 1 - 1.5 Juta</option>
                                            <option value="1500-2500">Rp 1.5 - 2.5 Juta</option>
                                            <option value="over-2500">&gt; Rp 2.5 Juta</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- KEYWORDS SEARCH & SUBMIT -->
                                <div class="sm:col-span-4 flex items-center gap-2">
                                    <div class="flex-1 relative flex items-center bg-[#FFF8F1] rounded-2xl px-3 py-2.5 border border-[#E9DDD2] hover:border-[#F3A833] transition-colors">
                                        <i class="fa-solid fa-magnifying-glass text-[#00A896] text-sm shrink-0 mr-2"></i>
                                        <div class="flex-1 min-w-0">
                                            <label for="hero-search-input" class="block text-[9px] uppercase font-black text-[#8E7B6D] tracking-wider cursor-pointer">Kata Kunci</label>
                                            <input
                                                type="text"
                                                id="hero-search-input"
                                                name="search"
                                                placeholder="Nama kos / fasilitas..."
                                                class="w-full bg-transparent text-xs font-bold text-[#3B2314] placeholder-[#8E7B6D]/60 focus:outline-none"
                                            >
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        class="shine px-5 py-4 bg-[#E60049] hover:bg-[#C90040] text-white rounded-2xl font-black text-xs transition-all shadow-md active:scale-95 shrink-0 flex items-center gap-2"
                                        title="Cari Kos"
                                        aria-label="Cari Kos"
                                    >
                                        <span>Cari</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </button>
                                </div>

                            </div>
                        </form>

                        <!-- QUICK SEARCH TAGS -->
                        <div class="flex flex-wrap items-center gap-2 mt-3 text-xs">
                            <span class="text-[11px] font-bold text-white/70">🔥 Populer:</span>
                            <button type="button" onclick="setQuickSearch('AC')" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white text-[11px] font-semibold transition-colors border border-white/15">AC Dingin</button>
                            <button type="button" onclick="setQuickSearch('WiFi')" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white text-[11px] font-semibold transition-colors border border-white/15">Wi-Fi Cepat</button>
                            <button type="button" onclick="setQuickSearch('Kamar Mandi')" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white text-[11px] font-semibold transition-colors border border-white/15">KM Dalam</button>
                            <button type="button" onclick="setQuickSearch('Parkir')" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white text-[11px] font-semibold transition-colors border border-white/15">Parkir Mobil</button>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- 4. TRUST BAR / METRIC HIGHLIGHTS -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-10 mb-16 relative z-30 reveal">
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-[#E9DDD2] grid grid-cols-2 md:grid-cols-4 gap-6 text-center divide-y sm:divide-y-0 sm:divide-x divide-[#E9DDD2]">
                <div class="pt-3 sm:pt-0">
                    <div class="text-2xl sm:text-3xl font-black text-[#3B2314]">100%</div>
                    <div class="text-xs text-[#7B6759] font-bold mt-1">Unit Asli & Terverifikasi</div>
                </div>
                <div class="pt-3 sm:pt-0">
                    <div class="text-2xl sm:text-3xl font-black text-[#00A896]">500+</div>
                    <div class="text-xs text-[#7B6759] font-bold mt-1">Penghuni Aktif & Puas</div>
                </div>
                <div class="pt-3 sm:pt-0">
                    <div class="text-2xl sm:text-3xl font-black text-[#F3A833]">24/7</div>
                    <div class="text-xs text-[#7B6759] font-bold mt-1">Keamanan CCTV Terpadu</div>
                </div>
                <div class="pt-3 sm:pt-0">
                    <div class="text-2xl sm:text-3xl font-black text-[#E60049]">Rp 0</div>
                    <div class="text-xs text-[#7B6759] font-bold mt-1">Biaya Survei / Bebas Pungli</div>
                </div>
            </div>
        </section>

        <!-- 5. MENGAPA SINAR CITRA LESTARI? (VALUE PROPOSITIONS) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 reveal">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-[10px] font-black uppercase tracking-[.25em] text-[#00A896] block mb-1">Keunggulan Layanan</span>
                <h2 class="text-3xl sm:text-4xl font-black text-[#3B2314]">Kenapa Memilih Sinar Citra Lestari?</h2>
                <p class="text-xs sm:text-sm text-[#7B6759] mt-2 leading-relaxed">
                    Kami menghadirkan standar hunian kos modern dengan jaminan kenyamanan, kepastian hukum sewa, dan kemudahan transaksi.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- CARD 1 -->
                <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-[#E9DDD2] shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-[#00A896]/10 text-[#00A896] flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h3 class="text-lg font-black text-[#3B2314]">Keamanan & Privasi 24 Jam</h3>
                        <p class="text-xs text-[#7B6759] mt-2.5 leading-relaxed">
                            Dilengkapi pantauan CCTV modern, pintu akses aman, dan lingkungan tenang yang mendukung istirahat serta aktivitas belajar Anda.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#E9DDD2]/60 text-[11px] font-bold text-[#00A896] flex items-center gap-1.5">
                        <span>Aman & Terjaga</span>
                        <i class="fa-solid fa-check text-[10px]"></i>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-[#E9DDD2] shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-[#E60049]/10 text-[#E60049] flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <h3 class="text-lg font-black text-[#3B2314]">Lokasi Sangat Strategis</h3>
                        <p class="text-xs text-[#7B6759] mt-2.5 leading-relaxed">
                            Dekat pusat pendidikan, perkantoran, rumah sakit, minimarket 24 jam, dan sentra kuliner. Akses jalan mudah dan bebas banjir.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#E9DDD2]/60 text-[11px] font-bold text-[#E60049] flex items-center gap-1.5">
                        <span>Akses Mudah</span>
                        <i class="fa-solid fa-check text-[10px]"></i>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-[#E9DDD2] shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-[#F3A833]/15 text-[#3B2314] flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <h3 class="text-lg font-black text-[#3B2314]">Transparansi & Invoice PDF</h3>
                        <p class="text-xs text-[#7B6759] mt-2.5 leading-relaxed">
                            Tanpa pungutan liar. Setiap reservasi disertai rincian biaya transparan, tiket digital (#BOOK-XXXXX), serta invoice resmi berformat PDF.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#E9DDD2]/60 text-[11px] font-bold text-[#F3A833] flex items-center gap-1.5">
                        <span>Pasti & Resmi</span>
                        <i class="fa-solid fa-check text-[10px]"></i>
                    </div>
                </div>

                <!-- CARD 4 -->
                <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-[#E9DDD2] shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-[#3B2314]/10 text-[#3B2314] flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-couch"></i>
                        </div>
                        <h3 class="text-lg font-black text-[#3B2314]">Unit Kamar Siap Huni</h3>
                        <p class="text-xs text-[#7B6759] mt-2.5 leading-relaxed">
                            Dilengkapi perabot lengkap (kasur springbed, lemari, meja kerja), penyejuk ruangan (AC), dan internet berkecepatan tinggi.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#E9DDD2]/60 text-[11px] font-bold text-[#3B2314] flex items-center gap-1.5">
                        <span>Tinggal Masuk</span>
                        <i class="fa-solid fa-check text-[10px]"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. SHOWCASE PROPERTI KOSAN TERSEDIA -->
        @if(isset($kosanList) && $kosanList->count() > 0)
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14 reveal">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[.25em] text-[#E60049] block mb-1">Properti Pilihan</span>
                        <h2 class="text-3xl sm:text-4xl font-black text-[#3B2314]">Eksplorasi Properti Kosan</h2>
                    </div>
                    <a href="{{ route('kosan.index') }}" class="inline-flex items-center gap-2 text-xs font-black text-[#E60049] hover:text-[#3B2314] transition-colors">
                        <span>Lihat Semua Properti</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($kosanList as $kos)
                        @php
                            $kosImg = $kos->productImageKosan->first();
                            $kosImgUrl = $kosImg ? asset('storage/' . $kosImg->image) : 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=800&q=80';
                        @endphp
                        <div class="bg-white rounded-[2rem] border border-[#E9DDD2] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group flex flex-col sm:flex-row">
                            <div class="sm:w-5/12 relative h-56 sm:h-auto overflow-hidden bg-[#EDE4DC] shrink-0">
                                <img src="{{ $kosImgUrl }}" alt="{{ $kos->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <span class="absolute top-3 left-3 px-3 py-1 bg-[#3B2314]/90 text-white rounded-full text-[10px] font-black">
                                    {{ $kos->wilayah ?? 'Bali' }}
                                </span>
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#00A896]">
                                        <i class="fa-solid fa-door-open"></i> {{ $kos->productKamarKosan->count() }} Tipe Kamar
                                    </span>
                                    <h3 class="text-lg font-black text-[#3B2314] mt-1 group-hover:text-[#E60049] transition-colors">
                                        <a href="{{ route('kosan.detail', $kos->slug) }}">{{ $kos->title }}</a>
                                    </h3>
                                    <p class="text-xs text-[#7B6759] mt-2 line-clamp-2 leading-relaxed">
                                        {{ strip_tags($kos->description ?? 'Properti hunian kos nyaman dengan fasilitas unggulan dan lokasi aman.') }}
                                    </p>
                                </div>
                                <div class="pt-4 mt-4 border-t border-[#E9DDD2] flex items-center justify-between">
                                    <span class="text-[11px] text-[#8E7B6D] font-bold">
                                        <i class="fa-solid fa-location-dot text-[#E60049] mr-1"></i> {{ $kos->wilayah }}
                                    </span>
                                    <a href="{{ route('kosan.detail', $kos->slug) }}" class="px-4 py-2 bg-[#3B2314] hover:bg-[#E60049] text-white text-xs font-black rounded-xl transition-all active:scale-95 flex items-center gap-1.5" aria-label="Lihat kos {{ $kos->title }}">
                                        <span>Lihat Kos</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- 7. REKOMENDASI UNIT KAMAR (MODERNIZED WITH DUAL CTA & DYNAMIC FILTER) -->
        <section class="py-16 md:py-20 bg-[#3B2314] overflow-hidden reveal text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-10">
                    <div>
                        <span class="text-[#F3A833] text-xs font-black uppercase tracking-[.25em] block mb-1">Pilihan Unit Populer</span>
                        <h2 class="text-3xl sm:text-4xl font-black text-white">Kamar Kos Siap Huni</h2>
                        <p class="text-xs text-white/70 mt-1">Pilih kamar yang sesuai dengan kebutuhan dan segera amankan pesanan Anda.</p>
                    </div>

                    <!-- Filter Wilayah Buttons -->
                    <div class="rounded-2xl bg-white/10 p-1.5 border border-white/10 self-start xl:self-auto">
                        <div class="flex flex-wrap gap-1.5" id="filter-buttons">
                            <button data-filter="semua" class="filter-btn active px-4 py-2 bg-[#F3A833] text-[#3B2314] text-xs font-black rounded-xl shadow-md transition-all">
                                Semua Wilayah
                            </button>
                            @foreach($wilayahList as $w)
                                <button data-filter="{{ Str::slug($w) }}" class="filter-btn px-4 py-2 bg-white/5 text-white/75 hover:bg-[#E60049] hover:text-white text-xs font-bold rounded-xl transition-all border border-white/10">
                                    {{ $w }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                @php
                    $getFasilitasIcon = function($fas) {
                        $f = strtolower(trim($fas));
                        if (str_contains($f, 'ac')) return 'fa-snowflake';
                        if (str_contains($f, 'mandi') || str_contains($f, 'toilet') || str_contains($f, 'km ') || $f === 'km') return 'fa-bath';
                        if (str_contains($f, 'heater') || str_contains($f, 'hangat')) return 'fa-temperature-arrow-up';
                        if (str_contains($f, 'kasur') || str_contains($f, 'springbed') || str_contains($f, 'bed')) return 'fa-bed';
                        if (str_contains($f, 'lemari') || str_contains($f, 'wardrobe')) return 'fa-door-closed';
                        if (str_contains($f, 'meja') || str_contains($f, 'kursi') || str_contains($f, 'kerja')) return 'fa-chair';
                        if (str_contains($f, 'tv')) return 'fa-tv';
                        if (str_contains($f, 'wifi') || str_contains($f, 'wi-fi') || str_contains($f, 'internet')) return 'fa-wifi';
                        if (str_contains($f, 'mobil')) return 'fa-car';
                        if (str_contains($f, 'motor') || str_contains($f, 'parkir')) return 'fa-motorcycle';
                        if (str_contains($f, 'dapur') || str_contains($f, 'kitchen') || str_contains($f, 'masak')) return 'fa-kitchen-set';
                        if (str_contains($f, 'cctv') || str_contains($f, 'keamanan')) return 'fa-video';
                        if (str_contains($f, 'cuci') || str_contains($f, 'laundry')) return 'fa-soap';
                        if (str_contains($f, 'balkon')) return 'fa-mountain-sun';
                        if (str_contains($f, 'wastafel') || str_contains($f, 'sink')) return 'fa-sink';
                        return 'fa-check';
                    };
                @endphp

                @if($kamarList->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="kos-card-container">
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
                                $fasKosanArr = array_filter(array_map('trim', explode(',', $kamar->productKosan?->fasilitas ?? '')));
                                $fasilitasArr = array_unique(array_merge($fasKamarArr, $fasKosanArr));

                                $monthlyPriceObj = $kamar->priceKamar->first(function($price) {
                                    return strtolower($price->kategori) === 'bulan';
                                });
                                $monthlyPrice = $monthlyPriceObj ? (float)$monthlyPriceObj->price : null;
                                $discPercent = $monthlyPriceObj && $monthlyPriceObj->discount > 0
                                    ? (float)$monthlyPriceObj->discount
                                    : (float)($kamar->cumulative_discount ?? 0);
                                $finalMonthlyPrice = $monthlyPrice;
                                if ($monthlyPrice && $discPercent > 0) {
                                    $finalMonthlyPrice = max(0, round($monthlyPrice - ($monthlyPrice * ($discPercent / 100))));
                                }

                                $wilayahNama = $kamar->productKosan->wilayah ?? '-';
                                $kosanJudul = $kamar->productKosan->title ?? 'Kost Properti';
                                $roomStatus = $kamar->room_status ?? 'kosong';
                            @endphp

                            <div class="kos-card bg-white rounded-[2rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col overflow-hidden group border border-[#E9DDD2] text-[#3B2314]" data-wilayah="{{ Str::slug($wilayahNama) }}">
                                
                                <div class="relative overflow-hidden h-52 bg-[#EDE4DC]">
                                    <img src="{{ $imgUrl }}" alt="{{ $kamar->room }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                                    @if($roomStatus === 'terisi')
                                        <div class="absolute top-3 left-3 bg-[#3B2314]/90 text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1.5 shadow-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#E60049]"></span> Terisi
                                        </div>
                                    @elseif($roomStatus === 'pending')
                                        <div class="absolute top-3 left-3 bg-[#3B2314]/90 text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1.5 shadow-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#F3A833] animate-pulse"></span> Booking Pending
                                        </div>
                                    @else
                                        <div class="absolute top-3 left-3 bg-[#3B2314]/90 text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1.5 shadow-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00A896]"></span> Siap Huni
                                        </div>
                                    @endif

                                    <div class="absolute top-3 right-3 bg-white/90 text-[#3B2314] text-[10px] font-extrabold px-2.5 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                                        <i class="fa-solid fa-eye text-[#E60049]"></i> {{ number_format($kamar->views ?? 0) }}
                                    </div>

                                    <div class="absolute bottom-3 left-3 right-3 flex justify-between items-center">
                                        <span class="inline-block bg-[#F3A833] text-[#3B2314] text-[10px] font-black px-3 py-1 rounded-full shadow">
                                            <i class="fa-solid fa-location-dot mr-1"></i> {{ $wilayahNama }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <span class="text-[10px] font-black text-[#00A896] uppercase tracking-wider block mb-1 truncate">
                                            {{ $kosanJudul }}
                                        </span>

                                        <h3 class="font-black text-lg text-[#3B2314] hover:text-[#E60049] transition-colors line-clamp-1">
                                            <a href="{{ route('kamar.detail', $kamar->id) }}">{{ $kamar->room }}</a>
                                        </h3>

                                        <div class="flex flex-wrap items-center gap-1.5 my-3.5 min-h-[28px]">
                                            @forelse(array_slice($fasilitasArr, 0, 3) as $fas)
                                                @php $iconClass = $getFasilitasIcon($fas); @endphp
                                                <span class="bg-[#FFF8F1] text-[#7B6759] border border-[#E9DDD2] px-2.5 py-1 rounded-lg flex items-center gap-1 text-[10px] font-bold">
                                                    <i class="fa-solid {{ $iconClass }} text-[#00A896]"></i> {{ $fas }}
                                                </span>
                                            @empty
                                                <span class="text-[#8E7B6D] text-[11px]">Fasilitas lengkap</span>
                                            @endforelse

                                            @if(count($fasilitasArr) > 3)
                                                <span class="text-[10px] text-[#7B6759] bg-[#F3A833]/15 px-2 py-1 rounded-lg font-black border border-[#F3A833]/30">
                                                    +{{ count($fasilitasArr) - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex items-baseline justify-between pt-3 border-t border-[#E9DDD2]">
                                            <span class="text-[10px] uppercase font-bold text-[#8E7B6D]">Tarif Sewa</span>
                                            @if($monthlyPrice)
                                                <div class="text-right">
                                                    @if($discPercent > 0)
                                                        <div class="flex items-center justify-end gap-1.5 mb-0.5">
                                                            <span class="text-[10px] text-[#8E7B6D] line-through">Rp {{ number_format($monthlyPrice, 0, ',', '.') }}</span>
                                                            <span class="text-[9px] font-black bg-[#E60049]/10 text-[#E60049] px-1.5 py-0.5 rounded-md">-{{ round($discPercent) }}%</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <span class="text-lg font-black text-[#E60049]">Rp {{ number_format($finalMonthlyPrice, 0, ',', '.') }}</span>
                                                        <span class="text-[10px] text-[#7B6759] font-medium">/bln</span>
                                                    </div>
                                                </div>
                                            @elseif($kamar->priceKamar->isNotEmpty())
                                                @php $altPrice = $kamar->priceKamar->first(); @endphp
                                                <div>
                                                    <span class="text-lg font-black text-[#E60049]">Rp {{ number_format($altPrice->price, 0, ',', '.') }}</span>
                                                    <span class="text-[10px] text-[#7B6759] font-medium">/{{ strtolower($altPrice->kategori) }}</span>
                                                </div>
                                            @else
                                                <span class="text-xs font-extrabold text-[#7B6759]">Hubungi Admin</span>
                                            @endif
                                        </div>

                                        <!-- DUAL ACTION SHORTCUT BUTTONS -->
                                        <div class="grid grid-cols-2 gap-2 mt-4">
                                            <a
                                                href="{{ route('kamar.detail', $kamar->id) }}"
                                                class="px-3 py-2.5 bg-white hover:bg-[#FFF8F1] text-[#3B2314] border border-[#E9DDD2] text-xs font-black rounded-xl text-center transition-all shadow-sm active:scale-95"
                                            >
                                                Detail
                                            </a>
                                            <a
                                                href="{{ route('form.booking.kamar', $kamar->id) }}"
                                                class="shine px-3 py-2.5 {{ $roomStatus === 'terisi' ? 'bg-[#3B2314] hover:bg-[#24150D]' : 'bg-[#E60049] hover:bg-[#C90040]' }} text-white text-xs font-black rounded-xl text-center transition-all shadow-md active:scale-95 flex items-center justify-center gap-1"
                                                title="{{ $roomStatus === 'terisi' ? 'Kamar terisi saat ini, booking sekarang untuk periode mendatang' : 'Pesan kamar ini sekarang' }}"
                                            >
                                                <i class="fa-solid fa-bolt text-[#F3A833] text-[10px]"></i>
                                                <span>{{ $roomStatus === 'terisi' ? 'Booking Nanti' : 'Pesan' }}</span>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach

                        <!-- Empty state when filtered by Wilayah tab -->
                        <div id="no-kamar-filter" class="hidden col-span-1 sm:col-span-2 lg:col-span-3 bg-white/5 rounded-3xl p-10 text-center border border-white/10 text-white/70">
                            <i class="fa-solid fa-location-dot text-2xl text-[#F3A833] mb-2 block"></i>
                            <p class="text-xs font-bold text-white">Tidak ada kamar pada wilayah ini saat ini.</p>
                            <p class="text-[11px] text-white/60 mt-1">Silakan pilih "Semua Wilayah" atau hubungi kami untuk ketersediaan unit terbaru.</p>
                        </div>
                    </div>
                @else
                    <div class="bg-white/5 rounded-3xl p-12 text-center max-w-lg mx-auto border border-white/10 text-white/70">
                        <i class="fa-solid fa-bed text-3xl text-[#F3A833] mb-3 block"></i>
                        <h4 class="font-bold text-white text-sm">Belum Ada Kamar Tersedia</h4>
                        <p class="text-xs mt-1">Data kamar sedang diperbarui oleh pengelola. Silakan hubungi admin untuk ketersediaan unit terbaru.</p>
                    </div>
                @endif

            </div>
        </section>

        <!-- 8. 3 LANGKAH MUDAH SEWA KOS (HOW IT WORKS) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 reveal">
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-[10px] font-black uppercase tracking-[.25em] text-[#E60049] block mb-1">Panduan Praktis</span>
                <h2 class="text-3xl sm:text-4xl font-black text-[#3B2314]">3 Langkah Mudah Sewa Kos</h2>
                <p class="text-xs sm:text-sm text-[#7B6759] mt-2">
                    Proses pemesanan transparan tanpa birokrasi rumit, mulai dari pemilihan kamar hingga serah terima kunci.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                <!-- STEP 1 -->
                <div class="bg-white p-7 rounded-[2.5rem] border border-[#E9DDD2] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                    <div class="text-4xl font-black text-[#F3A833]/20 absolute top-5 right-6 select-none pointer-events-none">01</div>
                    <div class="w-12 h-12 rounded-2xl bg-[#E60049]/10 text-[#E60049] flex items-center justify-center text-xl font-black mb-5">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3 class="text-lg font-black text-[#3B2314]">1. Pilih Kamar Idaman</h3>
                    <p class="text-xs text-[#7B6759] mt-2 leading-relaxed">
                        Jelajahi foto asli, cek fasilitas lengkap, tarif sewa bulanan/tahunan, serta peta fasilitas sekitar kos secara transparan.
                    </p>
                </div>

                <!-- STEP 2 -->
                <div class="bg-white p-7 rounded-[2.5rem] border border-[#E9DDD2] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                    <div class="text-4xl font-black text-[#F3A833]/20 absolute top-5 right-6 select-none pointer-events-none">02</div>
                    <div class="w-12 h-12 rounded-2xl bg-[#F3A833]/15 text-[#3B2314] flex items-center justify-center text-xl font-black mb-5">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <h3 class="text-lg font-black text-[#3B2314]">2. Reservasi & Bayar Fleksibel</h3>
                    <p class="text-xs text-[#7B6759] mt-2 leading-relaxed">
                        Tentukan tanggal check-in, lalu bayar dengan QRIS, Transfer Bank BCA/Mandiri/BNI, atau opsi Bayar di Tempat (COD).
                    </p>
                </div>

                <!-- STEP 3 -->
                <div class="bg-white p-7 rounded-[2.5rem] border border-[#E9DDD2] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                    <div class="text-4xl font-black text-[#F3A833]/20 absolute top-5 right-6 select-none pointer-events-none">03</div>
                    <div class="w-12 h-12 rounded-2xl bg-[#00A896]/10 text-[#00A896] flex items-center justify-center text-xl font-black mb-5">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <h3 class="text-lg font-black text-[#3B2314]">3. Terima e-Ticket & Check-In</h3>
                    <p class="text-xs text-[#7B6759] mt-2 leading-relaxed">
                        Dapatkan tiket reservasi digital & invoice PDF seketika. Tunjukkan kode booking kepada petugas saat serah terima kunci kos.
                    </p>
                </div>
            </div>
        </section>

        <!-- 9. TESTIMONIALS SECTION -->
        <section class="bg-[#FFF1E2] py-16 md:py-20 overflow-hidden reveal border-y border-[#E9DDD2]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-5 mb-8">
                    <div>
                        <span class="text-[#00A896] text-xs font-black uppercase tracking-[.25em] block mb-1">Pengalaman Penghuni</span>
                        <h2 class="text-3xl sm:text-4xl font-black text-[#3B2314]">Apa Kata Penghuni Kami?</h2>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" class="manual-testimoni-scroll prev w-10 h-10 rounded-xl bg-white text-[#3B2314] hover:bg-[#E60049] hover:text-white transition-all shadow-sm border border-[#E9DDD2] flex items-center justify-center active:scale-95" aria-label="Testimoni sebelumnya">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        <button type="button" class="manual-testimoni-scroll next w-10 h-10 rounded-xl bg-white text-[#3B2314] hover:bg-[#E60049] hover:text-white transition-all shadow-sm border border-[#E9DDD2] flex items-center justify-center active:scale-95" aria-label="Testimoni berikutnya">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto no-scrollbar scroll-smooth pb-3" id="testimonial-scroller">
                    <div class="flex gap-5 py-2">
                        @forelse($testimonis as $t)
                            @php
                                $profileImg = $t->image_profile
                                    ? asset('storage/' . $t->image_profile)
                                    : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80';
                                $ratingVal = max(1, min(5, (int) $t->rating));
                            @endphp

                            <div class="w-80 bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow shrink-0 space-y-4 border border-[#E9DDD2]">
                                <div class="flex items-center justify-between">
                                    <div class="flex text-[#F3A833] text-xs gap-1">
                                        @for($i = 1; $i <= $ratingVal; $i++)
                                            <i class="fa-solid fa-star"></i>
                                        @endfor
                                        @for($j = $ratingVal + 1; $j <= 5; $j++)
                                            <i class="fa-regular fa-star text-[#E9DDD2]"></i>
                                        @endfor
                                    </div>
                                    <i class="fa-solid fa-quote-right text-[#E60049]/20 text-xl"></i>
                                </div>

                                <p class="text-[#7B6759] text-xs leading-relaxed font-medium line-clamp-4">
                                    "{{ $t->review }}"
                                </p>

                                <div class="flex items-center gap-3 pt-4 border-t border-[#E9DDD2]">
                                    <img src="{{ $profileImg }}" alt="{{ $t->name }}" loading="lazy" class="w-10 h-10 rounded-full object-cover shadow-sm border-2 border-[#F3A833]/40">
                                    <div>
                                        <h4 class="font-black text-[#3B2314] text-xs">{{ $t->name }}</h4>
                                        <span class="text-[10px] text-[#00A896] font-bold">Penghuni Terverifikasi</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="w-80 bg-white p-6 rounded-[2rem] shadow-sm shrink-0 space-y-3 border border-[#E9DDD2]">
                                <div class="flex text-[#F3A833] text-xs gap-1">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                </div>
                                <p class="text-[#7B6759] text-xs leading-relaxed font-medium">
                                    "Sangat mudah menemukan kos yang nyaman dan fasilitas lengkap di Sinar Citra Lestari. Pelayanannya cepat dan terpercaya!"
                                </p>
                                <div class="flex items-center gap-3 pt-3 border-t border-[#E9DDD2]">
                                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80" alt="Penghuni SCL" class="w-9 h-9 rounded-full object-cover">
                                    <div>
                                        <h4 class="font-black text-[#3B2314] text-xs">Penghuni SCL</h4>
                                        <span class="text-[10px] text-[#00A896] font-bold">Penghuni Terverifikasi</span>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </section>

        <!-- 10. NEWS & ARTIKEL SECTION -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 reveal">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-9">
                <div>
                    <span class="text-[#E60049] text-xs font-black uppercase tracking-[.25em] block mb-1">Kabar & Informasi</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-[#3B2314]">Artikel & Tips Hunian</h2>
                </div>

                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#3B2314] text-white text-xs font-black hover:bg-[#E60049] transition-all shadow-sm group">
                    <span>Lihat Semua Artikel</span>
                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                @forelse($artikels as $index => $art)
                    @php
                        $artImg = $art->image
                            ? asset('storage/' . $art->image)
                            : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
                    @endphp

                    @if($index === 0)
                        <div class="lg:col-span-7 bg-[#3B2314] rounded-[2.5rem] overflow-hidden min-h-[400px] relative group shadow-xl">
                            <img src="{{ $artImg }}" alt="{{ $art->title }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#24150D] via-[#24150D]/30 to-transparent"></div>
                            <div class="relative min-h-[400px] flex flex-col justify-end p-6 sm:p-8">
                                <span class="self-start bg-[#F3A833] text-[#3B2314] text-[10px] font-black px-3 py-1 rounded-full mb-3">
                                    Informasi Terbaru
                                </span>
                                <div class="text-white/70 text-xs font-semibold">
                                    {{ $art->created_at ? $art->created_at->translatedFormat('d F Y') : 'Terbaru' }}
                                </div>
                                <h3 class="mt-1 font-black text-white text-xl sm:text-2xl leading-tight">
                                    <a href="{{ route('news.detail', $art->slug) }}" class="hover:text-[#F3A833] transition-colors">{{ $art->title }}</a>
                                </h3>
                                <p class="text-white/70 text-xs mt-2 max-w-xl line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($art->deskripsi ?? 'Baca selengkapnya artikel menarik seputar hunian.'), 120) }}
                                </p>
                                <a href="{{ route('news.detail', $art->slug) }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-black text-[#F3A833] hover:text-white transition-colors" aria-label="Baca selengkapnya artikel {{ $art->title }}">
                                    <span>Baca Selengkapnya</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="lg:col-span-5 bg-white rounded-[2rem] border border-[#E9DDD2] shadow-sm hover:shadow-md transition-all overflow-hidden group flex flex-col sm:flex-row lg:flex-col">
                            <div class="relative overflow-hidden h-48 sm:h-auto lg:h-44 sm:w-5/12 lg:w-full shrink-0 bg-[#EDE4DC]">
                                <img src="{{ $artImg }}" alt="{{ $art->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <span class="absolute top-3 left-3 bg-[#E60049] text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full">Kabar</span>
                            </div>

                            <div class="p-5 flex flex-col justify-center">
                                <div class="text-[10px] text-[#8E7B6D] font-bold mb-1">
                                    {{ $art->created_at ? $art->created_at->translatedFormat('d F Y') : 'Terbaru' }}
                                </div>
                                <h3 class="font-black text-[#3B2314] text-base line-clamp-2 hover:text-[#E60049] transition-colors">
                                    <a href="{{ route('news.detail', $art->slug) }}">{{ $art->title }}</a>
                                </h3>
                                <p class="text-[#7B6759] text-xs mt-2 line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($art->deskripsi ?? 'Baca selengkapnya artikel menarik seputar hunian.'), 90) }}
                                </p>
                                <a href="{{ route('news.detail', $art->slug) }}" class="mt-3 text-xs font-black text-[#00A896] hover:text-[#E60049] transition-colors inline-flex items-center gap-1" aria-label="Baca artikel {{ $art->title }}">
                                    <span>Baca</span>
                                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="bg-white rounded-[2rem] border border-[#E9DDD2] p-10 text-center lg:col-span-12 shadow-sm text-[#8E7B6D]">
                        <i class="fa-solid fa-newspaper text-3xl text-[#F3A833] mb-3 block"></i>
                        <h4 class="font-bold text-sm">Belum Ada Artikel</h4>
                        <p class="text-xs mt-1">Tips seputar hunian kos akan segera kami hadirkan.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- 11. HIGH-CONVERTING PRE-FOOTER CTA BANNER -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 md:pb-20 reveal">
            <div class="bg-[#3B2314] rounded-[2.5rem] sm:rounded-[3rem] p-8 sm:p-12 lg:p-16 text-white relative overflow-hidden shadow-2xl border border-white/10">
                <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-[#F3A833]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-16 -top-16 w-80 h-80 bg-[#E60049]/15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 text-[#F3A833] text-xs font-black uppercase tracking-wider mb-4 border border-white/10">
                            <i class="fa-brands fa-whatsapp text-sm"></i> Layanan Konsultasi Gratis
                        </span>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight">
                            Butuh Rekomendasi Kamar atau Ingin Survei Lokasi?
                        </h2>
                        <p class="text-xs sm:text-sm text-white/75 mt-3 leading-relaxed">
                            Hubungi tim reservasi kami di WhatsApp. Kami siap merekomendasikan unit kamar terbaik sesuai preferensi anggaran dan kebutuhan mobilitas Anda.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                        @php
                            $adminWa = '6282146138847';
                            if (isset($globalSosmed)) {
                                $waItem = $globalSosmed->first(function ($s) {
                                    $t = strtolower($s->title ?? $s->name ?? $s->platform ?? '');
                                    return str_contains($t, 'whatsapp') || $t === 'wa' || str_contains($t, 'wa ');
                                });
                                if ($waItem && !empty($waItem->url ?? $waItem->link ?? '')) {
                                    $digits = preg_replace('/[^0-9]/', '', $waItem->url ?? $waItem->link ?? '');
                                    if (!empty($digits)) {
                                        $adminWa = $digits;
                                    }
                                }
                            }
                            $waText = "Halo Admin Sinar Citra Lestari, saya tertarik untuk mencari kamar kos dan ingin konsultasi/survei lokasi. Mohon informasinya.";
                            $waUrl = "https://wa.me/" . $adminWa . "?text=" . rawurlencode($waText);
                        @endphp
                        <a
                            href="{{ $waUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="shine px-7 py-4 bg-[#00A896] hover:bg-[#008f80] text-white font-black text-xs sm:text-sm rounded-2xl transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2.5"
                        >
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span>Chat WhatsApp Admin</span>
                        </a>

                        <a
                            href="{{ route('kosan.index') }}"
                            class="px-6 py-4 bg-white/10 hover:bg-white/20 text-white font-black text-xs sm:text-sm rounded-2xl transition-all border border-white/20 active:scale-95 flex items-center justify-center gap-2"
                        >
                            <span>Jelajahi Katalog Kos</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

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
        window.addEventListener('scroll', revealOnScroll, { passive: true });
        window.addEventListener('load', revealOnScroll);

        // Filter Wilayah Tabs for Kamar
        const filterBtns = document.querySelectorAll('.filter-btn');
        const kosCards = document.querySelectorAll('.kos-card');
        const noFilterAlert = document.getElementById('no-kamar-filter');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('bg-[#F3A833]', 'text-[#3B2314]', 'shadow-md');
                    b.classList.add('bg-white/5', 'text-white/75');
                });

                btn.classList.remove('bg-white/5', 'text-white/75');
                btn.classList.add('bg-[#F3A833]', 'text-[#3B2314]', 'shadow-md');

                const filter = btn.getAttribute('data-filter');
                let visibleCount = 0;

                kosCards.forEach(card => {
                    const show = filter === 'semua' || card.getAttribute('data-wilayah') === filter;
                    if (show) {
                        card.style.display = 'flex';
                        requestAnimationFrame(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        });
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (noFilterAlert) {
                    if (visibleCount === 0) {
                        noFilterAlert.classList.remove('hidden');
                    } else {
                        noFilterAlert.classList.add('hidden');
                    }
                }
            });
        });

        // Quick search keyword helper
        function setQuickSearch(keyword) {
            const input = document.getElementById('hero-search-input');
            if (input) {
                input.value = keyword;
                input.closest('form').submit();
            }
        }

        // Testimonial Scroller
        const testimonialScroller = document.getElementById('testimonial-scroller');
        const testimonialPrev = document.querySelector('.manual-testimoni-scroll.prev');
        const testimonialNext = document.querySelector('.manual-testimoni-scroll.next');

        if (testimonialScroller && testimonialPrev && testimonialNext) {
            testimonialPrev.addEventListener('click', () => {
                testimonialScroller.scrollBy({ left: -320, behavior: 'smooth' });
            });
            testimonialNext.addEventListener('click', () => {
                testimonialScroller.scrollBy({ left: 320, behavior: 'smooth' });
            });
        }

        // Flash Modal Close
        function closeSuccessModal() {
            const modal = document.getElementById('flash-success-modal');
            if (modal) modal.remove();
        }

        function closeFailedModal() {
            const modal = document.getElementById('flash-failed-modal');
            if (modal) modal.remove();
        }

        // Keyboard Escape listener for modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSuccessModal();
                closeFailedModal();
            }
        });
    </script>

</body>
</html>
