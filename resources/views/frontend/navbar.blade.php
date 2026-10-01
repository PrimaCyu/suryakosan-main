<!-- NAVBAR CONTAINER (RESPONSIVE STICKY HEADER) -->
@php
    $waNumber = '6282146138847'; // default fallback
    if (isset($globalSosmed)) {
        $waSosmed = $globalSosmed->first(function ($s) {
            $t = strtolower($s->title ?? $s->name ?? $s->platform ?? '');
            return str_contains($t, 'whatsapp') || $t === 'wa' || str_contains($t, 'wa ');
        });
        if ($waSosmed && !empty($waSosmed->url ?? $waSosmed->link ?? '')) {
            $link = $waSosmed->url ?? $waSosmed->link ?? '';
            // Extract angka dari URL wa.me atau dari field langsung
            $extracted = preg_replace('/[^0-9]/', '', $link);
            if (!empty($extracted)) {
                $waNumber = $extracted;
            }
        }
    }
@endphp

<style>
    /* DESKTOP / PC (Layar >= 768px): Menu horizontal selalu tampil, hamburger SELALU tersembunyi */
    @media (min-width: 768px) {
        .scl-desktop-nav {
            display: flex !important;
        }
        .scl-mobile-toggle {
            display: none !important;
        }
        .scl-mobile-menu {
            display: none !important;
        }
    }

    /* DEVICE SELAIN PC (HP / Layar Kecil < 768px): Menu horizontal sembunyi, hamburger otomatis muncul */
    @media (max-width: 767.98px) {
        .scl-desktop-nav {
            display: none !important;
        }
        .scl-mobile-toggle {
            display: flex !important;
        }
    }
</style>

<header class="sticky top-0 z-50 bg-[#FFF8F1]/95 backdrop-blur-md border-b border-[#E9DDD2] shadow-[0_4px_25px_rgba(59,35,20,0.06)] transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Brand Logo & Title -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 sm:gap-3.5 group transition-all duration-300 shrink-0" title="Sinar Citra Lestari">
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-2xl bg-white flex items-center justify-center p-1.5 shadow-sm border border-[#E9DDD2] group-hover:border-[#E60049]/60 group-hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('scl.png') }}" alt="Sinar Citra Lestari Logo" loading="lazy" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-sm sm:text-base md:text-lg font-black tracking-tight text-[#3B2314] group-hover:text-[#E60049] transition-colors">
                        Sinar Citra <span class="text-[#E60049]">Lestari</span>
                    </span>
                    <span class="text-[9px] sm:text-[10px] font-bold text-[#8E7B6D] tracking-widest uppercase hidden xs:inline-block">
                        Residence &amp; Living
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation Menu (Tampil otomatis di PC) -->
            <nav class="scl-desktop-nav hidden md:flex items-center space-x-1 sm:space-x-2 shrink-0">
                <a
                    href="{{ url('/') }}"
                    class="group relative inline-flex items-center justify-center overflow-hidden
                           px-3.5 py-2 sm:px-4 sm:py-2.5
                           text-xs sm:text-sm font-extrabold
                           rounded-xl transition-all duration-200
                           {{ Request::is('/') 
                               ? 'bg-[#E60049] text-white shadow-md shadow-[#E60049]/25' 
                               : 'text-[#3B2314] hover:text-[#E60049] hover:bg-[#F3A833]/15' }}"
                >
                    Beranda
                </a>
                <a
                    href="{{ route('kosan.index') }}"
                    class="group relative inline-flex items-center justify-center
                           px-3.5 py-2 sm:px-4 sm:py-2.5
                           text-xs sm:text-sm font-extrabold
                           rounded-xl transition-all duration-200
                           {{ Request::is('kosan*') || Request::is('kamar*') 
                               ? 'bg-[#00A896] text-white shadow-md shadow-[#00A896]/25' 
                               : 'text-[#3B2314] hover:text-[#00A896] hover:bg-[#00A896]/10' }}"
                >
                    Katalog Kos
                </a>
                <a
                    href="{{ route('news.index') }}"
                    class="group relative inline-flex items-center justify-center
                           px-3.5 py-2 sm:px-4 sm:py-2.5
                           text-xs sm:text-sm font-extrabold
                           rounded-xl transition-all duration-200
                           {{ Request::is('news*') 
                               ? 'bg-[#F3A833] text-[#3B2314] shadow-md shadow-[#F3A833]/25' 
                               : 'text-[#3B2314] hover:text-[#F3A833] hover:bg-[#F3A833]/12' }}"
                >
                    Berita
                </a>
                <a
                    href="https://wa.me/{{ $waNumber }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="ml-1 sm:ml-2 px-3.5 py-2 sm:px-5 sm:py-2.5 bg-[#3B2314] hover:bg-[#E60049] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center gap-1.5 sm:gap-2"
                >
                    <i class="fa-brands fa-whatsapp text-[#F3A833] text-sm"></i>
                    <span>Hubungi Kami</span>
                </a>
            </nav>

            <!-- Mobile Hamburger Button (Otomatis hanya muncul di device selain PC / layar kecil) -->
            <button
                type="button"
                id="scl-hamburger-btn"
                onclick="toggleMobileNav()"
                class="scl-mobile-toggle md:hidden w-10 h-10 rounded-xl bg-white hover:bg-[#E60049]/10 border border-[#E9DDD2] flex items-center justify-center text-[#3B2314] hover:text-[#E60049] transition-all duration-200 shadow-sm active:scale-95"
                aria-label="Buka Menu Navigasi"
            >
                <i id="scl-hamburger-icon" class="fa-solid fa-bars text-base transition-transform duration-200"></i>
            </button>

        </div>

        <!-- Mobile Navigation Drawer (Hanya terbuka ketika tombol hamburger diklik di mobile) -->
        <div id="mobile-nav" class="scl-mobile-menu hidden md:hidden pb-4 border-t border-[#E9DDD2] mt-1 pt-3 space-y-2">
            <a
                href="{{ url('/') }}"
                class="block px-4 py-2.5 rounded-xl text-sm font-extrabold transition-all duration-200
                       {{ Request::is('/') 
                           ? 'bg-[#E60049] text-white shadow-md' 
                           : 'text-[#3B2314] hover:bg-[#F3A833]/15' }}"
            >
                <i class="fa-solid fa-house mr-2 text-xs"></i>Beranda
            </a>
            <a
                href="{{ route('kosan.index') }}"
                class="block px-4 py-2.5 rounded-xl text-sm font-extrabold transition-all duration-200
                       {{ Request::is('kosan*') || Request::is('kamar*') 
                           ? 'bg-[#00A896] text-white shadow-md' 
                           : 'text-[#3B2314] hover:bg-[#00A896]/10' }}"
            >
                <i class="fa-solid fa-building mr-2 text-xs"></i>Katalog Kos
            </a>
            <a
                href="{{ route('news.index') }}"
                class="block px-4 py-2.5 rounded-xl text-sm font-extrabold transition-all duration-200
                       {{ Request::is('news*') 
                           ? 'bg-[#F3A833] text-[#3B2314] shadow-md' 
                           : 'text-[#3B2314] hover:bg-[#F3A833]/12' }}"
            >
                <i class="fa-solid fa-newspaper mr-2 text-xs"></i>Berita
            </a>
            <a
                href="https://wa.me/{{ $waNumber }}"
                target="_blank"
                rel="noopener noreferrer"
                class="block px-4 py-3 bg-[#3B2314] hover:bg-[#E60049] text-white font-extrabold text-sm rounded-xl shadow-md transition-all duration-200 text-center"
            >
                <i class="fa-brands fa-whatsapp text-[#F3A833] mr-2"></i>Hubungi Kami
            </a>
        </div>
    </div>
</header>

<script>
    function toggleMobileNav() {
        const nav = document.getElementById('mobile-nav');
        const icon = document.getElementById('scl-hamburger-icon');
        if (nav) {
            const isHidden = nav.classList.contains('hidden');
            if (isHidden) {
                nav.classList.remove('hidden');
                if (icon) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-xmark');
                }
            } else {
                nav.classList.add('hidden');
                if (icon) {
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                }
            }
        }
    }
</script>