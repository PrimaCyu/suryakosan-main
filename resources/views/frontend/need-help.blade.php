<style>
    .help-menu-enter {
        opacity: 0 !important;
        transform: translateY(16px) scale(0.95) !important;
        pointer-events: none !important;
        visibility: hidden !important;
    }
    .help-menu-active {
        opacity: 1 !important;
        transform: translateY(0) scale(1) !important;
        pointer-events: auto !important;
        visibility: visible !important;
    }
</style>

<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-3 font-sans">
    <!-- Action Options Container -->
    <div id="help-menu" class="help-menu-enter transition-all duration-300 flex flex-col items-end space-y-2.5">
        <!-- Option 1: Explore & Book Rooms -->
        <a href="{{ route('kosan.index') }}" class="flex items-center space-x-3 group">
            <span class="bg-white text-slate-800 text-xs font-bold px-3.5 py-2 rounded-2xl shadow-lg border border-slate-100 group-hover:bg-cyan-50 group-hover:text-cyan-700 transition-colors">
                🔍 Cari Kamar Kos
            </span>
            <div class="w-11 h-11 bg-cyan-600 text-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:bg-cyan-700 transition-transform">
                <i class="fa-solid fa-bed text-sm"></i>
            </div>
        </a>

        <!-- Option 2: Email Us -->
        <a href="mailto:info@nemukos.com" class="flex items-center space-x-3 group">
            <span class="bg-white text-slate-800 text-xs font-bold px-3.5 py-2 rounded-2xl shadow-lg border border-slate-100 group-hover:bg-rose-50 group-hover:text-rose-700 transition-colors">
                ✉️ Email Dukungan
            </span>
            <div class="w-11 h-11 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:bg-rose-600 transition-transform">
                <i class="fa-solid fa-envelope text-sm"></i>
            </div>
        </a>

        <!-- Option 3: WhatsApp Chat -->
        <a href="https://wa.me/6282146138847?text=Halo%20Admin%20NemuKOS,%20saya%20ingin%20bertanya%20seputar%20kamar%20kos" target="_blank" rel="noopener noreferrer" class="flex items-center space-x-3 group">
            <div class="bg-white text-slate-800 text-xs font-bold px-3.5 py-2 rounded-2xl shadow-lg border border-slate-100 group-hover:bg-emerald-50 transition-colors flex flex-col items-start">
                <span class="group-hover:text-emerald-700">Chat WhatsApp</span>
                <span class="text-[9px] text-emerald-600 font-extrabold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Respon Cepat
                </span>
            </div>
            <div class="w-11 h-11 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:bg-emerald-600 transition-transform">
                <i class="fa-brands fa-whatsapp text-lg"></i>
            </div>
        </a>
    </div>

    <!-- Main Floating Button Toggle -->
    <button id="help-toggle-btn" type="button" aria-label="Bantuan" class="flex items-center gap-2.5 px-5 py-3 bg-slate-900 hover:bg-cyan-600 text-white font-bold text-xs sm:text-sm rounded-full shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
        <i id="help-btn-icon" class="fa-solid fa-comment-dots text-base"></i>
        <span id="help-btn-text">Bantuan</span>
    </button>
</div>

<script>
    (function initGlobalHelpToggle() {
        function setup() {
            const helpToggleBtn = document.getElementById('help-toggle-btn');
            const helpMenu = document.getElementById('help-menu');
            const helpBtnIcon = document.getElementById('help-btn-icon');
            const helpBtnText = document.getElementById('help-btn-text');

            if (!helpToggleBtn || !helpMenu || helpToggleBtn.dataset.initialized) return;
            helpToggleBtn.dataset.initialized = "true";

            let isHelpOpen = false;

            helpToggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                isHelpOpen = !isHelpOpen;

                if (isHelpOpen) {
                    helpMenu.classList.remove('help-menu-enter');
                    helpMenu.classList.add('help-menu-active');
                    if (helpBtnIcon) helpBtnIcon.className = 'fa-solid fa-xmark text-base';
                    if (helpBtnText) helpBtnText.textContent = 'Tutup';
                } else {
                    helpMenu.classList.remove('help-menu-active');
                    helpMenu.classList.add('help-menu-enter');
                    if (helpBtnIcon) helpBtnIcon.className = 'fa-solid fa-comment-dots text-base';
                    if (helpBtnText) helpBtnText.textContent = 'Bantuan';
                }
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (isHelpOpen && !helpToggleBtn.contains(e.target) && !helpMenu.contains(e.target)) {
                    isHelpOpen = false;
                    helpMenu.classList.remove('help-menu-active');
                    helpMenu.classList.add('help-menu-enter');
                    if (helpBtnIcon) helpBtnIcon.className = 'fa-solid fa-comment-dots text-base';
                    if (helpBtnText) helpBtnText.textContent = 'Bantuan';
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setup);
        } else {
            setup();
        }
    })();
</script>
