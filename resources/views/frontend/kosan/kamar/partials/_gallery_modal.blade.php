<!-- LIGHTBOX GALERI FOTO (MODAL) -->
<div id="gallery-modal" class="fixed inset-0 bg-white/95 z-[999] hidden flex-col justify-between p-4 sm:p-6 backdrop-blur-md">
    <!-- Modal Header -->
    <div class="flex items-center justify-between border-b pb-4">
        <h3 class="font-bold text-slate-900 text-base sm:text-lg">Foto Detail Kamar</h3>
        <span id="gallery-counter" class="text-xs sm:text-sm font-extrabold text-slate-600">1 / 1</span>
        <button onclick="closeGallery()" class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-black hover:text-white transition-all">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
    </div>

    <!-- Main Active Image & Prev/Next Arrows -->
    <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden">
        <button onclick="prevImage()" class="absolute left-2 sm:left-6 w-10 h-10 rounded-full bg-white/90 shadow-lg border border-slate-100 flex items-center justify-center text-slate-700 hover:bg-black hover:text-white transition-all z-10">
            <i class="fa-solid fa-chevron-left text-sm"></i>
        </button>

        <!-- Frame modal berbatas ukuran presisi dengan gambar utuh (object-contain) -->
        <div class="w-full max-w-4xl h-[65vh] rounded-2xl overflow-hidden shadow-2xl border border-slate-200 bg-slate-950 flex items-center justify-center p-2">
            <img id="active-gallery-img" src="" alt="Foto Kamar" class="max-w-full max-h-full w-auto h-auto object-contain rounded-xl">
        </div>

        <button onclick="nextImage()" class="absolute right-2 sm:right-6 w-10 h-10 rounded-full bg-white/90 shadow-lg border border-slate-100 flex items-center justify-center text-slate-700 hover:bg-black hover:text-white transition-all z-10">
            <i class="fa-solid fa-chevron-right text-sm"></i>
        </button>
    </div>

    <!-- Thumbnail Strip Bottom -->
    <div class="flex items-center justify-center gap-2 overflow-x-auto no-scrollbar py-2">
        <div id="thumbnail-strip" class="flex items-center gap-2"></div>
    </div>
</div>
