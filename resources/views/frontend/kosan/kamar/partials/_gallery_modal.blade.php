<!-- LIGHTBOX GALERI FOTO (MODAL) -->
<div id="gallery-modal" class="fixed inset-0 bg-slate-950/90 z-[999] hidden flex-col justify-between p-4 sm:p-6 backdrop-blur-md">
    <!-- Modal Header -->
    <div class="flex items-center justify-between border-b border-slate-800 pb-4 max-w-5xl mx-auto w-full">
        <h3 class="font-bold text-white text-base sm:text-lg">{{ $namaKamar ?? 'Foto Detail Kamar' }}</h3>
        <span id="gallery-counter" class="text-xs sm:text-sm font-bold text-slate-400">1 / 1</span>
        <button onclick="closeGallery()" class="w-9 h-9 rounded-full bg-slate-800 text-slate-300 hover:bg-white hover:text-slate-900 transition-all flex items-center justify-center">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
    </div>

    <!-- Main Active Image & Prev/Next Arrows -->
    <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden max-w-5xl mx-auto w-full">
        <button onclick="prevImage()" class="absolute left-2 sm:left-4 w-11 h-11 rounded-full bg-white/10 text-white hover:bg-white hover:text-slate-900 transition-all flex items-center justify-center z-10 backdrop-blur-md">
            <i class="fa-solid fa-chevron-left text-base"></i>
        </button>

        <div class="w-full h-[65vh] rounded-2xl overflow-hidden flex items-center justify-center p-2">
            <img id="active-gallery-img" src="" alt="Foto Kamar" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl">
        </div>

        <button onclick="nextImage()" class="absolute right-2 sm:right-4 w-11 h-11 rounded-full bg-white/10 text-white hover:bg-white hover:text-slate-900 transition-all flex items-center justify-center z-10 backdrop-blur-md">
            <i class="fa-solid fa-chevron-right text-base"></i>
        </button>
    </div>

    <!-- Thumbnail Strip Bottom -->
    <div class="flex items-center justify-center gap-2 overflow-x-auto no-scrollbar py-2 max-w-5xl mx-auto w-full">
        <div id="thumbnail-strip" class="flex items-center gap-2"></div>
    </div>
</div>

<script>
    const galleryImages = @json($images ?? []);
    let currentGalleryIndex = 0;
    const galleryModal = document.getElementById('gallery-modal');
    const activeGalleryImg = document.getElementById('active-gallery-img');
    const galleryCounter = document.getElementById('gallery-counter');
    const thumbnailStrip = document.getElementById('thumbnail-strip');

    function openGallery(index = 0) {
        if (!galleryImages || !galleryImages.length) return;
        currentGalleryIndex = index;
        renderGallery();
        if (galleryModal) {
            galleryModal.classList.remove('hidden');
            galleryModal.classList.add('flex');
        }
    }

    function closeGallery() {
        if (galleryModal) {
            galleryModal.classList.add('hidden');
            galleryModal.classList.remove('flex');
        }
    }

    function renderGallery() {
        if (!galleryImages || !galleryImages.length || !activeGalleryImg) return;
        activeGalleryImg.src = galleryImages[currentGalleryIndex];
        if (galleryCounter) galleryCounter.textContent = `${currentGalleryIndex + 1} / ${galleryImages.length}`;

        if (thumbnailStrip) {
            thumbnailStrip.innerHTML = galleryImages.map((img, idx) => `
                <div onclick="setGalleryIndex(${idx})" class="w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden cursor-pointer border-2 transition-all ${idx === currentGalleryIndex ? 'border-cyan-400 scale-105 shadow-md' : 'border-transparent opacity-50 hover:opacity-100'}">
                    <img src="${img}" class="w-full h-full object-cover">
                </div>
            `).join('');
        }
    }

    function setGalleryIndex(index) {
        currentGalleryIndex = index;
        renderGallery();
    }

    function prevImage() {
        if (!galleryImages || !galleryImages.length) return;
        currentGalleryIndex = (currentGalleryIndex - 1 + galleryImages.length) % galleryImages.length;
        renderGallery();
    }

    function nextImage() {
        if (!galleryImages || !galleryImages.length) return;
        currentGalleryIndex = (currentGalleryIndex + 1) % galleryImages.length;
        renderGallery();
    }

    document.addEventListener('keydown', (e) => {
        if (galleryModal && !galleryModal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeGallery();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
        }
    });
</script>
