<footer class="bg-slate-900 text-slate-300 py-10 border-t border-slate-800 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-slate-800/80">

            <!-- 1. Brand Logo & Name -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group transition-transform duration-300 hover:scale-105 shrink-0">
                <div class="w-10 h-10 bg-teal-500/10 rounded-2xl flex items-center justify-center border border-teal-500/20 shadow-sm group-hover:shadow-teal-500/20 group-hover:shadow-md transition-all">
                    <img src="{{ asset('logo.png') }}" alt="Sinar Citra Lestari Logo" loading="lazy" class="w-7 h-7 object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-bold text-white tracking-wide group-hover:text-teal-400 transition-colors">Sinar Citra Lestari</span>
                    <span class="text-[10px] text-slate-400">Hunian Nyaman, Asri & Terpercaya</span>
                </div>
            </a>

            <!-- 2. Quick Links -->
            <div class="flex flex-wrap items-center justify-center gap-6 text-xs sm:text-sm font-medium text-slate-300">
                <a href="{{ url('/') }}" class="hover:text-teal-400 transition-colors">Beranda</a>
                <a href="{{ route('kosan.index') }}" class="hover:text-teal-400 transition-colors">Daftar Kos</a>
                <a href="{{ route('news.index') }}" class="hover:text-teal-400 transition-colors">News & Events</a>
                <a href="https://wa.me/6282146138847" target="_blank" rel="noopener noreferrer" class="hover:text-teal-400 transition-colors">Bantuan</a>
            </div>

            <!-- 3. Social Media Buttons -->
            <div class="flex items-center justify-center gap-2.5">
                @php
                    $sosmedIconMap = [
                        'instagram' => 'fa-instagram',
                        'facebook'  => 'fa-facebook-f',
                        'twitter'   => 'fa-twitter',
                        'x'         => 'fa-x-twitter',
                        'youtube'   => 'fa-youtube',
                        'tiktok'    => 'fa-tiktok',
                        'linkedin'  => 'fa-linkedin-in',
                        'whatsapp'  => 'fa-whatsapp',
                        'telegram'  => 'fa-telegram',
                    ];
                @endphp

                @if(isset($globalSosmed) && $globalSosmed->count() > 0)
                    @foreach($globalSosmed as $sm)
                        @php
                            $titleLower = strtolower(trim($sm->title));
                            $iconClass = 'fa-globe';
                            foreach ($sosmedIconMap as $key => $icon) {
                                if (str_contains($titleLower, $key)) {
                                    $iconClass = $icon;
                                    break;
                                }
                            }
                        @endphp
                        <a href="{{ $sm->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $sm->title }}" title="{{ $sm->title }}" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-teal-600 hover:text-white flex items-center justify-center text-slate-300 transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fa-brands {{ $iconClass }} text-sm"></i>
                        </a>
                    @endforeach
                @else
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-teal-600 hover:text-white flex items-center justify-center text-slate-300 transition-all duration-200 hover:-translate-y-0.5">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-teal-600 hover:text-white flex items-center justify-center text-slate-300 transition-all duration-200 hover:-translate-y-0.5">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" aria-label="Twitter" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-teal-600 hover:text-white flex items-center justify-center text-slate-300 transition-all duration-200 hover:-translate-y-0.5">
                        <i class="fa-brands fa-twitter text-sm"></i>
                    </a>
                @endif
            </div>

        </div>

        <!-- 4. Copyright Bottom Bar -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 text-center sm:text-left">
            <p>&copy; {{ date('Y') }} Sinar Citra Lestari. Seluruh hak cipta dilindungi undang-undang.</p>
            <p class="text-[11px] text-slate-500">Hunian Nyaman, Harmonis & Bernilai Lestari.</p>
        </div>

    </div>
</footer>
