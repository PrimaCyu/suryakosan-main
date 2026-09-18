<!-- NAVBAR CONTAINER (STICKY HEADER) -->
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

            <!-- Navigation Menu -->
            <nav class="flex items-center space-x-1 sm:space-x-2 shrink-0">
                <a
                    href="{{ url('/') }}"
                    class="group relative inline-flex items-center justify-center overflow-hidden
                           px-3 py-2 sm:px-4 sm:py-2.5
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
                           px-3 py-2 sm:px-4 sm:py-2.5
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
                           px-3 py-2 sm:px-4 sm:py-2.5
                           text-xs sm:text-sm font-extrabold
                           rounded-xl transition-all duration-200
                           {{ Request::is('news*') 
                               ? 'bg-[#F3A833] text-[#3B2314] shadow-md shadow-[#F3A833]/25' 
                               : 'text-[#3B2314] hover:text-[#F3A833] hover:bg-[#F3A833]/12' }}"
                >
                    Berita
                </a>
                <a
                    href="https://wa.me/6282146138847"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="ml-1 sm:ml-2 px-3.5 py-2 sm:px-5 sm:py-2.5 bg-[#3B2314] hover:bg-[#E60049] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center gap-1.5 sm:gap-2"
                >
                    <i class="fa-brands fa-whatsapp text-[#F3A833] text-sm"></i>
                    <span class="hidden sm:inline">Hubungi Kami</span>
                    <span class="sm:hidden">Kontak</span>
                </a>
            </nav>

        </div>
    </div>
</header>