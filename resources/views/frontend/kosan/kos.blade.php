<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Semua Kos-kosan Pilihan - Sinar Citra Lestari</title>
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

  <!-- MAIN CONTENT CONTAINER -->
  <main class="w-full">

    <!-- 3. HERO & SEARCH FILTER SECTION -->
    <section class="bg-gradient-to-b from-cyan-100/60 via-slate-50 to-slate-50 pt-10 pb-12 px-4 sm:px-6 lg:px-8 border-b border-slate-100">
      <div class="max-w-4xl mx-auto text-center space-y-4 reveal">

        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-white/90 border border-cyan-200 text-cyan-800 backdrop-blur-md rounded-full text-xs font-bold shadow-sm">
          <i class="fa-solid fa-compass text-cyan-600"></i> Jelajahi Seluruh Pilihan Properti Kos
        </span>

        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
          Semua Kos–kosan Terbaik Untukmu
        </h1>

        <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto leading-relaxed">
          Temukan kamar kos impianmu dengan fasilitas lengkap, lokasi strategis, dan harga terjangkau.
        </p>

        <!-- FILTER SEARCH FORM -->
        <div class="pt-4">
          <form action="{{ route('kosan.index') }}" method="GET" class="bg-white p-4 sm:p-6 rounded-3xl shadow-xl border border-slate-100 space-y-4 text-left max-w-3xl mx-auto">

            <!-- Input Text Search + Button -->
            <div class="relative flex items-center bg-slate-50 rounded-2xl border border-slate-200 focus-within:border-cyan-500 focus-within:ring-2 focus-within:ring-cyan-200 transition-all">
              <i class="fa-solid fa-magnifying-glass text-cyan-600 pl-4 text-sm"></i>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kos, fasilitas, alamat..." class="w-full pl-3 pr-24 py-3.5 bg-transparent text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none font-medium">
              <button type="submit" class="absolute right-2 px-6 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-md active:scale-95">
                Cari
              </button>
            </div>

            <!-- Dropdown Filters (Wilayah) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div class="bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-200">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Pilih Wilayah</label>
                <select name="wilayah" onchange="this.form.submit()" class="w-full bg-transparent text-xs font-bold text-slate-800 focus:outline-none cursor-pointer mt-0.5">
                  <option value="semua">Semua Wilayah</option>
                  @php
                    $wilayahList = ['Badung', 'Denpasar', 'Gianyar', 'Buleleng', 'Tabanan', 'Karangasem', 'Klungkung', 'Bangli', 'Jembrana', 'Jakarta Selatan', 'Jakarta Pusat', 'Jakarta Barat', 'Yogyakarta', 'Bandung', 'Surabaya', 'Malang', 'Semarang'];
                  @endphp
                  @foreach($wilayahList as $w)
                    <option value="{{ $w }}" {{ request('wilayah') == $w ? 'selected' : '' }}>{{ $w }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Filter Status Indicator -->
              <div class="bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-200 flex items-center justify-between">
                <div>
                  <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Status Pencarian</label>
                  <span class="text-xs font-bold text-slate-800">
                    {{ request('search') || (request('wilayah') && request('wilayah') != 'semua') ? 'Filter Aktif' : 'Menampilkan Semua' }}
                  </span>
                </div>
                @if(request('search') || (request('wilayah') && request('wilayah') != 'semua'))
                  <a href="{{ route('kosan.index') }}" class="text-xs text-rose-600 font-bold hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset
                  </a>
                @endif
              </div>
            </div>

          </form>
        </div>

      </div>
    </section>

    <!-- 4. LISTING KOS GRID SECTION -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

      <div class="flex items-center justify-between reveal">
        <div>
          <h2 class="text-2xl font-extrabold text-slate-900">Daftar Properti Kos</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Total ditemukan {{ $kosanList->total() }} pilihan kos.</p>
        </div>
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

          <!-- CARD KOS -->
          <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col border border-slate-100 hover:-translate-y-1.5 group">
            <div class="relative h-52 overflow-hidden bg-slate-100">
              <img src="{{ $imgUrl }}" alt="{{ $kos->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

              <!-- Badges -->
              <div class="absolute top-3 left-3 flex flex-col items-start gap-1.5 z-10">
                <span class="bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1.5">
                  <i class="fa-solid fa-door-open text-xs text-emerald-400"></i>
                  @if(is_numeric($kos->tersedia))
                    Sisa {{ $kos->tersedia }} Kamar
                  @else
                    {{ $kos->tersedia ?? 'Tersedia' }}
                  @endif
                </span>
              </div>

              <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                <i class="fa-solid fa-eye text-cyan-400"></i> {{ number_format($kos->view ?? 0) }}
              </div>
            </div>

            <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
              <div>
                <h3 class="font-extrabold text-slate-900 text-lg truncate hover:text-cyan-600 transition-colors" title="{{ $kos->title }}">
                  <a href="{{ route('kosan.detail', $kos->slug) }}">{{ $kos->title }}</a>
                </h3>
                <p class="text-xs text-slate-500 flex items-center gap-1 mt-1 font-medium">
                  <i class="fa-solid fa-location-dot text-rose-500 text-[11px]"></i> {{ $kos->wilayah ?? 'Lokasi Terdaftar' }}
                </p>
                <p class="text-slate-500 text-xs mt-2 line-clamp-2 leading-relaxed">
                  {{ Str::limit(strip_tags($kos->description), 95) }}
                </p>

                <!-- Fasilitas (Max 4) -->
                <div class="flex flex-wrap items-center gap-1.5 pt-3">
                  @forelse(array_slice($fasKosanArr, 0, 3) as $fas)
                    @php $iconClass = $iconMap[$fas] ?? 'fa-check'; @endphp
                    <span class="bg-cyan-50/80 border border-cyan-100 text-cyan-800 text-[10px] font-bold px-2.5 py-0.5 rounded-lg flex items-center gap-1">
                      <i class="fa-solid {{ $iconClass }} text-cyan-600"></i> {{ $fas }}
                    </span>
                  @empty
                    <span class="text-slate-400 text-xs">Fasilitas Lengkap</span>
                  @endforelse
                  @if(count($fasKosanArr) > 3)
                    <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg font-bold border border-slate-200">+{{ count($fasKosanArr) - 3 }}</span>
                  @endif
                </div>
              </div>

              <!-- Footer Price & Action -->
              <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <div>
                  <span class="text-[10px] text-slate-400 block font-semibold uppercase tracking-wider">Mulai dari</span>
                  <div class="flex items-baseline gap-1">
                    @if($minPrice)
                      <span class="text-base font-extrabold text-slate-900">Rp {{ number_format($minPrice, 0, ',', '.') }}</span>
                      <span class="text-[10px] text-slate-400 font-medium">/bln</span>
                    @else
                      <span class="text-xs font-bold text-slate-500">Hubungi Admin</span>
                    @endif
                  </div>
                </div>

                <a href="{{ route('kosan.detail', $kos->slug) }}" class="px-5 py-2.5 bg-slate-900 hover:bg-cyan-600 text-white text-xs font-bold rounded-full shadow-md transition-all active:scale-95 flex items-center gap-1.5">
                  <span>Lihat Kamar</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full bg-white rounded-3xl p-12 text-center shadow-sm border border-slate-100 space-y-4">
            <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mx-auto text-2xl shadow-inner">
              <i class="fa-solid fa-house-circle-xmark"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900">Kos Tidak Ditemukan</h3>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
              Tidak ada data properti kos yang sesuai dengan kriteria pencarian Anda. Silakan coba kata kunci lain atau reset filter.
            </p>
            <div>
              <a href="{{ route('kosan.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-900 hover:bg-cyan-600 text-white text-xs font-bold rounded-full transition-all shadow-md">
                <i class="fa-solid fa-rotate-left text-xs"></i> Tampilkan Semua Kos
              </a>
            </div>
          </div>
        @endforelse
      </div>

      <!-- PAGINATION -->
      @if($kosanList->hasPages())
        <div class="pt-6 flex justify-center reveal">
          {{ $kosanList->links() }}
        </div>
      @endif

    </section>

  </main>

  <!-- FOOTER -->
  @include('frontend.footer')

  <!-- NEED HELP WIDGET -->
  @include('frontend.need-help')

  <!-- JAVASCRIPT SYSTEM LOGIC -->
  <script>
    // Loading Bar
    window.addEventListener('load', () => {
      const loader = document.getElementById('page-loader');
      if (loader) {
        loader.style.width = '100%';
        setTimeout(() => { loader.style.opacity = '0'; }, 300);
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
