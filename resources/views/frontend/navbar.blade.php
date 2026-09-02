 <!-- 2. NAVBAR CONTAINER (FIXED STICKY DI ATAS) -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-md transition-all duration-300 ease-in-out">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-1.5 sm:gap-3 group transition-transform duration-300 hover:scale-105 shrink-0">
                <img src="{{ asset('logo.png') }}" alt="NemuKOS Logo" loading="lazy" class="h-7 w-7 sm:h-10 sm:w-10 object-contain rounded-lg shadow-sm group-hover:shadow-md transition-shadow duration-300">
                <span class="text-base sm:text-xl font-bold tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors duration-300">NemuKOS</span>
            </a>

            <!-- Navigation Menu (Menyatu untuk Mobile & Desktop, Ukuran Menyesuaikan) -->
            <nav class="flex items-center space-x-1 sm:space-x-2 shrink-0">
                <a href="https://wa.me/6282146138847" target="_blank" rel="noopener noreferrer" class="px-2.5 py-1.5 sm:px-5 sm:py-2.5 bg-slate-900 text-white font-medium text-[11px] sm:text-sm rounded-full shadow-md hover:bg-slate-800 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 flex items-center gap-1 sm:gap-2">
                <span>Hubungi Kami</span>
                </a>
                <a href="{{ route('kosan.index') }}" class="px-2 py-1.5 sm:px-4 sm:py-2 text-[11px] sm:text-sm font-medium text-slate-700 rounded-full hover:text-slate-900 hover:bg-slate-100 transition-all duration-300 whitespace-nowrap {{ Request::is('kosan*') ? 'bg-slate-100 font-bold text-slate-900' : '' }}">
                Kos-kosan
                </a>
                <a href="{{ route('news.index') }}" class="px-2 py-1.5 sm:px-4 sm:py-2 text-[11px] sm:text-sm font-medium text-slate-700 rounded-full hover:text-slate-900 hover:bg-slate-100 transition-all duration-300 whitespace-nowrap {{ Request::is('news*') ? 'bg-slate-100 font-bold text-slate-900' : '' }}">
                News & Event
                </a>
            </nav>

            </div>
        </div>
    </header>
