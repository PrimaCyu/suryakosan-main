<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Semua Kos-kosan untuk Kamu - NemuKOS</title>
  <link rel="stylesheet" href="{{ asset('build/assets/app-D-OnOQL0.css') }}">
  <link rel="icon" href="{{ asset('logo.png') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    .hero-bg-cyan {
      background: linear-gradient(180deg, rgba(207, 250, 254, 0.6) 0%, rgba(255, 255, 255, 1) 100%);
    }
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
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

  <!-- 1. BAR ANIMASI LOADING HALAMAN -->
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-cyan-500 z-[100] transition-all duration-500 ease-out"></div>

  <!-- 2. NAVBAR COMPONENT -->
  @include('frontend.navbar')

  <!-- MAIN CONTENT CONTAINER -->
  <main class="w-full">

    <!-- 3. HERO SECTION & FILTER SEARCH CARD -->
    <section class="hero-bg-cyan pt-10 pb-16 px-4 sm:px-6 lg:px-8">
      <div class="max-w-4xl mx-auto text-center space-y-4 reveal">

        <!-- Small Pill Badge -->
        <span class="inline-block px-4 py-1.5 bg-white/80 border border-teal-200 text-teal-800 backdrop-blur-md rounded-full text-xs font-semibold shadow-sm">
          Jelajahi Semua Pilihan Kos di Bali
        </span>

        <!-- Main Title -->
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
          Semua Kos–kosan untuk Kamu
        </h1>

        <!-- Subtitle -->
        <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto leading-relaxed">
          Temukan kamar kos impianmu di seluruh Bali. Bandingkan harga, fasilitas, dan lokasi dalam satu halaman.
        </p>

        <!-- FILTER SEARCH FORM CONTAINER -->
        <div class="pt-6">
          <form action="{{ route('kosan.index') }}" method="GET" class="bg-white p-4 sm:p-6 rounded-[2.5rem] shadow-xl border border-slate-100/80 space-y-4 text-left max-w-3xl mx-auto transition-all hover:shadow-2xl">

            <!-- Input Text Search + Button -->
            <div class="relative flex items-center bg-slate-50/80 rounded-2xl border border-slate-200/80 focus-within:border-cyan-500 focus-within:ring-2 focus-within:ring-cyan-200 transition-all">
              <i class="fa-solid fa-magnifying-glass text-slate-400 pl-4 text-sm"></i>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kos atau lokasi..." class="w-full pl-3 pr-24 py-3.5 bg-transparent text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
              <button type="submit" class="absolute right-2 px-6 py-2 bg-black text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition-all shadow-md active:scale-95">
                Cari
              </button>
            </div>

            <!-- Dropdown Filters (Wilayah) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <!-- Dropdown Wilayah -->
              <div class="bg-slate-50/80 px-4 py-2.5 rounded-2xl border border-slate-200/80">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Wilayah</label>
                <select name="wilayah" onchange="this.form.submit()" class="w-full bg-transparent text-xs font-bold text-slate-800 focus:outline-none cursor-pointer mt-0.5">
                  <option value="semua">Semua Wilayah</option>
                  @php
                    $wilayahList = ['Badung', 'Denpasar', 'Gianyar', 'Buleleng', 'Tabanan', 'Karangasem', 'Klungkung', 'Bangli', 'Jembrana'];
                  @endphp
                  @foreach($wilayahList as $w)
                    <option value="{{ $w }}" {{ request('wilayah') == $w ? 'selected' : '' }}>{{ $w }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Clear Filter Link -->
              <div class="bg-slate-50/80 px-4 py-2.5 rounded-2xl border border-slate-200/80 flex items-center justify-between">
                <div>
                  <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Status Filter</label>
                  <span class="text-xs font-bold text-slate-800">{{ request('search') || (request('wilayah') && request('wilayah') != 'semua') ? 'Filter Aktif' : 'Semua Data' }}</span>
                </div>
                @if(request('search') || (request('wilayah') && request('wilayah') != 'semua'))
                  <a href="{{ route('kosan.index') }}" class="text-xs text-rose-600 font-bold hover:underline">Reset Filter</a>
                @endif
              </div>
            </div>

          </form>
        </div>

      </div>
    </section>

    <!-- 4. LISTING KOS GRID SECTION -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

      <!-- Section Header -->
      <div class="reveal">
        <h2 class="text-2xl font-bold text-slate-900">Cari Kos Berdasarkan Wilayahmu</h2>
        <p class="text-xs text-slate-500 mt-1">Menampilkan pilihan kos-kosan terbaik dengan unit kamar yang tersedia.</p>
      </div>

      @php
        $iconMap = [
            'AC' => 'fa-snowflake',
            'Kamar Mandi Dalam' => 'fa-bath',
            'Wi-Fi / Internet' => 'fa-wifi',
            'Parkir Mobil' => 'fa-car',
            'Parkir Motor' => 'fa-motorcycle',
            'Dapur Bersama' => 'fa-kitchen-set',
            'CCTV 24 Jam' => 'fa-video',
            'Keamanan / Satpam' => 'fa-user-shield',
            'Kasur' => 'fa-bed',
            'Lemari' => 'fa-door-closed',
        ];
      @endphp

      <!-- Kos Grid Container -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 reveal">
        @forelse($kosanList as $kos)
          @php
            $firstImage = $kos->productImageKosan->first();
            $imgUrl = $firstImage ? asset('storage/' . $firstImage->image) : 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=600&q=80';
            $fasKosanArr = array_filter(array_map('trim', explode(',', $kos->fasilitas ?? '')));

            // Hitung harga terendah dari unit kamar
            $minPrice = null;
            foreach ($kos->productKamarKosan as $kamarItem) {
                foreach ($kamarItem->priceKamar as $pr) {
                    if (strtolower($pr->kategori) === 'bulan' && ($minPrice === null || $pr->price < $minPrice)) {
                        $minPrice = $pr->price;
                    }
                }
            }
          @endphp

          <!-- CARD KOS DYNAMIC -->
          <div class="bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 flex flex-col border border-slate-100 hover:-translate-y-1">
            <div class="relative h-52 overflow-hidden bg-slate-100">
              <img src="{{ $imgUrl }}" alt="{{ $kos->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

              <!-- Badges (Sisa Kamar Merah dengan Ikon) -->
              <div class="absolute top-3 left-3 flex flex-col items-start gap-1.5 z-10">
                <span class="bg-rose-500/90 backdrop-blur-md text-white text-[10px] font-extrabold px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5">
                  <i class="fa-solid fa-door-open text-xs"></i>
                  @if(is_numeric($kos->tersedia))
                    Sisa {{ $kos->tersedia }} Kamar
                  @else
                    {{ $kos->tersedia ?? 'Sisa Kamar' }}
                  @endif
                </span>
              </div>

              <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                <i class="fa-regular fa-eye text-cyan-400"></i> {{ number_format($kos->view ?? 0) }} dilihat
              </div>
            </div>

            <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
              <div>
                <h3 class="font-extrabold text-slate-900 text-lg truncate" title="{{ $kos->title }}">{{ $kos->title }}</h3>
                <p class="text-xs text-slate-400 flex items-center gap-1 mt-1">
                  <i class="fa-solid fa-location-dot text-rose-500 text-[10px]"></i> {{ $kos->wilayah ?? 'Bali' }}
                </p>
                <p class="text-slate-500 text-xs mt-2 line-clamp-2 leading-relaxed">
                  {{ Str::limit(strip_tags($kos->description), 100) }}
                </p>

                <!-- Fasilitas (Max 4) -->
                <div class="flex flex-wrap items-center gap-1.5 pt-3">
                  @forelse(array_slice($fasKosanArr, 0, 4) as $fas)
                    @php $iconClass = $iconMap[$fas] ?? 'fa-check'; @endphp
                    <span class="bg-cyan-50 border border-cyan-100 text-cyan-700 text-[10px] font-medium px-2 py-0.5 rounded-md flex items-center gap-1">
                      <i class="fa-solid {{ $iconClass }}"></i> {{ $fas }}
                    </span>
                  @empty
                    <span class="text-slate-400 text-xs">Fasilitas Lengkap</span>
                  @endforelse
                  @if(count($fasKosanArr) > 4)
                    <span class="text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded-md border border-slate-200">+{{ count($fasKosanArr) - 4 }}</span>
                  @endif
                </div>
              </div>

              <!-- Footer Price & Action -->
              <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <div>
                  <span class="text-[10px] text-slate-400 block font-medium">Mulai dari</span>
                  <div class="flex items-baseline gap-1">
                    @if($minPrice)
                      <span class="text-base font-extrabold text-slate-900">Rp {{ number_format($minPrice, 0, ',', '.') }}</span>
                      <span class="text-[10px] text-slate-400 font-medium">/bulan</span>
                    @else
                      <span class="text-xs font-semibold text-slate-500">Hubungi Pemilik</span>
                    @endif
                  </div>
                </div>

                <a href="{{ route('kosan.detail', $kos->slug) }}" class="px-5 py-2.5 bg-black text-white text-xs font-bold rounded-full shadow-md hover:bg-slate-800 transition-all active:scale-95">
                  Lihat Detail
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full text-center py-16 space-y-3">
            <i class="fa-solid fa-house-circle-xmark text-5xl text-slate-300"></i>
            <p class="text-slate-500 text-sm font-medium">Data kos-kosan belum tersedia untuk kriteria ini.</p>
          </div>
        @endforelse
      </div>

    </section>

  </main>

  <!-- 6. FOOTER COMPONENT -->
  @include('frontend.footer')

  <!-- 7. NEED HELP COMPONENT -->
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
