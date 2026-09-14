<footer class="bg-[#3B2314] text-[#F8EFE6] border-t border-[#F3A833]/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7">

        <div class="flex flex-col sm:flex-row items-center justify-between gap-5">

            <!-- LOGO -->
            <a
                href="{{ route('login') }}"
                aria-label="Login Sinar Citra Lestari"
                class="group flex items-center gap-3 transition-all duration-300 hover:-translate-y-1"
            >
                <div class="w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center rounded-2xl bg-white p-1.5 shadow-[0_5px_15px_rgba(0,0,0,0.18)] transition-all duration-300 group-hover:scale-105">
                    <img
                        src="{{ asset('scl.png') }}"
                        alt="Sinar Citra Lestari Logo"
                        loading="lazy"
                        class="w-full h-full object-contain pointer-events-none"
                    >
                </div>

                <span class="text-base sm:text-lg font-black tracking-wide text-white transition-colors duration-300 group-hover:text-[#F3A833]">
                    SINAR CITRA LESTARI
                </span>
            </a>


            <!-- SOCIAL MEDIA -->
            <div class="flex flex-col items-center sm:items-end gap-2">

                <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#F8EFE6]/60">
                    Ikuti Kami
                </span>

                <div class="flex items-center gap-2.5">

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

                            <a
                                href="{{ $sm->url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="{{ $sm->title }}"
                                title="{{ $sm->title }}"
                                class="group w-10 h-10 rounded-xl
                                       bg-[#F8EFE6]/10
                                       border border-[#F8EFE6]/10
                                       flex items-center justify-center
                                       text-[#F8EFE6]
                                       cursor-pointer
                                       transition-all duration-300 ease-out
                                       hover:-translate-y-1
                                       hover:scale-105
                                       hover:bg-[#F3A833]
                                       hover:text-[#3B2314]
                                       hover:border-[#F3A833]
                                       hover:shadow-[0_8px_18px_rgba(243,168,51,0.30)]
                                       active:scale-90"
                            >
                                <i
                                    class="fa-brands {{ $iconClass }}
                                           text-sm
                                           pointer-events-none
                                           select-none
                                           transition-transform duration-300
                                           group-hover:scale-110"
                                ></i>
                            </a>

                        @endforeach

                    @else

                        <a
                            href="https://instagram.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Instagram"
                            class="group w-10 h-10 rounded-xl bg-[#F8EFE6]/10 border border-[#F8EFE6]/10 flex items-center justify-center text-[#F8EFE6] cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:scale-105 hover:bg-[#F3A833] hover:text-[#3B2314] hover:border-[#F3A833] hover:shadow-[0_8px_18px_rgba(243,168,51,0.30)] active:scale-90"
                        >
                            <i class="fa-brands fa-instagram text-sm pointer-events-none select-none"></i>
                        </a>

                        <a
                            href="https://facebook.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Facebook"
                            class="group w-10 h-10 rounded-xl bg-[#F8EFE6]/10 border border-[#F8EFE6]/10 flex items-center justify-center text-[#F8EFE6] cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:scale-105 hover:bg-[#F3A833] hover:text-[#3B2314] hover:border-[#F3A833] hover:shadow-[0_8px_18px_rgba(243,168,51,0.30)] active:scale-90"
                        >
                            <i class="fa-brands fa-facebook-f text-sm pointer-events-none select-none"></i>
                        </a>

                        <a
                            href="https://twitter.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Twitter"
                            class="group w-10 h-10 rounded-xl bg-[#F8EFE6]/10 border border-[#F8EFE6]/10 flex items-center justify-center text-[#F8EFE6] cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:scale-105 hover:bg-[#F3A833] hover:text-[#3B2314] hover:border-[#F3A833] hover:shadow-[0_8px_18px_rgba(243,168,51,0.30)] active:scale-90"
                        >
                            <i class="fa-brands fa-twitter text-sm pointer-events-none select-none"></i>
                        </a>

                    @endif

                </div>
            </div>

        </div>

        <!-- COPYRIGHT -->
        <div class="mt-6 pt-4 border-t border-[#F3A833]/15 text-center">
            <p class="text-[10px] sm:text-xs text-[#F8EFE6]/50">
                &copy; {{ date('Y') }} Sinar Citra Lestari. Seluruh hak cipta dilindungi.
            </p>
        </div>

    </div>
</footer>