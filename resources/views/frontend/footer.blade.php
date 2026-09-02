 <footer class="bg-[#5a6472] text-slate-200 py-4 shadow-[0_-8px_20px_rgba(0,0,0,0.15)] relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col lg:flex-row items-center justify-between gap-4 lg:gap-6 text-xs sm:text-sm">

            <!-- 1. Brand Logo & Name -->
            <a href="index.html" class="flex items-center gap-3 group transition-transform duration-300 hover:scale-105 shrink-0">
                <div class="w-9 h-9 bg-[#b0f2f6] rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-cyan-300/50 group-hover:shadow-md transition-all duration-300">
                <!-- GANTI FOTO LOGO FOOTER DI SINI (Ukuran ideal: 24x24 px) -->
                <img src="{{ asset('logo.png') }}" alt="NemuKOS Logo" loading="lazy" class="w-6 h-6 object-contain rounded">
                </div>
                <span class="text-base font-bold text-white tracking-wide group-hover:text-[#b0f2f6] transition-colors duration-300">NemuKOS</span>
            </a>

            <!-- 2. Tombol Media Sosial (Ikon Bergaya Kotak Membulat - Dynamic) -->
            <div class="flex items-center justify-center gap-3 text-slate-200">
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
                            $iconClass = 'fa-globe'; // fallback icon
                            foreach ($sosmedIconMap as $key => $icon) {
                                if (str_contains($titleLower, $key)) {
                                    $iconClass = $icon;
                                    break;
                                }
                            }
                        @endphp
                        <a href="{{ $sm->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $sm->title }}" title="{{ $sm->title }}" class="w-10 h-10 rounded-2xl bg-[#4d5663] hover:bg-[#b0f2f6] hover:text-[#5a6472] flex items-center justify-center text-white transition-all duration-300 shadow-sm hover:scale-105">
                            <i class="fa-brands {{ $iconClass }} text-base"></i>
                        </a>
                    @endforeach
                @else
                    <!-- Fallback default sosial media -->
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="w-10 h-10 rounded-2xl bg-[#4d5663] hover:bg-[#b0f2f6] hover:text-[#5a6472] flex items-center justify-center text-white transition-all duration-300 shadow-sm hover:scale-105">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>
                    <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="w-10 h-10 rounded-2xl bg-[#4d5663] hover:bg-[#b0f2f6] hover:text-[#5a6472] flex items-center justify-center text-white transition-all duration-300 shadow-sm hover:scale-105">
                        <i class="fa-brands fa-facebook-f text-base"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" aria-label="Twitter" class="w-10 h-10 rounded-2xl bg-[#4d5663] hover:bg-[#b0f2f6] hover:text-[#5a6472] flex items-center justify-center text-white transition-all duration-300 shadow-sm hover:scale-105">
                        <i class="fa-brands fa-twitter text-base"></i>
                    </a>
                @endif
            </div>

            <!-- 3. Copyright Text -->
            <div class="text-slate-300 text-[11px] sm:text-xs shrink-0 text-center lg:text-right">
                © 2026 Sinar Cahaya Lestari. All rights reserved.
            </div>

            </div>

        </div>
    </footer>
