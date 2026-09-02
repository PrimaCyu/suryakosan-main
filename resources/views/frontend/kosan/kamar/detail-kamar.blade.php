<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NemuKOS - {{ $kamar->room ?? 'Detail Kamar' }}</title>
  <link rel="stylesheet" href="{{ asset('build/assets/app-D-OnOQL0.css') }}">
  <link rel="icon" href="{{ asset('logo.png') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    .reveal {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

    <!-- 1. BAR LOADING HALAMAN -->
    <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-cyan-500 z-[100] transition-all duration-500 ease-out"></div>

    <!-- 2. NAVBAR COMPONENT -->
    @include('frontend.navbar')

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

        @php
        $namaKosan = $kamar->productKosan->title ?? 'NemuKOS Properti';
        $namaKamar = $kamar->room ?? 'Kamar Exclusive';
        @endphp

        <!-- 3. BREADCRUMB NAVIGASI -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 reveal">
        <a href="{{ url('/') }}" class="hover:text-cyan-600 transition-colors">Kos-Kosan</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <span class="text-slate-500 font-medium">{{ $namaKosan }}</span>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <span class="text-slate-800 font-bold">{{ $namaKamar }}</span>
        </nav>

        <!-- 4. GALLERY GRID SECTION (PARTIAL) -->
        @include('frontend.kosan.kamar.partials._gallery')

        <!-- 5. KONTEN DETAIL & SIDEBAR HARGA -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-4">
        <!-- Deskripsi & Fasilitas Kamar (Partial) -->
        @include('frontend.kosan.kamar.partials._description_facilities')

        <!-- Sidebar Rincian Harga (Partial) -->
        @include('frontend.kosan.kamar.partials._sidebar_price')
        </div>

        <!-- 6. BOTTOM ACTION BUTTONS -->
        <div class="flex flex-col sm:flex-row items-center gap-3 pt-6 reveal">
        <a href="{{ route('form.booking.kamar',$kamar->id) }}" class="w-full sm:flex-1 py-4 bg-cyan-600 hover:bg-cyan-700 text-white text-xs sm:text-sm font-extrabold rounded-2xl transition-all shadow-md hover:shadow-lg text-center flex items-center justify-center gap-2 active:scale-95">
            Pesan Kamar Ini Sekarang <i class="fa-solid fa-chevron-right text-xs"></i>
        </a>
        <a id="btn-tanya-pemilik" href="#" target="_blank" class="w-full sm:w-auto px-8 py-4 bg-white border border-slate-200 hover:bg-slate-50 text-slate-800 text-xs sm:text-sm font-extrabold rounded-2xl transition-all shadow-sm hover:shadow text-center flex items-center justify-center gap-2 active:scale-95">
            <i class="fa-regular fa-comment-dots text-base"></i> Tanya Pemilik
        </a>
        </div>

    </main>

    <!-- 7. LIGHTBOX GALERI FOTO MODAL (PARTIAL) -->
    @include('frontend.kosan.kamar.partials._gallery_modal')

    <!-- 8. FOOTER COMPONENT -->
    @include('frontend.footer')

    <!-- 9. NEED HELP COMPONENT -->
    @include('frontend.need-help')

    <!-- JAVASCRIPT SYSTEM LOGIC -->
    <script>
        // Loading Bar
        window.addEventListener('load', () => {
        const loader = document.getElementById('page-loader');
        if (loader) {
            loader.style.width = '100%';
            setTimeout(() => { loader.style.opacity = '0'; }, 400);
        }
        });

        // WhatsApp Message Auto-Fill
        const phoneNumber = "6282146138847";
        const roomName = "{{ $namaKamar }} ({{ $namaKosan }})";
        const waMessage = encodeURIComponent(`Halo, saya berminat dan ingin bertanya mengenai unit ${roomName}. Apakah unit ini masih tersedia?`);
        const btnTanya = document.getElementById('btn-tanya-pemilik');
        if (btnTanya) {
        btnTanya.href = `https://wa.me/${phoneNumber}?text=${waMessage}`;
        }

        // Lightbox Gallery Script
        @php
        $imagesArr = [];
        if ($kamar && $kamar->productKamarImageKosan && $kamar->productKamarImageKosan->count() > 0) {
            foreach ($kamar->productKamarImageKosan as $imgObj) {
                $imagesArr[] = asset('storage/' . $imgObj->image);
            }
        }
        @endphp

        const galleryImages = @json($imagesArr);
        let currentGalleryIndex = 0;
        const galleryModal = document.getElementById('gallery-modal');
        const activeGalleryImg = document.getElementById('active-gallery-img');
        const galleryCounter = document.getElementById('gallery-counter');
        const thumbnailStrip = document.getElementById('thumbnail-strip');

        function openGallery(index = 0) {
        currentGalleryIndex = index;
        renderGallery();
        galleryModal.classList.remove('hidden');
        galleryModal.classList.add('flex');
        }

        function closeGallery() {
        galleryModal.classList.add('hidden');
        galleryModal.classList.remove('flex');
        }

        function renderGallery() {
        if (!galleryImages.length) return;
        activeGalleryImg.src = galleryImages[currentGalleryIndex];
        galleryCounter.textContent = `${currentGalleryIndex + 1} / ${galleryImages.length}`;

        thumbnailStrip.innerHTML = galleryImages.map((img, idx) => `
            <div onclick="setGalleryIndex(${idx})" class="w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden cursor-pointer border-2 transition-all ${idx === currentGalleryIndex ? 'border-black scale-105 shadow-md' : 'border-transparent opacity-60 hover:opacity-100'}">
            <img src="${img}" class="w-full h-full object-cover">
            </div>
        `).join('');
        }

        function setGalleryIndex(index) {
        currentGalleryIndex = index;
        renderGallery();
        }

        function prevImage() {
        currentGalleryIndex = (currentGalleryIndex - 1 + galleryImages.length) % galleryImages.length;
        renderGallery();
        }

        function nextImage() {
        currentGalleryIndex = (currentGalleryIndex + 1) % galleryImages.length;
        renderGallery();
        }

        // Scroll Reveal Animation
        const revealElements = document.querySelectorAll('.reveal');
        const revealOnScroll = () => {
        const windowHeight = window.innerHeight;
        revealElements.forEach(el => {
            if (el.getBoundingClientRect().top < windowHeight - 80) {
            el.classList.add('active');
            }
        });
        };
        window.addEventListener('scroll', revealOnScroll);
        window.addEventListener('load', revealOnScroll);
    </script>


</body>
</html>
