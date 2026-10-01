<!-- LIGHTBOX GALERI FOTO (MODAL) -->
<div
    id="gallery-modal"
    class="fixed inset-0 bg-[#24150D]/95 z-[999] hidden flex-col justify-between p-4 sm:p-6 backdrop-blur-md transition-opacity duration-300 select-none"
    onclick="handleModalBackdropClick(event)"
>
    <!-- Modal Header -->
    <div class="flex items-center justify-between border-b border-white/10 pb-4 max-w-5xl mx-auto w-full">
        <div>
            <span class="text-[9px] font-extrabold uppercase tracking-[.2em] text-[#F3A833]">Galeri Foto Kamar</span>
            <h3 class="font-black text-white text-base sm:text-lg mt-0.5">{{ $namaKamar ?? 'Detail Kamar' }}</h3>
        </div>

        <div class="flex items-center gap-3">
            <span id="gallery-counter" class="text-xs sm:text-sm font-bold text-[#F1DCC8] px-3 py-1 bg-white/10 rounded-full">
                1 / 1
            </span>
            <button
                type="button"
                onclick="closeGallery()"
                class="w-10 h-10 rounded-xl bg-white/10 text-white hover:bg-[#E60049] transition-all flex items-center justify-center shadow-md active:scale-95"
                title="Tutup Galeri (Esc)"
            >
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>
    </div>

    <!-- Main Active Image & Prev/Next Arrows -->
    <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden max-w-5xl mx-auto w-full">
        <button
            type="button"
            onclick="prevImage()"
            class="absolute left-1 sm:left-4 w-12 h-12 rounded-2xl bg-white/15 text-white hover:bg-[#F3A833] hover:text-[#3B2314] transition-all flex items-center justify-center z-10 backdrop-blur-md shadow-lg active:scale-95"
            title="Foto Sebelumnya (Panah Kiri)"
        >
            <i class="fa-solid fa-chevron-left text-lg"></i>
        </button>

        <div class="w-full h-[62vh] sm:h-[68vh] flex items-center justify-center px-6 sm:px-16" id="gallery-image-container">
            <img
                id="active-gallery-img"
                src=""
                alt="Foto Kamar"
                class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl transition-all duration-300"
            >
        </div>

        <button
            type="button"
            onclick="nextImage()"
            class="absolute right-1 sm:right-4 w-12 h-12 rounded-2xl bg-white/15 text-white hover:bg-[#F3A833] hover:text-[#3B2314] transition-all flex items-center justify-center z-10 backdrop-blur-md shadow-lg active:scale-95"
            title="Foto Berikutnya (Panah Kanan)"
        >
            <i class="fa-solid fa-chevron-right text-lg"></i>
        </button>
    </div>

    <!-- Thumbnail Strip Bottom -->
    <div class="max-w-5xl mx-auto w-full overflow-x-auto no-scrollbar py-2">
        <div id="thumbnail-strip" class="flex items-center justify-center gap-2.5 min-w-max px-2"></div>
    </div>
</div>

<script>
    const galleryImages = @json($images ?? []);
    let currentGalleryIndex = 0;

    const galleryModal = document.getElementById('gallery-modal');
    const activeGalleryImg = document.getElementById('active-gallery-img');
    const galleryCounter = document.getElementById('gallery-counter');
    const thumbnailStrip = document.getElementById('thumbnail-strip');
    const galleryContainer = document.getElementById('gallery-image-container');

    function openGallery(index = 0) {
        if (!galleryImages || !galleryImages.length) return;

        currentGalleryIndex = Math.max(0, Math.min(index, galleryImages.length - 1));
        renderGallery();

        if (galleryModal) {
            galleryModal.classList.remove('hidden');
            galleryModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeGallery() {
        if (galleryModal) {
            galleryModal.classList.add('hidden');
            galleryModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function handleModalBackdropClick(event) {
        if (event.target === galleryModal || event.target === galleryContainer) {
            closeGallery();
        }
    }

    function renderGallery() {
        if (!galleryImages || !galleryImages.length || !activeGalleryImg) return;

        activeGalleryImg.style.opacity = '0';
        activeGalleryImg.style.transform = 'scale(0.97)';

        setTimeout(() => {
            activeGalleryImg.src = galleryImages[currentGalleryIndex];
            activeGalleryImg.style.opacity = '1';
            activeGalleryImg.style.transform = 'scale(1)';
        }, 120);

        if (galleryCounter) {
            galleryCounter.textContent = `${currentGalleryIndex + 1} / ${galleryImages.length} Foto`;
        }

        if (thumbnailStrip) {
            thumbnailStrip.innerHTML = galleryImages.map((img, idx) => `
                <div
                    onclick="setGalleryIndex(${idx})"
                    class="w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden cursor-pointer transition-all duration-200 ${
                        idx === currentGalleryIndex
                            ? 'border-2 border-[#F3A833] ring-2 ring-[#E60049] scale-105 shadow-xl opacity-100'
                            : 'border border-white/20 opacity-40 hover:opacity-100 hover:scale-100'
                    }"
                >
                    <img src="${img}" class="w-full h-full object-cover pointer-events-none">
                </div>
            `).join('');

            // Scroll active thumbnail into visible range
            const activeThumb = thumbnailStrip.children[currentGalleryIndex];
            if (activeThumb && typeof activeThumb.scrollIntoView === 'function') {
                activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
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

    // Keyboard Shortcuts
    document.addEventListener('keydown', (e) => {
        if (galleryModal && !galleryModal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeGallery();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
        }
    });

    // Touch Swipe Gesture for Smartphone
    let touchStartX = 0;
    let touchEndX = 0;

    if (galleryModal) {
        galleryModal.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        galleryModal.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipeGesture();
        }, { passive: true });
    }

    function handleSwipeGesture() {
        const threshold = 40;
        if (touchEndX < touchStartX - threshold) {
            nextImage(); // Swipe left -> next image
        } else if (touchEndX > touchStartX + threshold) {
            prevImage(); // Swipe right -> prev image
        }
    }
</script>
