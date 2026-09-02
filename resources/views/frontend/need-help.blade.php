<style>
    .help-menu-enter {
        opacity: 0 !important;
        transform: translateY(20px) scale(0.95) !important;
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

<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-3">
    <!-- Action Options Container (Secara default tersembunyi dengan help-menu-enter) -->
    <div id="help-menu" class="help-menu-enter transition-all duration-300 flex flex-col items-end space-y-3">
        <!-- Option 1: Book a Room -->
        <a href="{{ url('/kamar/detail') }}" class="flex items-center space-x-3 group">
            <span class="bg-white text-slate-800 text-xs font-bold px-3 py-1.5 rounded-xl shadow-lg border border-slate-100 group-hover:bg-cyan-50 transition-colors">Book a Room</span>
            <div class="w-11 h-11 bg-cyan-600 text-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-calendar-check text-sm"></i>
            </div>
        </a>

        <!-- Option 2: Email Us -->
        <a href="mailto:naksibang3@gmail.com" class="flex items-center space-x-3 group">
            <span class="bg-white text-slate-800 text-xs font-bold px-3 py-1.5 rounded-xl shadow-lg border border-slate-100 group-hover:bg-cyan-50 transition-colors">Email Us</span>
            <div class="w-11 h-11 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-envelope text-sm"></i>
            </div>
        </a>

        <!-- Option 3: WhatsApp -->
        <a href="https://wa.me/6282146138847" target="_blank" rel="noopener noreferrer" class="flex items-center space-x-3 group">
            <div class="bg-white text-slate-800 text-xs font-bold px-3 py-1.5 rounded-xl shadow-lg border border-slate-100 group-hover:bg-cyan-50 transition-colors flex flex-col items-start">
                <span>Ask Local Expert</span>
                <span class="text-[9px] text-emerald-600 font-extrabold flex items-center gap-1">● REPLIES IN 5 MINS</span>
            </div>
            <div class="w-11 h-11 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="fa-brands fa-whatsapp text-lg"></i>
            </div>
        </a>
    </div>

    <!-- Main Floating Button Toggle -->
    <button id="help-toggle-btn" type="button" class="flex items-center gap-2.5 px-5 py-3 bg-cyan-700 text-white font-bold text-sm rounded-full shadow-2xl hover:bg-cyan-800 hover:scale-105 active:scale-95 transition-all duration-300">
        <i id="help-btn-icon" class="fa-solid fa-comment-dots text-base"></i>
        <span id="help-btn-text">Need Help?</span>
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
                    if (helpBtnText) helpBtnText.textContent = 'Close';
                } else {
                    helpMenu.classList.remove('help-menu-active');
                    helpMenu.classList.add('help-menu-enter');
                    if (helpBtnIcon) helpBtnIcon.className = 'fa-solid fa-comment-dots text-base';
                    if (helpBtnText) helpBtnText.textContent = 'Need Help?';
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

