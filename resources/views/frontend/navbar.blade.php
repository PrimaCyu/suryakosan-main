<!-- NAVBAR CONTAINER (STICKY HEADER) -->
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 sm:gap-3 group transition-transform duration-300 hover:scale-105 shrink-0">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center p-1.5 shadow-sm border border-cyan-100 group-hover:border-cyan-300 transition-colors">
                    <img src="{{ asset('logo.png') }}" alt="NemuKOS Logo" loading="lazy" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-base sm:text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-cyan-600 transition-colors">NemuKOS</span>
                    <span class="text-[9px] text-slate-400 font-semibold -mt-1 hidden sm:block tracking-wider uppercase">Sewa Kos & Kamar</span>
                </div>
            </a>

            <!-- Navigation Menu -->
            <nav class="flex items-center space-x-1 sm:space-x-2 shrink-0">
                <a href="{{ url('/') }}" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold rounded-full transition-all duration-200 {{ Request::is('/') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Beranda
                </a>
                <a href="{{ route('kosan.index') }}" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold rounded-full transition-all duration-200 {{ Request::is('kosan*') || Request::is('kamar*') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Kos-kosan
                </a>
                <a href="{{ route('news.index') }}" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold rounded-full transition-all duration-200 {{ Request::is('news*') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    News & Event
                </a>
                <a href="https://wa.me/6282146138847" target="_blank" rel="noopener noreferrer" class="ml-1 px-3 py-1.5 sm:px-5 sm:py-2.5 bg-slate-900 hover:bg-cyan-600 text-white font-semibold text-xs sm:text-sm rounded-full shadow-md hover:shadow-cyan-500/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center gap-1.5 sm:gap-2">
                    <i class="fa-brands fa-whatsapp text-emerald-400 text-sm"></i>
                    <span class="hidden xs:inline">Hubungi Kami</span>
                    <span class="xs:hidden">Kontak</span>
                </a>
            </nav>

        </div>
    </div>
</header>
