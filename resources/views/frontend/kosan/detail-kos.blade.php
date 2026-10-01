<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sinar Citra Lestari - {{ $kosan->title }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="icon" href="{{ asset('scl.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Leaflet.js Interactive Map CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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

    /* Custom Leaflet Markers */
    .custom-kos-marker {
      background: transparent;
      border: none;
    }
    .marker-pin {
      width: 42px;
      height: 42px;
      border-radius: 50% 50% 50% 0;
      background: #E60049;
      position: absolute;
      transform: rotate(-45deg);
      left: 50%;
      top: 50%;
      margin: -24px 0 0 -21px;
      box-shadow: 0 4px 15px rgba(230,0,73,0.45);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .marker-pin i {
      transform: rotate(45deg);
      color: #fff;
      font-size: 16px;
    }
    .marker-pulse {
      width: 14px;
      height: 14px;
      background: rgba(230,0,73,0.4);
      border-radius: 50%;
      position: absolute;
      left: 50%;
      top: 50%;
      margin: 12px 0 0 -7px;
      animation: map-pulse 1.8s ease-out infinite;
    }
    @keyframes map-pulse {
      0% { transform: scale(0.5); opacity: 1; }
      100% { transform: scale(3.5); opacity: 0; }
    }
    .leaflet-popup-content-wrapper {
      border-radius: 1.25rem !important;
      padding: 6px !important;
      box-shadow: 0 12px 35px rgba(59,35,20,0.18) !important;
      border: 1px solid #E9DDD2 !important;
    }
    .leaflet-control-zoom {
      border: none !important;
      box-shadow: 0 4px 15px rgba(59,35,20,0.12) !important;
      border-radius: 0.75rem !important;
      overflow: hidden;
      margin-top: 14px !important;
      margin-left: 14px !important;
    }
    .leaflet-control-zoom a {
      background-color: rgba(255, 255, 255, 0.95) !important;
      color: #3B2314 !important;
      border-bottom: 1px solid #E9DDD2 !important;
      transition: all 0.2s ease !important;
    }
    .leaflet-control-zoom a:hover {
      background-color: #FFF8F1 !important;
      color: #E60049 !important;
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

    // --- SMART LOCATION PARSER ---
    $gmapsRaw = trim($kosan->gmaps ?? '');
    $isIframe = str_contains($gmapsRaw, '<iframe');
    $isEmbedUrl = str_contains($gmapsRaw, 'google.com/maps/embed');

    // Extract iframe src if iframe tag is provided
    $embedSrc = null;
    if ($isIframe && preg_match('/src=["\']([^"\']+)["\']/', $gmapsRaw, $matches)) {
        $embedSrc = $matches[1];
    } elseif ($isEmbedUrl) {
        $embedSrc = $gmapsRaw;
    }

    // Resolve short Google Maps URLs (e.g. maps.app.goo.gl or goo.gl/maps) to obtain full target URL & exact coordinates
    $targetGmapsStr = $gmapsRaw;
    if (!empty($gmapsRaw) && (str_contains($gmapsRaw, 'maps.app.goo.gl') || str_contains($gmapsRaw, 'goo.gl/maps'))) {
        $targetGmapsStr = \Illuminate\Support\Facades\Cache::remember('gmaps_resolved_' . md5($gmapsRaw), 86400 * 7, function () use ($gmapsRaw) {
            $ch = curl_init($gmapsRaw);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_exec($ch);
            $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
            curl_close($ch);
            return $redirectUrl ?: $gmapsRaw;
        });
    }

    // Detect Place / Location Name from GMaps URL / Embed
    $detectedPlace = null;
    if (preg_match('#!2s([^!&"\']+)#', $targetGmapsStr, $placeMatch)) {
        $detectedPlace = urldecode(str_replace('+', ' ', $placeMatch[1]));
    } elseif (preg_match('#maps/place/([^/@?]+)#', $targetGmapsStr, $placeMatch)) {
        $detectedPlace = urldecode(str_replace('+', ' ', $placeMatch[1]));
    } elseif (preg_match('#[?&]q=([^&"\']+)#', $targetGmapsStr, $qMatch)) {
        $qVal = urldecode(str_replace('+', ' ', $qMatch[1]));
        if (!preg_match('#^-?[0-9.]+,-?[0-9.]+$#', $qVal)) {
            $detectedPlace = $qVal;
        }
    }

    // Accurate coordinates mapping for Bali regencies & key cities
    $coordinatesMap = [
        'denpasar'        => ['lat' => -8.6705, 'lng' => 115.2126],
        'badung'          => ['lat' => -8.5819, 'lng' => 115.1771],
        'gianyar'         => ['lat' => -8.5445, 'lng' => 115.3286],
        'tabanan'         => ['lat' => -8.5411, 'lng' => 115.1252],
        'buleleng'        => ['lat' => -8.1120, 'lng' => 115.0882],
        'karangasem'      => ['lat' => -8.4487, 'lng' => 115.6128],
        'klungkung'       => ['lat' => -8.5367, 'lng' => 115.4050],
        'bangli'          => ['lat' => -8.4539, 'lng' => 115.3551],
        'jembrana'        => ['lat' => -8.3585, 'lng' => 114.6360],
        'jakarta selatan' => ['lat' => -6.2615, 'lng' => 106.8106],
        'jakarta pusat'   => ['lat' => -6.1805, 'lng' => 106.8284],
        'jakarta barat'   => ['lat' => -6.1683, 'lng' => 106.7589],
        'bandung'         => ['lat' => -6.9175, 'lng' => 107.6191],
        'yogyakarta'      => ['lat' => -7.7956, 'lng' => 110.3695],
        'surabaya'        => ['lat' => -7.2575, 'lng' => 112.7521],
        'malang'          => ['lat' => -7.9666, 'lng' => 112.6326],
        'semarang'        => ['lat' => -6.9667, 'lng' => 110.4167],
    ];

    $wilayahClean = strtolower(trim($kosan->wilayah ?? 'denpasar'));
    $baseCoords = $coordinatesMap[$wilayahClean] ?? null;

    if (!$baseCoords) {
        foreach ($coordinatesMap as $k => $c) {
            if (str_contains($wilayahClean, $k)) {
                $baseCoords = $c;
                break;
            }
        }
    }
    if (!$baseCoords) {
        $baseCoords = ['lat' => -8.6705, 'lng' => 115.2126];
    }

    // Try extracting coordinates from Google Maps embed pb parameter (!2d... !3d... or !3d... !4d...) or @lat,lng
    if (preg_match('#!2d([0-9.-]+)!3d([0-9.-]+)#', $targetGmapsStr, $coordMatches)) {
        // In Google Maps embed PB: !2d is longitude, !3d is latitude
        $baseCoords = ['lat' => (float)$coordMatches[2], 'lng' => (float)$coordMatches[1]];
    } elseif (preg_match('#!3d([0-9.-]+)!4d([0-9.-]+)#', $targetGmapsStr, $coordMatches)) {
        $baseCoords = ['lat' => (float)$coordMatches[1], 'lng' => (float)$coordMatches[2]];
    } elseif (preg_match('#@(-?\d+\.\d+),(-?\d+\.\d+)#', $targetGmapsStr, $coordMatches)) {
        $baseCoords = ['lat' => (float)$coordMatches[1], 'lng' => (float)$coordMatches[2]];
    } elseif (preg_match('#[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)#', $targetGmapsStr, $coordMatches)) {
        $baseCoords = ['lat' => (float)$coordMatches[1], 'lng' => (float)$coordMatches[2]];
    }

    // Direct Google Maps link for button (opens exact location)
    if (!$isIframe && !empty($gmapsRaw) && filter_var($gmapsRaw, FILTER_VALIDATE_URL)) {
        $gmapsDirectUrl = $gmapsRaw;
    } elseif (!empty($detectedPlace)) {
        $gmapsDirectUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($detectedPlace . ' ' . ($kosan->wilayah ?? 'Bali'));
    } else {
        $gmapsDirectUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($kosan->title . ' ' . ($kosan->wilayah ?? 'Bali'));
    }

    // Route / Directions link
    if (!empty($detectedPlace)) {
        $directionsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($detectedPlace . ' ' . ($kosan->wilayah ?? 'Bali'));
    } elseif (!$isIframe && !empty($gmapsRaw) && filter_var($gmapsRaw, FILTER_VALIDATE_URL)) {
        $directionsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($gmapsRaw);
    } else {
        $directionsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($kosan->title . ' ' . ($kosan->wilayah ?? 'Bali'));
    }

    // Human-friendly location text for badges & subheaders
    $displayLocation = !empty($detectedPlace) ? ($detectedPlace . ' (' . ($kosan->wilayah ?? 'Bali') . ')') : ($kosan->wilayah ?? 'Lokasi Terdaftar');
    $displayLocationShort = !empty($detectedPlace) ? $detectedPlace : ($kosan->wilayah ?? 'Bali');

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

    // Hitung tarif bulanan terendah untuk ringkasan harga
    $minPrice = null;
    $maxPrice = null;
    foreach ($kosan->productKamarKosan as $km) {
        $mp = $km->priceKamar->first(function($p) {
            return strtolower($p->kategori) === 'bulan';
        });
        if ($mp) {
            if ($minPrice === null || $mp->price < $minPrice) $minPrice = $mp->price;
            if ($maxPrice === null || $mp->price > $maxPrice) $maxPrice = $mp->price;
        }
    }

    // Resolusi nomor WhatsApp pengelola / admin
    $waNumber = '6281234567890';
    if (isset($globalSosmed)) {
        foreach ($globalSosmed as $sm) {
            if (str_contains(strtolower($sm->title), 'whatsapp') || str_contains($sm->url, 'wa.me')) {
                preg_match('/[0-9]{9,15}/', $sm->url, $waMatch);
                if (!empty($waMatch[0])) {
                    $waNumber = $waMatch[0];
                    break;
                }
            }
        }
    }
    $waMessage = "Halo Pengelola Sinar Citra Lestari, saya tertarik dengan unit kos " . $kosan->title . " di " . ($kosan->wilayah ?? 'Bali') . ". Apakah masih ada kamar yang tersedia untuk disurvei/disewa?";
    $waInquiryUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($waMessage);
  @endphp

  <!-- MAIN CONTENT CONTAINER -->
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-7 space-y-10">

    <!-- 3. BREADCRUMB & TOP ACTION TOOLBAR -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs -mb-2">
      <nav class="flex items-center gap-2 text-[#7B6759] font-medium overflow-x-auto no-scrollbar py-1">
        <a href="{{ route('home') }}" class="hover:text-[#E60049] transition-colors whitespace-nowrap">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[9px] text-[#A69383]"></i>
        <a href="{{ route('kosan.index') }}" class="hover:text-[#E60049] transition-colors whitespace-nowrap">Daftar Kos</a>
        <i class="fa-solid fa-chevron-right text-[9px] text-[#A69383]"></i>
        <span class="font-bold text-[#3B2314] truncate max-w-[200px] sm:max-w-[320px]">{{ $kosan->title }}</span>
      </nav>

      <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
        <!-- Bookmark / Simpan -->
        <button
          type="button"
          onclick="toggleBookmark('{{ $kosan->slug }}')"
          id="bookmark-btn"
          class="px-3.5 py-2 bg-white hover:bg-[#FFF2E5] text-[#5D483A] border border-[#E9DDD2] rounded-xl font-bold text-xs shadow-sm transition-all flex items-center gap-1.5 active:scale-95"
          title="Simpan Kos ini"
        >
          <i class="fa-regular fa-heart text-xs text-[#E60049]"></i>
          <span id="bookmark-label">Simpan</span>
        </button>

        <!-- Share / Bagikan -->
        <button
          type="button"
          onclick="copyKosanLink()"
          class="px-3.5 py-2 bg-white hover:bg-[#FFF2E5] text-[#5D483A] border border-[#E9DDD2] rounded-xl font-bold text-xs shadow-sm transition-all flex items-center gap-1.5 active:scale-95"
          title="Bagikan Tautan Kos"
        >
          <i class="fa-solid fa-share-nodes text-xs text-[#00A896]"></i>
          <span>Bagikan</span>
        </button>

        <!-- WhatsApp Inquire -->
        <a
          href="{{ $waInquiryUrl }}"
          target="_blank"
          rel="noopener noreferrer"
          class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all flex items-center gap-1.5 active:scale-95"
          title="Tanya Pengelola via WhatsApp"
        >
          <i class="fa-brands fa-whatsapp text-sm"></i>
          <span>Tanya Pengelola</span>
        </a>
      </div>
    </section>

    <!-- 4. MODERN BENTO GRID GALLERY -->
    <section class="reveal">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4">

        <!-- Photo Utama (Hero Item) -->
        <div class="lg:col-span-7">
          <div
            class="relative h-[280px] sm:h-[400px] lg:h-[474px] rounded-[2rem] overflow-hidden cursor-pointer bg-[#EADFD4] group shadow-lg"
            onclick="openGallery(0)"
          >
            <img
              src="{{ $images[0] }}"
              alt="{{ $kosan->title }}"
              class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700 ease-out"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-[#3B2314]/80 via-[#3B2314]/20 to-transparent pointer-events-none"></div>

            <div class="absolute left-4 bottom-4 sm:left-6 sm:bottom-6 right-4 sm:right-6 flex items-end justify-between gap-4 text-white">
              <div>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#E60049] rounded-full text-[10px] font-black uppercase tracking-wider shadow-md">
                  <i class="fa-solid fa-camera"></i>
                  Foto Properti Utama
                </span>
                <p class="mt-2 text-xs sm:text-sm font-bold text-white/90">Klik foto untuk perbesar layar penuh</p>
              </div>

              <span class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0 group-hover:bg-[#E60049] transition-colors">
                <i class="fa-solid fa-expand text-sm"></i>
              </span>
            </div>
          </div>
        </div>

        <!-- 4 Sub Photos (2x2 Grid) -->
        <div class="lg:col-span-5 grid grid-cols-2 gap-3 sm:gap-4">
          @for($i = 1; $i <= 4; $i++)
            @if(isset($images[$i]))
              <div
                class="relative h-[135px] sm:h-[195px] lg:h-[231px] overflow-hidden rounded-[1.5rem] bg-[#EADFD4] cursor-pointer group shadow-sm"
                onclick="openGallery({{ $i }})"
              >
                <img
                  src="{{ $images[$i] }}"
                  alt="Foto {{ $i }}"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                >

                @if($i === 4)
                  <div class="absolute inset-0 bg-[#3B2314]/65 backdrop-blur-[2px] flex flex-col items-center justify-center text-white gap-1.5 group-hover:bg-[#3B2314]/75 transition-colors">
                    <span class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                      <i class="fa-regular fa-images text-base"></i>
                    </span>
                    <span class="text-xs font-black tracking-wide">Lihat Semua Foto</span>
                    <span class="text-[10px] font-semibold text-white/70">({{ count($images) }} Foto)</span>
                  </div>
                @else
                  <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                @endif
              </div>
            @else
              <div class="h-[135px] sm:h-[195px] lg:h-[231px] rounded-[1.5rem] bg-[#F2E9E1] flex flex-col items-center justify-center text-[#B6A393] gap-1">
                <i class="fa-regular fa-image text-xl"></i>
                <span class="text-[10px] font-bold">Foto Tambahan</span>
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

          <div class="flex flex-wrap items-center gap-2 mb-2.5">
            @if($kosan->available_rooms_count > 0)
              <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-full text-[11px] font-black tracking-wide">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Tersedia {{ $kosan->available_rooms_count }} Kamar Siap Huni</span>
              </span>
            @else
              <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200/80 rounded-full text-[11px] font-black tracking-wide">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span>Kamar Terisi Penuh</span>
              </span>
            @endif

            @if($minPrice)
              <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#F3A833]/15 text-[#9A7651] border border-[#F3A833]/30 rounded-full text-[11px] font-black tracking-wide">
                <span>Mulai Rp {{ number_format($minPrice, 0, ',', '.') }}</span>
                <span class="text-[9px] font-semibold text-[#8F7765]">/bulan</span>
              </span>
            @endif
          </div>

          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#3B2314] leading-[1.05]">
            {{ $kosan->title }}
          </h1>

          <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs mt-4">
            <span class="flex items-center gap-2 font-bold text-[#6E594A]">
              <span class="w-8 h-8 rounded-xl bg-[#E60049]/10 text-[#E60049] flex items-center justify-center">
                <i class="fa-solid fa-location-dot"></i>
              </span>
              {{ $displayLocation }}
            </span>

            <span class="flex items-center gap-2 font-semibold text-[#8F7765]">
              <span class="w-8 h-8 rounded-xl bg-[#00A896]/10 text-[#00A896] flex items-center justify-center">
                <i class="fa-solid fa-eye"></i>
              </span>
              {{ number_format($kosan->view ?? 0) }} dilihat
            </span>

            <span class="flex items-center gap-2 font-semibold text-[#8F7765]">
              <span class="w-8 h-8 rounded-xl bg-[#F3A833]/15 text-[#F3A833] flex items-center justify-center">
                <i class="fa-solid fa-door-open"></i>
              </span>
              {{ $kosan->productKamarKosan->count() }} Tipe Kamar
            </span>
          </div>
        </div>

        <!-- Quick navigation dibuat seperti action rail -->
        <div class="lg:col-span-5">
          <div class="bg-[#3B2314] rounded-[1.5rem] p-2 shadow-lg overflow-x-auto no-scrollbar">
            <div class="flex items-center min-w-max gap-1">
              <button type="button" onclick="scrollToSection('deskripsi', this)" class="tab-button tab-active px-3 sm:px-3.5 py-2.5 rounded-xl bg-[#FFF8F1] text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                Deskripsi
              </button>

              <button type="button" onclick="scrollToSection('fasilitas', this)" class="tab-button px-3 sm:px-3.5 py-2.5 rounded-xl text-[#F8EBDD] hover:bg-white/10 text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                Fasilitas
              </button>

              <button type="button" onclick="scrollToSection('lokasi', this)" class="tab-button px-3 sm:px-3.5 py-2.5 rounded-xl text-[#F8EBDD] hover:bg-white/10 text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                Lokasi
              </button>

              <button type="button" onclick="scrollToSection('kamar-tersedia', this)" class="tab-button px-3 sm:px-3.5 py-2.5 rounded-xl text-[#F8EBDD] hover:bg-white/10 text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                Kamar ({{ $kosan->productKamarKosan->count() }})
              </button>

              <button type="button" onclick="scrollToSection('peraturan', this)" class="tab-button px-3 sm:px-3.5 py-2.5 rounded-xl text-[#F8EBDD] hover:bg-white/10 text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                Tata Tertib
              </button>

              <button type="button" onclick="scrollToSection('faq', this)" class="tab-button px-3 sm:px-3.5 py-2.5 rounded-xl text-[#F8EBDD] hover:bg-white/10 text-[10px] sm:text-xs font-extrabold whitespace-nowrap transition-all">
                FAQ
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

    <!-- 7. LOKASI & PETA FASILITAS SEKITAR -->
    <section id="lokasi" class="reveal space-y-6">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <span class="text-[10px] font-extrabold uppercase tracking-[.2em] text-[#E60049]">03 — Lokasi & Fasilitas Sekitar</span>
          <h3 class="text-2xl sm:text-3xl font-black text-[#3B2314] mt-1">Area strategis & kemudahan akses</h3>
          <p class="text-xs sm:text-sm text-[#7B6759] mt-1 max-w-xl">
            @if(!empty($detectedPlace))
              Berada di sekitar area <strong class="text-[#3B2314]">{{ $detectedPlace }}</strong> ({{ $kosan->wilayah ?? 'Bali' }}) dengan akses cepat ke pusat perkantoran, universitas, minimarket, dan fasilitas kesehatan.
            @else
              Berada di kawasan <strong class="text-[#3B2314]">{{ $kosan->wilayah ?? 'Bali' }}</strong> dengan akses cepat ke pusat perkantoran, universitas, minimarket, dan fasilitas kesehatan.
            @endif
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-white rounded-xl border border-[#EADFD4] shadow-sm text-xs font-bold text-[#5D493A]">
            <i class="fa-solid fa-map-pin text-[#E60049]"></i>
            <span>{{ $displayLocationShort }}</span>
          </div>

          @if($kosan->gmaps)
            <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-xl text-xs font-bold shadow-sm">
              <i class="fa-solid fa-circle-check text-emerald-500"></i>
              <span>Tersinkron GMaps</span>
            </span>
          @endif

          <a
            href="{{ $directionsUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="px-3.5 py-2 bg-white hover:bg-[#F3A833] text-[#3B2314] border border-[#EADFD4] text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-1.5"
            title="Buka Petunjuk Arah Google Maps"
          >
            <i class="fa-solid fa-route text-[#00A896]"></i>
            <span>Petunjuk Arah</span>
          </a>

          <a
            href="{{ $gmapsDirectUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="px-4 py-2 bg-[#3B2314] hover:bg-[#E60049] text-white text-xs font-black rounded-xl shadow-md transition-all flex items-center gap-2"
            title="Buka di Google Maps"
          >
            <i class="fa-solid fa-diamond-turn-right text-[#F3A833]"></i>
            <span>Buka Google Maps</span>
          </a>
        </div>
      </div>

      <!-- Container Peta Interaktif / Embed Google Maps -->
      <div class="relative rounded-[2rem] overflow-hidden border-4 border-white shadow-xl bg-[#EDE4DC]">
        @if($embedSrc)
          <!-- Tampilan Embed Iframe Langsung -->
          <div class="w-full h-[340px] sm:h-[420px] bg-neutral-100">
            <iframe
              src="{{ $embedSrc }}"
              class="w-full h-full border-0"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        @else
          <!-- Interactive Vicinity Map (Leaflet) centered on accurate coordinates -->
          <div id="vicinity-map" class="w-full h-[360px] sm:h-[440px] z-10"></div>
        @endif

        <div class="absolute bottom-3 right-3 z-20 bg-white/95 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-[#E9DDD2] text-[11px] font-bold text-[#5D483A] shadow-md flex items-center gap-2 pointer-events-none">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <span>{{ $displayLocationShort }} • {{ $kosan->title }}</span>
        </div>

        @if(!$embedSrc)
          <!-- Layer Switcher & Recenter Controls inside Map -->
          <div class="absolute top-3 right-3 z-20 flex items-center bg-white/95 backdrop-blur-md p-1 rounded-xl border border-[#E9DDD2] shadow-md text-xs font-bold gap-1">
            <button
              id="map-btn-street"
              type="button"
              class="px-2.5 py-1.5 rounded-lg bg-[#3B2314] text-white text-[11px] font-extrabold transition-all shadow-sm flex items-center gap-1.5"
            >
              <i class="fa-solid fa-map"></i>
              <span>Peta Jalan</span>
            </button>
            <button
              id="map-btn-sat"
              type="button"
              class="px-2.5 py-1.5 rounded-lg text-[#5D483A] hover:text-[#3B2314] text-[11px] font-extrabold transition-all flex items-center gap-1.5"
            >
              <i class="fa-solid fa-earth-americas"></i>
              <span>Satelit</span>
            </button>
            <button
              id="map-btn-recenter"
              type="button"
              class="p-1.5 px-2 text-[#7B6759] hover:text-[#E60049] rounded-lg transition-colors border-l border-[#E9DDD2]"
              title="Fokus ke Lokasi Kosan"
            >
              <i class="fa-solid fa-crosshairs"></i>
            </button>
          </div>
        @endif
      </div>

      <!-- 4 Vicinity Cards (Contextual to Region) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="bg-white p-4 rounded-2xl border border-[#E9DDD2] shadow-sm hover:border-[#0284c7] transition-all flex items-start gap-3.5">
          <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 text-base">
            <i class="fa-solid fa-graduation-cap"></i>
          </div>
          <div>
            <h4 class="text-xs font-extrabold text-[#3B2314]">Kampus / Pendidikan</h4>
            <p class="text-[11px] text-[#7B6759] mt-0.5">Area {{ $displayLocationShort }}</p>
            <span class="inline-block mt-2 text-[10px] font-black text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md">
              🚗 Akses Cepat Kendaraan
            </span>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-[#E9DDD2] shadow-sm hover:border-emerald-500 transition-all flex items-start gap-3.5">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-base">
            <i class="fa-solid fa-cart-shopping"></i>
          </div>
          <div>
            <h4 class="text-xs font-extrabold text-[#3B2314]">Kebutuhan Harian</h4>
            <p class="text-[11px] text-[#7B6759] mt-0.5">Minimarket & Pasar {{ $displayLocationShort }}</p>
            <span class="inline-block mt-2 text-[10px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
              🚶 Dekat Jangkauan
            </span>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-[#E9DDD2] shadow-sm hover:border-rose-500 transition-all flex items-start gap-3.5">
          <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 text-base">
            <i class="fa-solid fa-heart-pulse"></i>
          </div>
          <div>
            <h4 class="text-xs font-extrabold text-[#3B2314]">Layanan Kesehatan</h4>
            <p class="text-[11px] text-[#7B6759] mt-0.5">Faskes & Apotek {{ $displayLocationShort }}</p>
            <span class="inline-block mt-2 text-[10px] font-black text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md">
              🚗 Fasilitas Lengkap
            </span>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-[#E9DDD2] shadow-sm hover:border-amber-500 transition-all flex items-start gap-3.5">
          <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 text-base">
            <i class="fa-solid fa-mug-hot"></i>
          </div>
          <div>
            <h4 class="text-xs font-extrabold text-[#3B2314]">Kuliner & Laundry</h4>
            <p class="text-[11px] text-[#7B6759] mt-0.5">Warung Kuliner & Laundry Kiloan</p>
            <span class="inline-block mt-2 text-[10px] font-black text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">
              🚶 Kemudahan Akses
            </span>
          </div>
        </div>
      </div>
    </section>

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

            $yearlyPriceObj = $kamarItem->priceKamar->first(function($price) {
              return strtolower($price->kategori) === 'tahun';
            });
            $yearlyPrice = $yearlyPriceObj ? $yearlyPriceObj->price : null;

            $roomStatus = $kamarItem->room_status ?? 'kosong';
            $isOccupied = ($roomStatus === 'terisi');
            $kamarFasilitas = array_filter(array_map('trim', explode(',', $kamarItem->fasilitas ?? '')));
            $imgCount = $kamarItem->productKamarImageKosan->count();

            $kamarWaMsg = "Halo Pengelola Sinar Citra Lestari, saya tertarik dengan unit kamar " . $kamarItem->room . " di " . $kosan->title . " (yang saat ini berstatus terisi). Apakah ada perkiraan jadwal kamar ini akan kosong kembali?";
            $kamarWaUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($kamarWaMsg);
          @endphp

          <!-- ROOM CARD -->
          <div class="room-card bg-white rounded-[2rem] border {{ $isOccupied ? 'border-[#E9DDD2] opacity-95' : 'border-[#E9DDD2]' }} shadow-sm overflow-hidden group hover:border-[#F3A833] transition-all">
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

                <!-- Status Badge -->
                <div class="absolute top-3 left-3">
                  @if($isOccupied)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-600/90 backdrop-blur-sm text-white rounded-full text-[10px] font-black shadow-md">
                      <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                      Terisi (Penuh)
                    </span>
                  @elseif($roomStatus === 'pending')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/90 backdrop-blur-sm text-white rounded-full text-[10px] font-black shadow-md">
                      <i class="fa-solid fa-clock text-[9px]"></i>
                      Verifikasi
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-600/90 backdrop-blur-sm text-white rounded-full text-[10px] font-black shadow-md">
                      <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                      Siap Huni
                    </span>
                  @endif
                </div>

                <!-- Total Photo Badge -->
                @if($imgCount > 0)
                  <div class="absolute bottom-3 right-3 px-2 py-0.5 bg-[#24150D]/80 backdrop-blur-sm text-white rounded-lg text-[9px] font-bold flex items-center gap-1">
                    <i class="fa-solid fa-camera text-[8px]"></i>
                    <span>{{ $imgCount }} Foto</span>
                  </div>
                @endif
              </div>

              <div class="sm:col-span-3 p-5 flex flex-col justify-between">
                <div>
                  <h4 class="font-black text-[#3B2314] text-lg leading-tight">
                    <a href="{{ route('kamar.detail', $kamarItem->id) }}" class="hover:text-[#E60049] transition-colors">
                      {{ $kamarItem->room }}
                    </a>
                  </h4>

                  <p class="text-xs text-[#8F7765] mt-1.5 leading-relaxed line-clamp-2">
                    {{ strip_tags($kamarItem->description ?? 'Fasilitas kamar lengkap, bersih, dan nyaman.') }}
                  </p>

                  <!-- Amenities Pills -->
                  @if(count($kamarFasilitas) > 0)
                    <div class="flex flex-wrap gap-1 mt-2.5">
                      @foreach(array_slice($kamarFasilitas, 0, 3) as $kFas)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#FAF4ED] border border-[#EADFD4] text-[#6F5A4B] text-[10px] font-bold">
                          <i class="fa-solid fa-check text-[8px] text-[#00A896]"></i>
                          {{ $kFas }}
                        </span>
                      @endforeach
                      @if(count($kamarFasilitas) > 3)
                        <span class="px-1.5 py-0.5 rounded-md bg-[#FAF4ED] text-[#8F7765] text-[10px] font-bold">
                          +{{ count($kamarFasilitas) - 3 }} lainnya
                        </span>
                      @endif
                    </div>
                  @endif

                  <!-- Pricing Container -->
                  <div class="mt-4 p-3 rounded-2xl bg-[#F3A833]/10 border border-[#F3A833]/15">
                    <span class="text-[9px] uppercase font-extrabold tracking-wider text-[#9A7651] block">Tarif Sewa</span>

                    @if($monthlyPrice)
                      <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-xl font-black text-[#E60049]">
                          Rp {{ number_format($monthlyPrice, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] font-semibold text-[#8F7765]">/bulan</span>
                      </div>
                      @if($yearlyPrice)
                        <div class="text-[10px] text-[#8F7765] font-semibold mt-1">
                          Opsi Tahunan: <strong class="text-[#3B2314]">Rp {{ number_format($yearlyPrice, 0, ',', '.') }}</strong>
                        </div>
                      @endif
                    @else
                      <span class="text-xs font-extrabold text-[#6F5A4B]">Hubungi Admin</span>
                    @endif
                  </div>
                </div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <a
                    href="{{ route('kamar.detail', $kamarItem->id) }}"
                    class="w-full px-3 py-2.5 bg-white hover:bg-[#EDE4DC] text-[#3B2314] border border-[#E9DDD2] text-xs font-black rounded-xl transition-all shadow-sm active:scale-[.98] flex items-center justify-center gap-1.5 text-center"
                  >
                    <span>Detail Kamar</span>
                    <i class="fa-solid fa-arrow-right text-[10px] text-[#7B6759]"></i>
                  </a>

                  @if($isOccupied)
                    <a
                      href="{{ $kamarWaUrl }}"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="w-full px-3 py-2.5 bg-[#FAF4ED] hover:bg-[#F3E8DC] text-[#7B6759] border border-[#E9DDD2] text-xs font-bold rounded-xl transition-all active:scale-[.98] flex items-center justify-center gap-1.5 text-center"
                      title="Kamar sedang penuh, tanya antrean/jadwal ketersediaan"
                    >
                      <i class="fa-brands fa-whatsapp text-emerald-600 text-xs"></i>
                      <span>Waiting List</span>
                    </a>
                  @else
                    <a
                      href="{{ route('form.booking.kamar', $kamarItem->id) }}"
                      class="shine w-full px-3 py-2.5 bg-[#E60049] hover:bg-[#C90040] text-white text-xs font-black rounded-xl transition-all shadow-md active:scale-[.98] flex items-center justify-center gap-1.5"
                    >
                      <i class="fa-solid fa-bolt text-[#F3A833] text-[11px]"></i>
                      <span>Pesan Langsung</span>
                    </a>
                  @endif
                </div>
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

    <!-- 8.1 PERATURAN & TATA TERTIB KOS -->
    <section id="peraturan" class="reveal space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
        <div>
          <span class="text-[10px] font-extrabold uppercase tracking-[.2em] text-[#E60049]">05 — Tata Tertib</span>
          <h3 class="text-2xl sm:text-3xl font-black text-[#3B2314] mt-1">Peraturan & Ketentuan Huni</h3>
          <p class="text-xs sm:text-sm text-[#7B6759] mt-1">Dirancang untuk menciptakan kenyamanan, privasi, dan ketenangan seluruh penghuni kos.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold">
          <i class="fa-solid fa-shield-halved text-emerald-600"></i>
          <span>Lingkungan Nyaman & Tertib</span>
        </span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Rule 1: Jam Bertamu -->
        <div class="bg-white p-5 rounded-2xl border border-[#E9DDD2] shadow-sm hover:shadow-md transition-shadow">
          <div class="w-10 h-10 rounded-xl bg-[#E60049]/10 text-[#E60049] flex items-center justify-center text-lg mb-3">
            <i class="fa-regular fa-clock"></i>
          </div>
          <h4 class="text-sm font-black text-[#3B2314]">Jam Bertamu & Istirahat</h4>
          <p class="text-xs text-[#7B6759] mt-1 leading-relaxed">Tamu diperkenankan berkunjung maksimal hingga pkl 22.00 WITA untuk menjaga ketenangan jam istirahat penghuni lain.</p>
        </div>

        <!-- Rule 2: Ketertiban & Larangan Merokok -->
        <div class="bg-white p-5 rounded-2xl border border-[#E9DDD2] shadow-sm hover:shadow-md transition-shadow">
          <div class="w-10 h-10 rounded-xl bg-[#F3A833]/15 text-[#D97706] flex items-center justify-center text-lg mb-3">
            <i class="fa-solid fa-ban-smoking"></i>
          </div>
          <h4 class="text-sm font-black text-[#3B2314]">Bebas Asap di Ruang AC</h4>
          <p class="text-xs text-[#7B6759] mt-1 leading-relaxed">Dilarang merokok di dalam kamar ber-AC. Merokok hanya diperbolehkan di area luar/balkon terbuka yang disediakan.</p>
        </div>

        <!-- Rule 3: Parkir Rapi -->
        <div class="bg-white p-5 rounded-2xl border border-[#E9DDD2] shadow-sm hover:shadow-md transition-shadow">
          <div class="w-10 h-10 rounded-xl bg-[#00A896]/10 text-[#00A896] flex items-center justify-center text-lg mb-3">
            <i class="fa-solid fa-square-parking"></i>
          </div>
          <h4 class="text-sm font-black text-[#3B2314]">Area Parkir Tertib</h4>
          <p class="text-xs text-[#7B6759] mt-1 leading-relaxed">Parkir kendaraan motor/mobil di area yang telah ditentukan secara rapi dan pastikan selalu dikunci stang ganda.</p>
        </div>

        <!-- Rule 4: Kebersihan Bersama -->
        <div class="bg-white p-5 rounded-2xl border border-[#E9DDD2] shadow-sm hover:shadow-md transition-shadow">
          <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg mb-3">
            <i class="fa-solid fa-broom"></i>
          </div>
          <h4 class="text-sm font-black text-[#3B2314]">Kebersihan Area Bersama</h4>
          <p class="text-xs text-[#7B6759] mt-1 leading-relaxed">Wajib mencuci peralatan masak setelah menggunakan dapur bersama serta membuang sampah pada tempat yang disediakan.</p>
        </div>

        <!-- Rule 5: Penggunaan Listrik -->
        <div class="bg-white p-5 rounded-2xl border border-[#E9DDD2] shadow-sm hover:shadow-md transition-shadow">
          <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg mb-3">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <h4 class="text-sm font-black text-[#3B2314]">Penggunaan Daya Listrik</h4>
          <p class="text-xs text-[#7B6759] mt-1 leading-relaxed">Pemakaian alat elektronik berdaya tinggi (seperti dispenser pribadi, air fryer) harap dikonfirmasikan ke pengelola kos.</p>
        </div>

        <!-- Rule 6: Kebijakan Hewan Peliharaan -->
        <div class="bg-white p-5 rounded-2xl border border-[#E9DDD2] shadow-sm hover:shadow-md transition-shadow">
          <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg mb-3">
            <i class="fa-solid fa-paw"></i>
          </div>
          <h4 class="text-sm font-black text-[#3B2314]">Hewan Peliharaan</h4>
          <p class="text-xs text-[#7B6759] mt-1 leading-relaxed">Kebijakan membawa hewan peliharaan memerlukan izin pengelola untuk memastikan tidak mengganggu kenyamanan tetangga.</p>
        </div>
      </div>
    </section>

    <!-- 8.2 FAQ / PERTANYAAN UMUM -->
    <section id="faq" class="reveal space-y-6">
      <div>
        <span class="text-[10px] font-extrabold uppercase tracking-[.2em] text-[#00A896]">06 — Bantuan & FAQ</span>
        <h3 class="text-2xl sm:text-3xl font-black text-[#3B2314] mt-1">Pertanyaan yang Sering Diajukan</h3>
        <p class="text-xs sm:text-sm text-[#7B6759] mt-1">Informasi ringkas seputar proses booking, pembayaran, dan survei lokasi kos.</p>
      </div>

      <div class="space-y-3">
        <!-- FAQ 1 -->
        <div class="bg-white rounded-2xl border border-[#E9DDD2] overflow-hidden shadow-sm">
          <button type="button" onclick="toggleFaq(1)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-black text-sm sm:text-base text-[#3B2314] hover:text-[#E60049] transition-colors">
            <span class="flex items-center gap-3">
              <span class="w-7 h-7 rounded-lg bg-[#E60049]/10 text-[#E60049] flex items-center justify-center text-xs shrink-0 font-black">1</span>
              <span>Bagaimana cara melakukan survei langsung ke lokasi kos?</span>
            </span>
            <i id="faq-icon-1" class="fa-solid fa-chevron-down text-xs text-[#8F7765] transition-transform duration-300"></i>
          </button>
          <div id="faq-content-1" class="hidden px-5 pb-5 pt-1 text-xs sm:text-sm text-[#6F5A4B] leading-relaxed border-t border-[#F4EBE3]">
            Anda dapat langsung menghubungi pengelola via WhatsApp melalui tombol <strong>Tanya Pengelola</strong>. Jadwalkan waktu kunjungan Anda agar penjaga kos dapat mendampingi dan memperlihatkan kondisi unit kamar secara langsung.
          </div>
        </div>

        <!-- FAQ 2 -->
        <div class="bg-white rounded-2xl border border-[#E9DDD2] overflow-hidden shadow-sm">
          <button type="button" onclick="toggleFaq(2)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-black text-sm sm:text-base text-[#3B2314] hover:text-[#E60049] transition-colors">
            <span class="flex items-center gap-3">
              <span class="w-7 h-7 rounded-lg bg-[#00A896]/10 text-[#00A896] flex items-center justify-center text-xs shrink-0 font-black">2</span>
              <span>Apa saja yang sudah termasuk dalam harga sewa bulanan?</span>
            </span>
            <i id="faq-icon-2" class="fa-solid fa-chevron-down text-xs text-[#8F7765] transition-transform duration-300"></i>
          </button>
          <div id="faq-content-2" class="hidden px-5 pb-5 pt-1 text-xs sm:text-sm text-[#6F5A4B] leading-relaxed border-t border-[#F4EBE3]">
            Tarif bulanan telah mencakup biaya kebersihan lingkungan, retribusi sampah, air bersih, serta koneksi internet Wi-Fi gratis. Penggunaan listrik kamar mengikuti fasilitas masing-masing unit (apakah menggunakan token mandiri atau sudah include listrik).
          </div>
        </div>

        <!-- FAQ 3 -->
        <div class="bg-white rounded-2xl border border-[#E9DDD2] overflow-hidden shadow-sm">
          <button type="button" onclick="toggleFaq(3)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-black text-sm sm:text-base text-[#3B2314] hover:text-[#E60049] transition-colors">
            <span class="flex items-center gap-3">
              <span class="w-7 h-7 rounded-lg bg-[#F3A833]/20 text-[#D97706] flex items-center justify-center text-xs shrink-0 font-black">3</span>
              <span>Bagaimana alur pemesanan (booking) kamar online di Sinar Citra Lestari?</span>
            </span>
            <i id="faq-icon-3" class="fa-solid fa-chevron-down text-xs text-[#8F7765] transition-transform duration-300"></i>
          </button>
          <div id="faq-content-3" class="hidden px-5 pb-5 pt-1 text-xs sm:text-sm text-[#6F5A4B] leading-relaxed border-t border-[#F4EBE3]">
            Pilih unit kamar yang bertanda hijau <em>"Siap Huni"</em>, klik tombol <strong>Pesan Langsung</strong>, pilih tanggal mulai masuk di kalender interaktif, lalu isi formulir biodata serta unggah bukti pembayaran. Admin akan memverifikasi dalam waktu 1x24 jam dan mengirimkan invoice resmi ke email Anda.
          </div>
        </div>

        <!-- FAQ 4 -->
        <div class="bg-white rounded-2xl border border-[#E9DDD2] overflow-hidden shadow-sm">
          <button type="button" onclick="toggleFaq(4)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-black text-sm sm:text-base text-[#3B2314] hover:text-[#E60049] transition-colors">
            <span class="flex items-center gap-3">
              <span class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs shrink-0 font-black">4</span>
              <span>Apakah bisa sewa harian atau mingguan?</span>
            </span>
            <i id="faq-icon-4" class="fa-solid fa-chevron-down text-xs text-[#8F7765] transition-transform duration-300"></i>
          </button>
          <div id="faq-content-4" class="hidden px-5 pb-5 pt-1 text-xs sm:text-sm text-[#6F5A4B] leading-relaxed border-t border-[#F4EBE3]">
            Sistem utama kami melayani sewa bulanan dan tahunan. Untuk ketersediaan sewa jangka pendek (harian/mingguan), silakan konsultasikan langsung dengan pengelola via WhatsApp.
          </div>
        </div>
      </div>
    </section>

    <!-- 8.3 REKOMENDASI KOS LAINNYA (CROSS-SELLING) -->
    @if(isset($relatedKosans) && $relatedKosans->count() > 0)
      <section class="reveal pt-4 border-t border-[#EADFD4]">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-6">
          <div>
            <span class="text-[10px] font-extrabold uppercase tracking-[.2em] text-[#E60049]">07 — Rekomendasi</span>
            <h3 class="text-2xl sm:text-3xl font-black text-[#3B2314] mt-1">Kos Lainnya di Sekitarmu</h3>
            <p class="text-xs sm:text-sm text-[#7B6759] mt-0.5">Jelajahi pilihan kos lainnya dari jaringan Sinar Citra Lestari.</p>
          </div>

          <a href="{{ route('kosan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-black text-[#E60049] hover:underline">
            <span>Lihat Semua Kos</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
          </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          @foreach($relatedKosans as $relKos)
            @php
              $relImg = $relKos->productImageKosan->first();
              $relImgUrl = $relImg ? asset('storage/' . $relImg->image) : 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=600&q=80';

              $relMinPrice = null;
              foreach ($relKos->productKamarKosan as $rKm) {
                $rPrice = $rKm->priceKamar->first(fn($p) => strtolower($p->kategori) === 'bulan');
                if ($rPrice && ($relMinPrice === null || $rPrice->price < $relMinPrice)) {
                  $relMinPrice = $rPrice->price;
                }
              }
            @endphp

            <div class="bg-white rounded-3xl border border-[#E9DDD2] overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between">
              <div>
                <div class="relative h-44 overflow-hidden bg-[#EADFD4]">
                  <img src="{{ $relImgUrl }}" alt="{{ $relKos->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                  <div class="absolute top-3 left-3 px-2.5 py-1 bg-[#3B2314]/80 backdrop-blur-md rounded-full text-white text-[10px] font-bold">
                    <i class="fa-solid fa-location-dot text-[#E60049]"></i> {{ $relKos->wilayah ?? 'Bali' }}
                  </div>
                </div>

                <div class="p-4 sm:p-5">
                  <h4 class="font-black text-[#3B2314] text-base group-hover:text-[#E60049] transition-colors line-clamp-1">
                    {{ $relKos->title }}
                  </h4>
                  <p class="text-xs text-[#7B6759] mt-1">
                    <i class="fa-solid fa-door-open text-[#00A896]"></i> {{ $relKos->productKamarKosan->count() }} Tipe Kamar
                  </p>

                  <div class="mt-4 pt-3 border-t border-[#F2E7DF] flex items-center justify-between">
                    <div>
                      <span class="text-[9px] uppercase font-bold text-[#8E7B6D] block">Mulai</span>
                      @if($relMinPrice)
                        <span class="text-sm font-black text-[#E60049]">Rp {{ number_format($relMinPrice, 0, ',', '.') }}</span>
                        <span class="text-[10px] text-[#8F7765]">/bln</span>
                      @else
                        <span class="text-xs font-bold text-[#6F5A4B]">Hubungi Admin</span>
                      @endif
                    </div>

                    <a href="{{ route('kosan.detail', $relKos->slug) }}" class="px-3 py-1.5 bg-[#3B2314] hover:bg-[#E60049] text-white text-xs font-bold rounded-xl transition-colors shadow-sm flex items-center gap-1">
                      <span>Lihat</span>
                      <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </section>
    @endif
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

  <!-- TOAST NOTIFICATION -->
  <div id="toast-message" class="fixed top-5 right-5 z-[9999] hidden items-center gap-2.5 px-4 py-3 bg-[#3B2314] text-white text-xs font-bold rounded-2xl shadow-2xl transition-all duration-300 border border-white/10">
    <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
    <span id="toast-text">Tautan berhasil disalin!</span>
  </div>

  <!-- FLOATING WHATSAPP BUTTON (DESKTOP) -->
  <a
    href="{{ $waInquiryUrl }}"
    target="_blank"
    rel="noopener noreferrer"
    class="fixed bottom-6 right-6 z-40 hidden lg:flex items-center gap-2.5 px-5 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full shadow-[0_8px_25px_rgba(16,185,129,0.35)] hover:scale-105 active:scale-95 transition-all text-xs font-black group"
    title="Tanya Pengelola Kosan via WhatsApp"
  >
    <i class="fa-brands fa-whatsapp text-xl"></i>
    <span>Tanya Pengelola</span>
  </a>

  <!-- FOOTER -->
  @include('frontend.footer')

  <!-- JAVASCRIPT SYSTEM LOGIC -->
  <script>
    function scrollToSection(id, btn) {
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

        if (btn) {
          btn.classList.add('tab-active', 'bg-[#FFF8F1]');
          btn.classList.remove('text-[#F8EBDD]');
        }
      }
    }

    // FAQ Accordion Toggle
    function toggleFaq(id) {
      const content = document.getElementById('faq-content-' + id);
      const icon = document.getElementById('faq-icon-' + id);
      if (!content) return;
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        if (icon) icon.style.transform = 'rotate(180deg)';
      } else {
        content.classList.add('hidden');
        if (icon) icon.style.transform = 'rotate(0deg)';
      }
    }

    // Copy Link & Toast Feedback
    function copyKosanLink() {
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(window.location.href).then(() => {
          showToast("Tautan kos berhasil disalin ke clipboard!");
        }).catch(() => {
          fallbackCopyText(window.location.href);
        });
      } else {
        fallbackCopyText(window.location.href);
      }
    }

    function fallbackCopyText(text) {
      const textArea = document.createElement("textarea");
      textArea.value = text;
      textArea.style.position = "fixed";
      textArea.style.left = "-999999px";
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      try {
        document.execCommand('copy');
        showToast("Tautan kos berhasil disalin ke clipboard!");
      } catch (err) {
        showToast("Gagal menyalin tautan");
      }
      document.body.removeChild(textArea);
    }

    function showToast(msg) {
      const toast = document.getElementById('toast-message');
      const toastText = document.getElementById('toast-text');
      if (!toast || !toastText) return;
      toastText.textContent = msg;
      toast.classList.remove('hidden');
      toast.classList.add('flex');
      setTimeout(() => {
        toast.classList.add('hidden');
        toast.classList.remove('flex');
      }, 2800);
    }

    // Bookmark Toggle
    function toggleBookmark(slug) {
      const key = 'scl_fav_' + slug;
      const btn = document.getElementById('bookmark-btn');
      const label = document.getElementById('bookmark-label');
      const icon = btn ? btn.querySelector('i') : null;
      const isSaved = localStorage.getItem(key) === 'true';

      if (isSaved) {
        localStorage.removeItem(key);
        if (icon) icon.className = 'fa-regular fa-heart text-xs text-[#E60049]';
        if (label) label.textContent = 'Simpan';
        showToast("Dihapus dari daftar simpan");
      } else {
        localStorage.setItem(key, 'true');
        if (icon) icon.className = 'fa-solid fa-heart text-xs text-[#E60049]';
        if (label) label.textContent = 'Tersimpan';
        showToast("Kosan disimpan ke favorit!");
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      const slug = '{{ $kosan->slug }}';
      const isSaved = localStorage.getItem('scl_fav_' + slug) === 'true';
      const btn = document.getElementById('bookmark-btn');
      const label = document.getElementById('bookmark-label');
      const icon = btn ? btn.querySelector('i') : null;
      if (isSaved) {
        if (icon) icon.className = 'fa-solid fa-heart text-xs text-[#E60049]';
        if (label) label.textContent = 'Tersimpan';
      }
    });

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

    // Leaflet Interactive Vicinity Map Initialization
    document.addEventListener("DOMContentLoaded", function () {
      const mapElem = document.getElementById('vicinity-map');
      if (!mapElem || typeof L === 'undefined') return;

      const kosLat = {{ $baseCoords['lat'] }};
      const kosLng = {{ $baseCoords['lng'] }};

      const vicinityMap = L.map('vicinity-map', {
        center: [kosLat, kosLng],
        zoom: 15,
        scrollWheelZoom: false
      });

      // High-resolution tile layers (Zero Watermark, 100% Free & Fast)
      const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
      });

      const satLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics'
      });

      let currentLayer = streetLayer;
      streetLayer.addTo(vicinityMap);

      // Layer Switcher Buttons
      const btnStreet = document.getElementById('map-btn-street');
      const btnSat = document.getElementById('map-btn-sat');
      const btnRecenter = document.getElementById('map-btn-recenter');

      if (btnStreet && btnSat) {
        btnStreet.addEventListener('click', function () {
          if (currentLayer !== streetLayer) {
            vicinityMap.removeLayer(currentLayer);
            streetLayer.addTo(vicinityMap);
            currentLayer = streetLayer;
            btnStreet.className = 'px-2.5 py-1.5 rounded-lg bg-[#3B2314] text-white text-[11px] font-extrabold transition-all shadow-sm flex items-center gap-1.5';
            btnSat.className = 'px-2.5 py-1.5 rounded-lg text-[#5D483A] hover:text-[#3B2314] text-[11px] font-extrabold transition-all flex items-center gap-1.5';
          }
        });

        btnSat.addEventListener('click', function () {
          if (currentLayer !== satLayer) {
            vicinityMap.removeLayer(currentLayer);
            satLayer.addTo(vicinityMap);
            currentLayer = satLayer;
            btnSat.className = 'px-2.5 py-1.5 rounded-lg bg-[#3B2314] text-white text-[11px] font-extrabold transition-all shadow-sm flex items-center gap-1.5';
            btnStreet.className = 'px-2.5 py-1.5 rounded-lg text-[#5D483A] hover:text-[#3B2314] text-[11px] font-extrabold transition-all flex items-center gap-1.5';
          }
        });
      }

      if (btnRecenter) {
        btnRecenter.addEventListener('click', function () {
          vicinityMap.flyTo([kosLat, kosLng], 15, { duration: 0.8 });
          kosMarker.openPopup();
        });
      }

      // Visual Walking Radius (300m)
      L.circle([kosLat, kosLng], {
        color: '#E60049',
        fillColor: '#E60049',
        fillOpacity: 0.08,
        weight: 1.5,
        dashArray: '4, 4',
        radius: 300
      }).addTo(vicinityMap);

      // Custom Kos Marker
      const kosIcon = L.divIcon({
        className: 'custom-kos-marker',
        html: '<div class="marker-pin"><i class="fa-solid fa-house"></i></div><div class="marker-pulse"></div>',
        iconSize: [42, 42],
        iconAnchor: [21, 42]
      });

      const kosMarker = L.marker([kosLat, kosLng], { icon: kosIcon }).addTo(vicinityMap);
      kosMarker.bindPopup(`
        <div class="p-2 text-center" style="min-width: 180px;">
          <div class="inline-block px-2 py-0.5 bg-[#FFF0F3] text-[#E60049] text-[9px] font-extrabold rounded-md uppercase tracking-wider mb-1">
            Lokasi Kosan
          </div>
          <strong class="text-xs font-black text-[#3B2314] block leading-tight">{{ addslashes($kosan->title) }}</strong>
          <span class="text-[11px] text-[#7B6759] font-semibold block mt-0.5"><i class="fa-solid fa-location-dot text-[#E60049]"></i> {{ addslashes($displayLocationShort) }}</span>
          <div class="mt-2.5 pt-2 border-t border-[#F2E7DF] flex items-center justify-center gap-1.5">
            <a href="{{ $gmapsDirectUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-3 py-1 bg-[#3B2314] hover:bg-[#E60049] text-white text-[10px] font-bold rounded-lg shadow-sm transition-colors text-decoration-none">
              <span>Buka Google Maps</span>
              <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-[#F3A833]"></i>
            </a>
          </div>
        </div>
      `).openPopup();

      // Vicinity POI Markers
      const pois = [
        { title: 'Kampus / Pendidikan', icon: 'fa-graduation-cap', color: '#0284c7', offset: [0.0035, 0.0028], dist: '🚗 Akses Kendaraan' },
        { title: 'Minimarket & Belanja Harian', icon: 'fa-cart-shopping', color: '#10b981', offset: [-0.002, 0.0022], dist: '🚶 Jarak Dekat' },
        { title: 'Layanan Kesehatan & Apotek', icon: 'fa-heart-pulse', color: '#ef4444', offset: [0.0022, -0.0032], dist: '🚗 Layanan Medis' },
        { title: 'Sentra Kuliner & Laundry', icon: 'fa-mug-hot', color: '#f59e0b', offset: [-0.0022, -0.0018], dist: '🚶 Dekat Jangkauan' }
      ];

      pois.forEach(p => {
        const poiIcon = L.divIcon({
          className: 'custom-poi-marker',
          html: `<div style="background:${p.color};width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 3px 10px rgba(0,0,0,0.25);border:2px solid #fff;"><i class="fa-solid ${p.icon}" style="font-size:12px;"></i></div>`,
          iconSize: [30, 30],
          iconAnchor: [15, 15]
        });
        L.marker([kosLat + p.offset[0], kosLng + p.offset[1]], { icon: poiIcon })
          .addTo(vicinityMap)
          .bindPopup(`<div class="p-1.5 text-xs text-center"><strong class="font-bold text-[#3B2314] block">${p.title}</strong><span class="text-[10px] text-[#7B6759] font-semibold">${p.dist}</span></div>`);
      });
    });
  </script>

  <!-- 9. MOBILE FLOATING STICKY BAR (KHUSUS SMARTPHONE) -->
  <div class="fixed bottom-0 left-0 right-0 z-40 bg-[#FFF8F1]/95 backdrop-blur-md border-t border-[#E9DDD2] p-3 px-4 shadow-[0_-6px_25px_rgba(59,35,20,0.1)] block lg:hidden">
    <div class="max-w-md mx-auto flex items-center justify-between gap-2.5">
      <div>
        <span class="text-[10px] uppercase font-bold text-[#8E7B6D] tracking-wider block">Ketersediaan Unit</span>
        <div class="flex items-center gap-1.5">
          @if($kosan->available_rooms_count > 0)
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs sm:text-sm font-black text-[#3B2314]">Sisa {{ $kosan->available_rooms_count }} Kamar Siap Huni</span>
          @else
            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            <span class="text-xs sm:text-sm font-black text-rose-600">Semua Kamar Terisi</span>
          @endif
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ $waInquiryUrl }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center shrink-0 shadow-sm" title="Tanya Pengelola via WhatsApp">
          <i class="fa-brands fa-whatsapp text-lg"></i>
        </a>
        <button type="button" onclick="scrollToSection('kamar-tersedia')" class="shine px-4 py-2.5 bg-[#E60049] hover:bg-[#C90040] text-white font-black text-xs rounded-xl shadow-md active:scale-95 flex items-center gap-1.5 shrink-0">
          <span>Pilih Kamar</span>
          <i class="fa-solid fa-arrow-down text-[10px]"></i>
        </button>
      </div>
    </div>
  </div>
</body>
</html>