<!-- NAVBAR CONTAINER (STICKY HEADER) -->
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 sm:gap-3 group transition-transform duration-300 hover:scale-105 shrink-0">
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-teal-500/10 flex items-center justify-center p-1 shadow-sm border border-teal-100 group-hover:border-teal-300 transition-colors">
                    <img src="{{ asset('logo.png') }}" alt="Sinar Citra Lestari Logo" loading="lazy" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-base sm:text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-teal-700 transition-colors">Sinar Citra Lestari</span>
                    <span class="text-[9px] text-slate-500 font-semibold -mt-1 hidden sm:block tracking-wider uppercase">Hunian Eksklusif & Nyaman</span>
                </div>
            </a>

            <!-- Navigation Menu -->
            <nav class="flex items-center space-x-1 sm:space-x-2 shrink-0">
                <a href="{{ url('/') }}" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold rounded-full transition-all duration-200 {{ Request::is('/') ? 'bg-teal-50 text-teal-800 font-bold border border-teal-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Beranda
                </a>
                <a href="{{ route('kosan.index') }}" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold rounded-full transition-all duration-200 {{ Request::is('kosan*') || Request::is('kamar*') ? 'bg-teal-50 text-teal-800 font-bold border border-teal-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Kos-kosan
                </a>
                <a href="{{ route('news.index') }}" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold rounded-full transition-all duration-200 {{ Request::is('news*') ? 'bg-teal-50 text-teal-800 font-bold border border-teal-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    News & Event
                </a>
                <a href="https://wa.me/6282146138847" target="_blank" rel="noopener noreferrer" class="ml-1 px-3 py-1.5 sm:px-5 sm:py-2.5 bg-[#2B1810] hover:bg-teal-600 text-white font-semibold text-xs sm:text-sm rounded-full shadow-md hover:shadow-teal-500/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center gap-1.5 sm:gap-2">
                    <i class="fa-brands fa-whatsapp text-emerald-400 text-sm"></i>
                    <span class="hidden xs:inline">Hubungi Kami</span>
                    <span class="xs:hidden">Kontak</span>
                </a>
            </nav>

        </div>
    </div>
</header>
