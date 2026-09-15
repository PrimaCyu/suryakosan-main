<!-- NAVBAR CONTAINER (STICKY HEADER) -->
<header class="sticky top-0 z-50 bg-[#fffaf7]/95 backdrop-blur-md border-b border-[#3B2314]/10 shadow-[0_8px_30px_rgba(59,35,20,0.08)] transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 sm:gap-3 group transition-transform duration-300 hover:scale-105 shrink-0">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-gradient-to-br from-[#E60049]/20 to-[#F3A833]/30 flex items-center justify-center p-1.5 shadow-sm border border-[#F3A833]/40 group-hover:border-[#E60049]/70 transition-all duration-300">
                    <img src="{{ asset('scl.png') }}" alt="Sinar Citra Lestari Logo" loading="lazy" class="w-full h-full object-contain">
                </div>
            </a>

            <!-- Navigation Menu -->
            <nav class="flex items-center space-x-1 sm:space-x-2 shrink-0">
                <a
    href="{{ url('/') }}"
    class="group relative inline-flex items-center justify-center overflow-hidden
           px-3 py-2 sm:px-5 sm:py-2.5
           text-xs sm:text-sm font-extrabold
           rounded-xl
           transition-all duration-300 ease-out
           hover:-translate-y-1 hover:scale-[1.03]
           active:translate-y-0 active:scale-95
           {{ Request::is('/') 
               ? 'bg-[#E60049] text-white shadow-[0_8px_20px_rgba(230,0,73,0.30)]'
               : 'text-[#3B2314] hover:text-[#E60049] hover:bg-[#F3A833]/15 hover:shadow-[0_8px_20px_rgba(243,168,51,0.20)]' }}"
>
    <!-- Efek kilau saat hover -->
    <span
        class="absolute inset-0 -translate-x-full
               bg-gradient-to-r from-transparent via-white/30 to-transparent
               transition-transform duration-700
               group-hover:translate-x-full"
    ></span>

    <!-- Teks -->
    <span class="relative z-10">
        Beranda
    </span>

    <!-- Garis aksen kuning -->
    <span
        class="absolute bottom-1 left-1/2
               h-0.5 w-0 -translate-x-1/2
               rounded-full bg-[#F3A833]
               transition-all duration-300
               group-hover:w-1/2
               {{ Request::is('/') ? 'w-1/2' : '' }}"
    ></span>
</a>
                <a href="{{ route('kosan.index') }}" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-extrabold rounded-full transition-all duration-200 {{ Request::is('kosan*') || Request::is('kamar*') ? 'bg-[#00A896] text-white shadow-sm' : 'text-[#3B2314] hover:text-[#00A896] hover:bg-[#00A896]/10' }}">
                    Kos
                </a>
                <a href="{{ route('news.index') }}" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-extrabold rounded-full transition-all duration-200 {{ Request::is('news*') ? 'bg-[#F3A833]/20 text-[#3B2314] border border-[#F3A833]/40' : 'text-[#3B2314] hover:text-[#E60049] hover:bg-[#F3A833]/12' }}">
                    Berita
                </a>
                <a href="https://wa.me/6282146138847" target="_blank" rel="noopener noreferrer" class="ml-1 px-3 py-1.5 sm:px-5 sm:py-2.5 bg-[#3B2314] hover:bg-[#E60049] text-white font-extrabold text-xs sm:text-sm rounded-full shadow-md shadow-[#3B2314]/20 hover:shadow-[#E60049]/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center gap-1.5 sm:gap-2">
                    <i class="fa-brands fa-whatsapp text-[#F3A833] text-sm"></i>
                    <span class="hidden xs:inline">Hubungi Kami</span>
                    <span class="xs:hidden">Kontak</span>
                </a>
            </nav>

        </div>
    </div>
</header>