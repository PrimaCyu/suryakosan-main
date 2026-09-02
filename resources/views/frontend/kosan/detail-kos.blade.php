<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NemuKOS - {{ $kosan->title }}</title>
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
    .custom-scroll::-webkit-scrollbar {
      width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
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

  @php
    // Persiapan Gambar Kosan
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

    <!-- 3. BREADCRUMB NAVIGASI -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 reveal">
      <a href="{{ route('kosan.index') }}" class="hover:text-cyan-600 transition-colors">Kos-Kosan</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <span class="text-slate-800 font-bold">{{ $kosan->title }}</span>
    </nav>

    <!-- 4. GALLERY GRID (5 PHOTO LAYOUT) -->
    <section class="grid grid-cols-1 md:grid-cols-12 gap-3 rounded-3xl overflow-hidden reveal cursor-pointer">
      <!-- Main Large Photo (Left) -->
      <div class="md:col-span-7 h-[260px] sm:h-[380px] relative group overflow-hidden" onclick="openGallery(0)">
        <img src="{{ $images[0] }}" alt="{{ $kosan->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
      </div>

      <!-- 4 Grid Photos (Right) -->
      <div class="md:col-span-5 grid grid-cols-2 gap-3 h-[260px] sm:h-[380px]">
        @for($i = 1; $i <= 3; $i++)
          @if(isset($images[$i]))
            <div class="relative group overflow-hidden rounded-2xl" onclick="openGallery({{ $i }})">
              <img src="{{ $images[$i] }}" alt="Foto {{ $i }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
          @else
            <div class="relative group overflow-hidden rounded-2xl bg-slate-200 flex items-center justify-center text-slate-400">
              <i class="fa-regular fa-image text-2xl"></i>
            </div>
          @endif
        @endfor

        <!-- Photo with Overlay Badge -->
        <div class="relative group overflow-hidden rounded-2xl" onclick="openGallery(3)">
          <img src="{{ $images[3] ?? $images[0] }}" alt="Area Santai" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          <div class="absolute inset-0 bg-black/50 group-hover:bg-black/60 transition-colors flex flex-col items-center justify-center text-white gap-1">
            <i class="fa-regular fa-images text-xl"></i>
            <span id="gallery-badge-text" class="text-xs font-bold">+{{ max(0, count($images) - 4) }} Foto Lain</span>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. KOS HEADER & TABS NAVIGATION -->
    <section class="space-y-4 reveal">
      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $kosan->title }}</h1>
        <div class="flex items-center gap-4 text-xs text-slate-500 mt-1">
          <span class="flex items-center gap-1.5">
            <i class="fa-solid fa-location-dot text-cyan-600"></i> {{ $kosan->wilayah ?? 'Bali' }}
          </span>
          <span class="flex items-center gap-1.5 text-slate-400 border-l border-slate-200 pl-4">
            <i class="fa-regular fa-eye text-cyan-600"></i> {{ number_format($kosan->view ?? 0) }} dilihat
          </span>
        </div>
      </div>

      <!-- Quick Navigation Tabs -->
      <div class="flex items-center gap-6 border-b border-slate-200 text-xs sm:text-sm font-semibold pt-2 overflow-x-auto no-scrollbar">
        <button onclick="scrollToSection('deskripsi')" class="pb-3 text-cyan-600 border-b-2 border-cyan-600 hover:text-cyan-600 transition-colors whitespace-nowrap">Deskripsi Lengkap</button>
        <button onclick="scrollToSection('fasilitas')" class="pb-3 text-slate-500 hover:text-cyan-600 transition-colors whitespace-nowrap">Fasilitas Properti</button>
        @if($kosan->gmaps)
          <button onclick="scrollToSection('lokasi')" class="pb-3 text-slate-500 hover:text-cyan-600 transition-colors whitespace-nowrap">Lokasi Maps</button>
        @endif
        <button onclick="scrollToSection('kamar-tersedia')" class="pb-3 text-slate-500 hover:text-cyan-600 transition-colors whitespace-nowrap">Kamar yang Tersedia</button>
      </div>
    </section>

    <!-- 6. DESKRIPSI LENGKAP SECTION -->
    <section id="deskripsi" class="space-y-3 pt-2 reveal">
      <h3 class="text-base font-extrabold text-slate-900">Deskripsi Lengkap</h3>
      <div class="text-xs sm:text-sm text-slate-600 leading-relaxed space-y-2">
        {!! nl2br(e($kosan->description ?? 'Deskripsi kosan belum ditambahkan.')) !!}
      </div>
    </section>

    <!-- 6.1 FASILITAS SECTION -->
    <section id="fasilitas" class="space-y-6 pt-4 reveal">
      <div class="space-y-3">
        <h3 class="text-base font-extrabold text-slate-900">Fasilitas Properti Kos</h3>
        @if(count($fasKosanArr) > 0)
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($fasKosanArr as $fasName)
              @php $iconClass = $iconMap[$fasName] ?? 'fa-check'; @endphp
              <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center gap-2">
                <i class="fa-solid {{ $iconClass }} text-cyan-600 text-xl"></i>
                <span class="text-xs font-bold text-slate-800">{{ $fasName }}</span>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-xs text-slate-400">Belum ada rincian fasilitas kosan.</p>
        @endif
      </div>
    </section>

    <!-- 7. LOKASI SECTION (GOOGLE MAPS EMBED) -->
    @if($kosan->gmaps)
      <section id="lokasi" class="space-y-3 pt-4 reveal">
        <div class="flex items-center justify-between">
          <h3 class="text-base font-extrabold text-slate-900">Lokasi Properti</h3>
        </div>

        <!-- Google Maps Frame Container -->
        <div class="rounded-3xl overflow-hidden border border-slate-200/80 shadow-md h-[300px] sm:h-[380px] bg-slate-100 relative">
          @if(Str::contains($kosan->gmaps, '<iframe'))
            @php
              // Bersihkan width/height bawaan tag iframe agar mengikuti frame Tailwind
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
    <section id="kamar-tersedia" class="space-y-4 pt-4 reveal">
      <h3 class="text-base font-extrabold text-slate-900">Kamar yang Tersedia</h3>

      <div id="rooms-container" class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scroll">
        @forelse($kosan->productKamarKosan as $kamarItem)
          @php
            $kamarImg = $kamarItem->productKamarImageKosan->first();
            $monthlyPriceObj = $kamarItem->priceKamar->first(function($price) {
                return strtolower($price->kategori) === 'bulan';
            });
            $monthlyPrice = $monthlyPriceObj ? $monthlyPriceObj->price : null;
          @endphp

          <!-- ROOM CARD -->
          <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto">
              @if($kamarImg)
                <img src="{{ asset('storage/' . $kamarImg->image) }}" alt="{{ $kamarItem->room }}" loading="lazy" class="w-24 h-20 sm:w-32 sm:h-24 rounded-2xl object-cover shrink-0">
              @else
                <div class="w-24 h-20 sm:w-32 sm:h-24 rounded-2xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-slate-400 text-center p-2 shrink-0">
                  <i class="fa-regular fa-image text-xl mb-1"></i>
                  <span class="text-[9px] font-semibold leading-none">Tidak ada foto</span>
                </div>
              @endif
              <div>
                <h4 class="font-extrabold text-slate-900 text-sm sm:text-base">{{ $kamarItem->room }}</h4>
                <p class="text-[11px] text-slate-400 mt-1 leading-relaxed line-clamp-1">{{ strip_tags($kamarItem->description ?? 'Fasilitas kamar lengkap & nyaman') }}</p>
                <div class="mt-2">
                  <span class="text-[10px] uppercase font-bold text-slate-400 block">Harga Bulanan</span>
                  @if($monthlyPrice)
                    <span class="text-sm sm:text-base font-extrabold text-slate-900">Rp {{ number_format($monthlyPrice, 0, ',', '.') }} <span class="text-[10px] font-normal text-slate-400">/bln</span></span>
                  @else
                    <span class="text-xs font-semibold text-slate-500">Harga N/A</span>
                  @endif
                </div>
              </div>
            </div>
            <a href="{{ route('kamar.detail', $kamarItem->id) }}" class="w-full sm:w-auto px-6 py-2.5 bg-black text-white text-xs font-bold rounded-full hover:bg-slate-800 transition-all shadow-sm active:scale-95 shrink-0 text-center">
              Lihat Detail Kamar
            </a>
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
  <div id="gallery-modal" class="fixed inset-0 bg-white/95 z-[999] hidden flex-col justify-between p-4 sm:p-6 backdrop-blur-md">
    <div class="flex items-center justify-between border-b pb-4">
      <h3 class="font-bold text-slate-900 text-base sm:text-lg">Foto Detail Kos</h3>
      <span id="gallery-counter" class="text-xs sm:text-sm font-extrabold text-slate-600">1 / 1</span>
      <button onclick="closeGallery()" class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-black hover:text-white transition-all">
        <i class="fa-solid fa-xmark text-base"></i>
      </button>
    </div>

    <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden">
      <button onclick="prevImage()" class="absolute left-2 sm:left-6 w-10 h-10 rounded-full bg-white/90 shadow-lg border border-slate-100 flex items-center justify-center text-slate-700 hover:bg-black hover:text-white transition-all z-10">
        <i class="fa-solid fa-chevron-left text-sm"></i>
      </button>

      <div class="w-full max-w-4xl h-[65vh] rounded-2xl overflow-hidden shadow-2xl border border-slate-200 bg-slate-950 flex items-center justify-center p-2">
        <img id="active-gallery-img" src="" alt="Foto Kos" class="max-w-full max-h-full w-auto h-auto object-contain rounded-xl">
      </div>

      <button onclick="nextImage()" class="absolute right-2 sm:right-6 w-10 h-10 rounded-full bg-white/90 shadow-lg border border-slate-100 flex items-center justify-center text-slate-700 hover:bg-black hover:text-white transition-all z-10">
        <i class="fa-solid fa-chevron-right text-sm"></i>
      </button>
    </div>

    <div class="flex items-center justify-center gap-2 overflow-x-auto no-scrollbar py-2">
      <div id="thumbnail-strip" class="flex items-center gap-2"></div>
    </div>
  </div>

  <!-- FOOTER COMPONENT -->
  @include('frontend.footer')

  <!-- NEED HELP COMPONENT -->
  @include('frontend.need-help')

  <!-- JAVASCRIPT SYSTEM LOGIC -->
  <script>
    // Scroll To Section Smoothly
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
        setTimeout(() => { loader.style.opacity = '0'; }, 400);
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
