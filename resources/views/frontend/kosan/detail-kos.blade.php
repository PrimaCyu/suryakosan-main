<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sinar Citra Lestari - {{ $kosan->title }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
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
  </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-x-hidden">

  <!-- 1. BAR LOADING -->
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-500 to-blue-600 z-[100] transition-all duration-500 ease-out"></div>

  <!-- 2. NAVBAR -->
  @include('frontend.navbar')

  @php
    $images = [];
    if ($kosan->productImageKosan->count() > 0) {
        foreach ($kosan->productImageKosan as $imgObj) {
            $images[] = asset('storage/' . $imgObj->image);
        }
    }

    if (empty($images)) {
        $images = [
            "https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=1000&q=80",
            "https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=1000&q=80",
            "https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1000&q=80",
            "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80"
        ];
    }

    $fasKosanArr = array_filter(array_map('trim', explode(',', $kosan->fasilitas ?? '')));
    $iconMap = [
        'Wi-Fi / Internet' => 'fa-wifi',
        'Parkir Mobil' => 'fa-car',
        'Parkir Motor' => 'fa-motorcycle',
        'Dapur Bersama' => 'fa-kitchen-set',
        'CCTV 24 Jam' => 'fa-video',
        'Keamanan / Satpam' => 'fa-user-shield',
        'Ruang Tamu Bersama' => 'fa-couch',
        'Ruang Jemur' => 'fa-shirt',
        'Mesin Cuci Bersama' => 'fa-soap',
        'Kulkas Bersama' => 'fa-box',
        'Air Minum / Dispenser' => 'fa-glass-water',
        'Penjaga Kos' => 'fa-user-clock',
        'Listrik Gratis / Included' => 'fa-bolt',
        'Bebas Jam Malam' => 'fa-key',
        'Akses Kartu / Smart Lock' => 'fa-id-card',
        'Balkon / Rooftop' => 'fa-building',
        'Musholla' => 'fa-mosque',
        'Gazebo / Area Santai' => 'fa-umbrella-beach'
    ];
  @endphp

  <!-- MAIN CONTENT CONTAINER -->
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

    <!-- 3. BREADCRUMB -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 reveal">
      <a href="{{ route('kosan.index') }}" class="hover:text-cyan-600 transition-colors">Kos-Kosan</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <span class="text-slate-800 font-bold">{{ $kosan->title }}</span>
    </nav>

    <!-- 4. GALLERY GRID (5 PHOTO MODERN LAYOUT) -->
    <section class="grid grid-cols-1 md:grid-cols-12 gap-3 rounded-3xl overflow-hidden reveal cursor-pointer">
      <!-- Main Large Photo (Left) -->
      <div class="md:col-span-7 h-[260px] sm:h-[380px] relative group overflow-hidden rounded-3xl" onclick="openGallery(0)">
        <img src="{{ $images[0] }}" alt="{{ $kosan->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors"></div>
      </div>

      <!-- Grid Photos (Right) -->
      <div class="md:col-span-5 grid grid-cols-2 gap-3 h-[260px] sm:h-[380px]">
        @for($i = 1; $i <= 2; $i++)
          @if(isset($images[$i]))
            <div class="relative group overflow-hidden rounded-2xl" onclick="openGallery({{ $i }})">
              <img src="{{ $images[$i] }}" alt="Foto {{ $i }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
          @else
            <div class="relative group overflow-hidden rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
              <i class="fa-regular fa-image text-2xl"></i>
            </div>
          @endif
        @endfor

        <!-- Extra Photos Thumbnails -->
        @if(isset($images[3]))
          <div class="relative group overflow-hidden rounded-2xl" onclick="openGallery(3)">
            <img src="{{ $images[3] }}" alt="Foto 3" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          </div>
        @else
          <div class="relative group overflow-hidden rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
            <i class="fa-regular fa-image text-2xl"></i>
          </div>
        @endif

        <div class="relative group overflow-hidden rounded-2xl" onclick="openGallery({{ count($images) > 4 ? 4 : 0 }})">
          <img src="{{ $images[4] ?? $images[0] }}" alt="Semua Foto" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          <div class="absolute inset-0 bg-slate-900/60 group-hover:bg-slate-900/70 transition-colors flex flex-col items-center justify-center text-white gap-1">
            <i class="fa-regular fa-images text-xl"></i>
            <span class="text-xs font-bold">{{ count($images) }} Foto</span>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. KOS HEADER & TABS NAVIGATION -->
    <section class="space-y-4 reveal">
      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $kosan->title }}</h1>
        <div class="flex items-center gap-4 text-xs text-slate-500 mt-1">
          <span class="flex items-center gap-1.5 font-semibold text-slate-600">
            <i class="fa-solid fa-location-dot text-rose-500"></i> {{ $kosan->wilayah ?? 'Lokasi Terdaftar' }}
          </span>
          <span class="flex items-center gap-1.5 text-slate-400 border-l border-slate-200 pl-4">
            <i class="fa-solid fa-eye text-cyan-600"></i> {{ number_format($kosan->view ?? 0) }} dilihat
          </span>
        </div>
      </div>

      <!-- Quick Navigation Tabs -->
      <div class="flex items-center gap-6 border-b border-slate-200 text-xs sm:text-sm font-bold pt-2 overflow-x-auto no-scrollbar">
        <button type="button" onclick="scrollToSection('deskripsi')" class="pb-3 text-cyan-600 border-b-2 border-cyan-600 hover:text-cyan-600 transition-colors whitespace-nowrap">
          Deskripsi Lengkap
        </button>
        <button type="button" onclick="scrollToSection('fasilitas')" class="pb-3 text-slate-500 hover:text-cyan-600 transition-colors whitespace-nowrap">
          Fasilitas Properti
        </button>
        @if($kosan->gmaps)
          <button type="button" onclick="scrollToSection('lokasi')" class="pb-3 text-slate-500 hover:text-cyan-600 transition-colors whitespace-nowrap">
            Lokasi Maps
          </button>
        @endif
        <button type="button" onclick="scrollToSection('kamar-tersedia')" class="pb-3 text-slate-500 hover:text-cyan-600 transition-colors whitespace-nowrap">
          Kamar yang Tersedia ({{ $kosan->productKamarKosan->count() }})
        </button>
      </div>
    </section>

    <!-- 6. DESKRIPSI LENGKAP -->
    <section id="deskripsi" class="space-y-3 pt-2 reveal">
      <h3 class="text-base font-extrabold text-slate-900">Deskripsi Properti</h3>
      <div class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        {!! nl2br(e($kosan->description ?? 'Deskripsi kosan belum ditambahkan oleh pemilik.')) !!}
      </div>
    </section>

    <!-- 6.1 FASILITAS SECTION -->
    <section id="fasilitas" class="space-y-4 pt-2 reveal">
      <h3 class="text-base font-extrabold text-slate-900">Fasilitas Properti Kos</h3>
      @if(count($fasKosanArr) > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
          @foreach($fasKosanArr as $fasName)
            @php $iconClass = $iconMap[$fasName] ?? 'fa-check'; @endphp
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                <i class="fa-solid {{ $iconClass }} text-base"></i>
              </div>
              <span class="text-xs font-bold text-slate-800">{{ $fasName }}</span>
            </div>
          @endforeach
        </div>
      @else
        <div class="bg-white p-6 rounded-2xl border border-slate-100 text-slate-400 text-xs">
          Belum ada rincian fasilitas kosan.
        </div>
      @endif
    </section>

    <!-- 7. LOKASI SECTION (GOOGLE MAPS) -->
    @if($kosan->gmaps)
      <section id="lokasi" class="space-y-3 pt-2 reveal">
        <h3 class="text-base font-extrabold text-slate-900">Lokasi Properti</h3>
        <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-md h-[300px] sm:h-[380px] bg-slate-100 relative">
          @if(Str::contains($kosan->gmaps, '<iframe'))
            @php
              $cleanIframe = preg_replace('/width="[^"]*"/', 'width="100%"', $kosan->gmaps);
              $cleanIframe = preg_replace('/height="[^"]*"/', 'height="100%"', $cleanIframe);
              $cleanIframe = preg_replace('/style="[^"]*"/', '', $cleanIframe);
              $cleanIframe = str_replace('<iframe', '<iframe class="w-full h-full border-0 rounded-3xl"', $cleanIframe);
            @endphp
            {!! $cleanIframe !!}
          @else
            <iframe
              src="{{ $kosan->gmaps }}"
              class="w-full h-full border-0 rounded-3xl"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          @endif
        </div>
      </section>
    @endif

    <!-- 8. KAMAR YANG TERSEDIA SECTION -->
    <section id="kamar-tersedia" class="space-y-4 pt-2 reveal">
      <div class="flex items-center justify-between">
        <h3 class="text-base font-extrabold text-slate-900">Unit Kamar yang Tersedia</h3>
        <span class="text-xs text-slate-500 font-semibold">{{ $kosan->productKamarKosan->count() }} Tipe Kamar</span>
      </div>

      <div class="space-y-4">
        @forelse($kosan->productKamarKosan as $kamarItem)
          @php
            $kamarImg = $kamarItem->productKamarImageKosan->first();
            $monthlyPriceObj = $kamarItem->priceKamar->first(function($price) {
                return strtolower($price->kategori) === 'bulan';
            });
            $monthlyPrice = $monthlyPriceObj ? $monthlyPriceObj->price : null;
          @endphp

          <!-- ROOM CARD -->
          <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col sm:flex-row items-center justify-between gap-5 group">
            <div class="flex items-center gap-4 w-full sm:w-auto">
              @if($kamarImg)
                <img src="{{ asset('storage/' . $kamarImg->image) }}" alt="{{ $kamarItem->room }}" loading="lazy" class="w-24 h-24 sm:w-32 sm:h-28 rounded-2xl object-cover shrink-0 group-hover:scale-105 transition-transform duration-300">
              @else
                <div class="w-24 h-24 sm:w-32 sm:h-28 rounded-2xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-slate-400 text-center p-2 shrink-0">
                  <i class="fa-regular fa-image text-2xl mb-1"></i>
                  <span class="text-[9px] font-semibold">Foto Kamar</span>
                </div>
              @endif
              <div>
                <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-wider">Unit Kamar</span>
                <h4 class="font-extrabold text-slate-900 text-base sm:text-lg hover:text-cyan-600 transition-colors">
                  <a href="{{ route('kamar.detail', $kamarItem->id) }}">{{ $kamarItem->room }}</a>
                </h4>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-1 font-medium">
                  {{ strip_tags($kamarItem->description ?? 'Fasilitas kamar lengkap dan nyaman.') }}
                </p>
                <div class="mt-2.5">
                  <span class="text-[10px] uppercase font-bold text-slate-400 block">Tarif Bulanan</span>
                  @if($monthlyPrice)
                    <span class="text-base sm:text-lg font-extrabold text-slate-900">Rp {{ number_format($monthlyPrice, 0, ',', '.') }} <span class="text-[10px] font-normal text-slate-400">/bulan</span></span>
                  @else
                    <span class="text-xs font-bold text-slate-500">Hubungi Admin</span>
                  @endif
                </div>
              </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
              <a href="{{ route('kamar.detail', $kamarItem->id) }}" class="w-full sm:w-auto px-6 py-3 bg-slate-900 hover:bg-cyan-600 text-white text-xs font-bold rounded-2xl transition-all shadow-md active:scale-95 text-center flex items-center justify-center gap-1.5">
                <span>Pilih Kamar Ini</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
              </a>
            </div>
          </div>
        @empty
          <div class="p-8 text-center bg-white rounded-3xl border border-slate-100 text-slate-400 text-xs">
            Belum ada unit kamar yang terdaftar untuk properti ini.
          </div>
        @endforelse
      </div>
    </section>

  </main>

  <!-- LIGHTBOX GALERI FOTO (MODAL) -->
  <div id="gallery-modal" class="fixed inset-0 bg-slate-950/90 z-[999] hidden flex-col justify-between p-4 sm:p-6 backdrop-blur-md">
    <div class="flex items-center justify-between border-b border-slate-800 pb-4 max-w-5xl mx-auto w-full">
      <h3 class="font-bold text-white text-base sm:text-lg">{{ $kosan->title }}</h3>
      <span id="gallery-counter" class="text-xs sm:text-sm font-bold text-slate-400">1 / 1</span>
      <button onclick="closeGallery()" class="w-9 h-9 rounded-full bg-slate-800 text-slate-300 hover:bg-white hover:text-slate-900 transition-all flex items-center justify-center">
        <i class="fa-solid fa-xmark text-base"></i>
      </button>
    </div>

    <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden max-w-5xl mx-auto w-full">
      <button onclick="prevImage()" class="absolute left-2 sm:left-4 w-11 h-11 rounded-full bg-white/10 text-white hover:bg-white hover:text-slate-900 transition-all flex items-center justify-center z-10 backdrop-blur-md">
        <i class="fa-solid fa-chevron-left text-base"></i>
      </button>

      <div class="w-full h-[65vh] rounded-2xl overflow-hidden flex items-center justify-center p-2">
        <img id="active-gallery-img" src="" alt="Foto Kos" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl">
      </div>

      <button onclick="nextImage()" class="absolute right-2 sm:right-4 w-11 h-11 rounded-full bg-white/10 text-white hover:bg-white hover:text-slate-900 transition-all flex items-center justify-center z-10 backdrop-blur-md">
        <i class="fa-solid fa-chevron-right text-base"></i>
      </button>
    </div>

    <div class="flex items-center justify-center gap-2 overflow-x-auto no-scrollbar py-2 max-w-5xl mx-auto w-full">
      <div id="thumbnail-strip" class="flex items-center gap-2"></div>
    </div>
  </div>

  <!-- FOOTER -->
  @include('frontend.footer')

  <!-- NEED HELP WIDGET -->
  @include('frontend.need-help')

  <!-- JAVASCRIPT SYSTEM LOGIC -->
  <script>
    function scrollToSection(id) {
      const element = document.getElementById(id);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
      }
    }

    // Loading Bar
    window.addEventListener('load', () => {
      const loader = document.getElementById('page-loader');
      if (loader) {
        loader.style.width = '100%';
        setTimeout(() => { loader.style.opacity = '0'; }, 300);
      }
    });

    // Lightbox Gallery
    const galleryImages = @json($images);
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
        <div onclick="setGalleryIndex(${idx})" class="w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden cursor-pointer border-2 transition-all ${idx === currentGalleryIndex ? 'border-cyan-400 scale-105 shadow-md' : 'border-transparent opacity-50 hover:opacity-100'}">
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

    // Keyboard navigation for gallery
    document.addEventListener('keydown', (e) => {
      if (!galleryModal.classList.contains('hidden')) {
        if (e.key === 'Escape') closeGallery();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'ArrowRight') nextImage();
      }
    });

    // Scroll Reveal
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
