<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sinar Citra Lestari - {{ $kosan->title }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="icon" href="{{ asset('scl.png') }}">
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

    .gallery-main {
      transition: transform .6s cubic-bezier(.16,1,.3,1);
    }

    .gallery-main:hover {
      transform: scale(1.025);
    }

    .room-card {
      transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }

    .room-card:hover {
      transform: translateY(-4px);
    }

    .tab-active {
      color: #E60049;
      border-color: #E60049;
    }
  </style>
</head>

<body class="bg-[#FFF8F1] text-[#3B2314] font-sans antialiased overflow-x-hidden">

  <!-- 1. BAR LOADING -->
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#F3A833] via-[#E60049] to-[#00A896] z-[100] transition-all duration-500 ease-out"></div>

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
            'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80'
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
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-7 space-y-10">

    <!-- 4. GALLERY GRID -->
    <section class="reveal">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <!-- Photo utama dipindahkan ke sisi kanan -->
        <div class="lg:col-span-8 order-1 lg:order-2">
          <div
            class="relative h-[300px] sm:h-[430px] lg:h-[520px] rounded-[2rem] overflow-hidden cursor-pointer bg-[#EADFD4] group shadow-xl"
            onclick="openGallery(0)"
          >
            <img
              src="{{ $images[0] }}"
              alt="{{ $kosan->title }}"
              class="gallery-main w-full h-full object-cover"
            >

            <div class="absolute inset-0 bg-gradient-to-t from-[#3B2314]/75 via-transparent to-transparent pointer-events-none"></div>

            <div class="absolute left-5 bottom-5 sm:left-7 sm:bottom-7 right-5 sm:right-7 flex items-end justify-between gap-4 text-white">
              <div>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#E60049] rounded-full text-[10px] font-extrabold uppercase tracking-wider shadow-lg">
                  <i class="fa-solid fa-camera"></i>
                  Foto Utama
                </span>
                <p class="mt-2 text-sm font-bold">Klik foto untuk melihat galeri</p>
              </div>

              <span class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center shrink-0">
                <i class="fa-solid fa-expand text-sm"></i>
              </span>
            </div>
          </div>
        </div>

        <!-- Panel thumbnail dibuat vertikal -->
        <div class="lg:col-span-4 order-2 lg:order-1 grid grid-cols-2 lg:grid-cols-1 gap-4">
          @for($i = 1; $i <= 4; $i++)
            @if(isset($images[$i]))
              <div
                class="relative h-32 sm:h-40 lg:h-[119px] overflow-hidden rounded-[1.5rem] bg-[#EADFD4] cursor-pointer group shadow-sm"
                onclick="openGallery({{ $i }})"
              >
                <img
                  src="{{ $images[$i] }}"
                  alt="Foto {{ $i }}"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                >

                @if($i === 4)
                  <div class="absolute inset-0 bg-[#3B2314]/60 flex flex-col items-center justify-center text-white gap-1">
                    <i class="fa-regular fa-images text-xl"></i>
                    <span class="text-xs font-extrabold">{{ count($images) }} Foto</span>
                  </div>
                @endif
              </div>
            @else
              <div class="h-32 sm:h-40 lg:h-[119px] rounded-[1.5rem] bg-[#F2E9E1] flex items-center justify-center text-[#B6A393]">
                <i class="fa-regular fa-image text-xl"></i>
              </div>
            @endif
          @endfor
        </div>

      </div>
    </section>

    <!-- 5. KOS HEADER & TABS NAVIGATION -->
    <section class="reveal">
      <div class="grid lg:grid-cols-12 gap-6 items-end">

        <!-- Header dibuat sebagai panel identitas -->
        <div class="lg:col-span-7">

          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#3B2314] leading-[1.05]">
            {{ $kosan->title }}
          </h1>

          <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs mt-4">
            <span class="flex items-center gap-2 font-bold text-[#6E594A]">
              <span class="w-8 h-8 rounded-xl bg-[#E60049]/10 text-[#E60049] flex items-center justify-center">
                <i class="fa-solid fa-location-dot"></i>
              </span>
              {{ $kosan->wilayah ?? 'Lokasi Terdaftar' }}
            </span>

            <span class="flex items-center gap-2 font-semibold text-[#8F7765]">
              <span class="w-8 h-8 rounded-xl bg-[#00A896]/10 text-[#00A896] flex items-center justify-center">
                <i class="fa-solid fa-eye"></i>
              </span>
              {{ number_format($kosan->view ?? 0) }} dilihat
            </span>
          </div>
        </div>

        <!-- Quick navigation dibuat seperti action rail -->
        <div class="lg:col-span-5">
          <div class="bg-[#3B2314] rounded-[1.5rem] p-2 shadow-lg overflow-x-auto no-scrollbar">
            <div class="flex items-center min-w-max gap-1">
              <button type="button" onclick="scrollToSection('deskripsi')" class="tab-button tab-active px-3 sm:px-4 py-2.5 rounded-xl bg-[#FFF8F1] text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                Deskripsi
              </button>

              <button type="button" onclick="scrollToSection('fasilitas')" class="tab-button px-3 sm:px-4 py-2.5 rounded-xl text-[#F8EBDD] hover:bg-white/10 text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                Fasilitas
              </button>

              @if($kosan->gmaps)
                <button type="button" onclick="scrollToSection('lokasi')" class="tab-button px-3 sm:px-4 py-2.5 rounded-xl text-[#F8EBDD] hover:bg-white/10 text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                  Lokasi
                </button>
              @endif

              <button type="button" onclick="scrollToSection('kamar-tersedia')" class="tab-button px-3 sm:px-4 py-2.5 rounded-xl text-[#F8EBDD] hover:bg-white/10 text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                Kamar ({{ $kosan->productKamarKosan->count() }})
              </button>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- 6. DESKRIPSI LENGKAP -->
    <section id="deskripsi" class="reveal">
      <div class="grid lg:grid-cols-12 gap-6 items-start">

        <div class="lg:col-span-4 lg:sticky lg:top-28">
          <span class="text-[10px] font-extrabold uppercase tracking-[.2em] text-[#E60049]">01 — Tentang Properti</span>
          <h3 class="text-2xl sm:text-3xl font-black text-[#3B2314] mt-2 leading-tight">
            Kenali tempat tinggalmu sebelum memilih.
          </h3>
        </div>

        <div class="lg:col-span-8">
          <div class="relative bg-white p-6 sm:p-8 rounded-[2rem] border border-[#E9DDD2] shadow-sm overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-bl-[3rem] bg-[#F3A833]/15"></div>
            <div class="relative text-xs sm:text-sm text-[#604D3F] leading-[1.9]">
              @if(!empty(trim(strip_tags($kosan->description ?? ''))))
                {!! $kosan->description !!}
              @else
                <p class="text-[#9A8675] italic">Deskripsi kosan belum ditambahkan oleh pemilik.</p>
              @endif
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- 6.1 FASILITAS SECTION -->
    <section id="fasilitas" class="reveal">
      <div class="bg-[#F3A833]/10 rounded-[2rem] p-6 sm:p-8 border border-[#F3A833]/20">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-6">
          <div>
            <span class="text-[10px] font-extrabold uppercase tracking-[.2em] text-[#00A896]">02 — Fasilitas</span>
            <h3 class="text-2xl sm:text-3xl font-black text-[#3B2314] mt-1">Yang tersedia di properti</h3>
          </div>
          <span class="text-xs font-bold text-[#8F7765]">
            {{ count($fasKosanArr) }} fasilitas
          </span>
        </div>

        @if(count($fasKosanArr) > 0)
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($fasKosanArr as $fasName)
              @php $iconClass = $iconMap[$fasName] ?? 'fa-check'; @endphp

              <div class="group bg-white p-4 rounded-2xl border border-[#EADFD4] shadow-sm hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                <div class="w-10 h-10 rounded-xl bg-[#00A896]/10 text-[#00A896] group-hover:bg-[#00A896] group-hover:text-white transition-colors flex items-center justify-center mb-3">
                  <i class="fa-solid {{ $iconClass }} text-sm"></i>
                </div>
                <span class="text-xs font-extrabold text-[#4C392B] leading-snug">{{ $fasName }}</span>
              </div>
            @endforeach
          </div>
        @else
          <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] text-[#9A8675] text-xs">
            Belum ada rincian fasilitas kosan.
          </div>
        @endif
      </div>
    </section>

    <!-- 7. LOKASI SECTION (GOOGLE MAPS) -->
    @if($kosan->gmaps)
      <section id="lokasi" class="reveal">
        <div class="grid lg:grid-cols-12 gap-6 items-center">

          <div class="lg:col-span-4">
            <span class="text-[10px] font-extrabold uppercase tracking-[.2em] text-[#E60049]">03 — Lokasi</span>
            <h3 class="text-2xl sm:text-3xl font-black text-[#3B2314] mt-2">Lihat area properti</h3>

            <div class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 bg-white rounded-xl border border-[#EADFD4] shadow-sm text-xs font-bold text-[#5D493A]">
              <i class="fa-solid fa-map-location-dot text-[#E60049]"></i>
              {{ $kosan->wilayah ?? 'Lokasi Terdaftar' }}
            </div>
          </div>

          <div class="lg:col-span-8">
            <div class="rounded-[2rem] overflow-hidden border-4 border-white shadow-xl h-[300px] sm:h-[390px] bg-[#EDE4DC] relative">
              @if(Str::contains($kosan->gmaps, '<iframe'))
                @php
                  $cleanIframe = preg_replace('/width="[^"]*"/', 'width="100%"', $kosan->gmaps);
                  $cleanIframe = preg_replace('/height="[^"]*"/', 'height="100%"', $cleanIframe);
                  $cleanIframe = preg_replace('/style="[^"]*"/', '', $cleanIframe);
                  $cleanIframe = str_replace('<iframe', '<iframe class="w-full h-full border-0"', $cleanIframe);
                @endphp
                {!! $cleanIframe !!}
              @else
                <iframe
                  src="{{ $kosan->gmaps }}"
                  class="w-full h-full border-0"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              @endif
            </div>
          </div>

        </div>
      </section>
    @endif

    <!-- 8. KAMAR YANG TERSEDIA SECTION -->
    <section id="kamar-tersedia" class="reveal">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-6">
        <div>
          <span class="text-[10px] font-extrabold uppercase tracking-[.2em] text-[#00A896]">04 — Pilihan Kamar</span>
          <h3 class="text-2xl sm:text-3xl font-black text-[#3B2314] mt-1">Pilih unit yang sesuai</h3>
        </div>

        <span class="inline-flex self-start sm:self-auto items-center gap-2 px-3 py-2 bg-[#3B2314] text-[#FFF8F1] rounded-xl text-xs font-extrabold">
          <i class="fa-solid fa-door-open text-[#F3A833]"></i>
          {{ $kosan->productKamarKosan->count() }} Tipe Kamar
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse($kosan->productKamarKosan as $kamarItem)
          @php
            $kamarImg = $kamarItem->productKamarImageKosan->first();

            $monthlyPriceObj = $kamarItem->priceKamar->first(function($price) {
              return strtolower($price->kategori) === 'bulan';
            });

            $monthlyPrice = $monthlyPriceObj ? $monthlyPriceObj->price : null;
          @endphp

          <!-- ROOM CARD -->
          <div class="room-card bg-white rounded-[2rem] border border-[#E9DDD2] shadow-sm overflow-hidden group">
            <div class="grid grid-cols-1 sm:grid-cols-5">

              <div class="sm:col-span-2 h-52 sm:h-full min-h-[220px] relative overflow-hidden bg-[#EDE4DC]">
                @if($kamarImg)
                  <img
                    src="{{ asset('storage/' . $kamarImg->image) }}"
                    alt="{{ $kamarItem->room }}"
                    loading="lazy"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  >
                @else
                  <div class="w-full h-full flex flex-col items-center justify-center text-[#A99888] text-center p-4">
                    <i class="fa-regular fa-image text-3xl mb-2"></i>
                    <span class="text-[9px] font-bold">Foto Kamar</span>
                  </div>
                @endif

                <div class="absolute top-3 left-3 px-2.5 py-1 bg-[#FFF8F1]/95 backdrop-blur-sm rounded-full text-[9px] font-extrabold text-[#3B2314]">
                  UNIT KAMAR
                </div>
              </div>

              <div class="sm:col-span-3 p-5 flex flex-col justify-between">
                <div>
                  <h4 class="font-black text-[#3B2314] text-lg leading-tight">
                    <a href="{{ route('kamar.detail', $kamarItem->id) }}" class="hover:text-[#E60049] transition-colors">
                      {{ $kamarItem->room }}
                    </a>
                  </h4>

                  <p class="text-xs text-[#8F7765] mt-2 leading-relaxed line-clamp-2">
                    {{ strip_tags($kamarItem->description ?? 'Fasilitas kamar lengkap dan nyaman.') }}
                  </p>

                  <div class="mt-5 p-3 rounded-2xl bg-[#F3A833]/10 border border-[#F3A833]/15">
                    <span class="text-[9px] uppercase font-extrabold tracking-wider text-[#9A7651] block">Tarif Bulanan</span>

                    @if($monthlyPrice)
                      <span class="text-xl font-black text-[#E60049]">
                        Rp {{ number_format($monthlyPrice, 0, ',', '.') }}
                      </span>
                      <span class="text-[10px] font-semibold text-[#8F7765]">/bulan</span>
                    @else
                      <span class="text-xs font-extrabold text-[#6F5A4B]">Hubungi Admin</span>
                    @endif
                  </div>
                </div>

                <a
                  href="{{ route('kamar.detail', $kamarItem->id) }}"
                  class="mt-5 w-full px-5 py-3 bg-[#3B2314] hover:bg-[#E60049] text-white text-xs font-extrabold rounded-2xl transition-all shadow-sm active:scale-[.98] flex items-center justify-center gap-2"
                >
                  <span>Lihat Detail Kamar</span>
                  <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
              </div>

            </div>
          </div>
        @empty
          <div class="md:col-span-2 p-10 text-center bg-white rounded-[2rem] border border-[#E9DDD2] text-[#9A8675] text-xs">
            <i class="fa-regular fa-door-closed text-3xl mb-3 text-[#F3A833]"></i>
            <p>Belum ada unit kamar yang terdaftar untuk properti ini.</p>
          </div>
        @endforelse
      </div>
    </section>

  </main>

  <!-- LIGHTBOX GALERI FOTO (MODAL) -->
  <div id="gallery-modal" class="fixed inset-0 bg-[#24150D]/95 z-[999] hidden flex-col justify-between p-4 sm:p-6 backdrop-blur-md">

    <div class="flex items-center justify-between max-w-5xl mx-auto w-full">
      <div>
        <span class="text-[9px] font-extrabold uppercase tracking-[.2em] text-[#F3A833]">Galeri Properti</span>
        <h3 class="font-black text-white text-base sm:text-lg mt-0.5">{{ $kosan->title }}</h3>
      </div>

      <div class="flex items-center gap-3">
        <span id="gallery-counter" class="text-xs sm:text-sm font-bold text-[#F1DCC8]">1 / 1</span>
        <button onclick="closeGallery()" class="w-10 h-10 rounded-xl bg-white/10 text-white hover:bg-[#E60049] transition-all flex items-center justify-center">
          <i class="fa-solid fa-xmark text-base"></i>
        </button>
      </div>
    </div>

    <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden max-w-5xl mx-auto w-full">
      <button onclick="prevImage()" class="absolute left-1 sm:left-4 w-11 h-11 rounded-2xl bg-white/10 text-white hover:bg-[#F3A833] hover:text-[#3B2314] transition-all flex items-center justify-center">
        <i class="fa-solid fa-chevron-left text-base"></i>
      </button>

      <div class="w-full h-[65vh] flex items-center justify-center px-10">
        <img id="active-gallery-img" src="" alt="Foto Kos" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl">
      </div>

      <button onclick="nextImage()" class="absolute right-1 sm:right-4 w-11 h-11 rounded-2xl bg-white/10 text-white hover:bg-[#F3A833] hover:text-[#3B2314] transition-all flex items-center justify-center">
        <i class="fa-solid fa-chevron-right text-base"></i>
      </button>
    </div>

    <div class="max-w-5xl mx-auto w-full overflow-x-auto no-scrollbar py-2">
      <div id="thumbnail-strip" class="flex items-center justify-center gap-2 min-w-max"></div>
    </div>

  </div>

  <!-- FOOTER -->
  @include('frontend.footer')

  <!-- JAVASCRIPT SYSTEM LOGIC -->
  <script>
    function scrollToSection(id) {
      const element = document.getElementById(id);

      if (element) {
        const offset = 90;
        const top = element.getBoundingClientRect().top + window.scrollY - offset;

        window.scrollTo({
          top: top,
          behavior: 'smooth'
        });

        document.querySelectorAll('.tab-button').forEach(button => {
          button.classList.remove('tab-active', 'bg-[#FFF8F1]');
          button.classList.add('text-[#F8EBDD]');
        });
      }
    }

    // Loading Bar
    window.addEventListener('load', () => {
      const loader = document.getElementById('page-loader');

      if (loader) {
        loader.style.width = '100%';

        setTimeout(() => {
          loader.style.opacity = '0';
        }, 300);
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
      if (!galleryImages.length) return;

      currentGalleryIndex = Math.max(0, Math.min(index, galleryImages.length - 1));

      renderGallery();

      galleryModal.classList.remove('hidden');
      galleryModal.classList.add('flex');

      document.body.classList.add('overflow-hidden');
    }

    function closeGallery() {
      galleryModal.classList.add('hidden');
      galleryModal.classList.remove('flex');

      document.body.classList.remove('overflow-hidden');
    }

    function renderGallery() {
      if (!galleryImages.length) return;

      activeGalleryImg.src = galleryImages[currentGalleryIndex];

      galleryCounter.textContent =
        `${currentGalleryIndex + 1} / ${galleryImages.length}`;

      thumbnailStrip.innerHTML = galleryImages.map((img, idx) => `
        <button
          type="button"
          onclick="setGalleryIndex(${idx})"
          class="w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden cursor-pointer border-2 transition-all ${
            idx === currentGalleryIndex
              ? 'border-[#F3A833] scale-105 shadow-lg opacity-100'
              : 'border-transparent opacity-50 hover:opacity-100'
          }"
        >
          <img src="${img}" alt="Thumbnail ${idx + 1}" class="w-full h-full object-cover">
        </button>
      `).join('');
    }

    function setGalleryIndex(index) {
      currentGalleryIndex = index;
      renderGallery();
    }

    function prevImage() {
      if (!galleryImages.length) return;

      currentGalleryIndex =
        (currentGalleryIndex - 1 + galleryImages.length) % galleryImages.length;

      renderGallery();
    }

    function nextImage() {
      if (!galleryImages.length) return;

      currentGalleryIndex =
        (currentGalleryIndex + 1) % galleryImages.length;

      renderGallery();
    }

    // Keyboard navigation
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