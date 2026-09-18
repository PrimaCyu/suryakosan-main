<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Semua kosan Pilihan - Sinar Citra Lestari</title>
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
  </style>
</head>
<body class="bg-[#fffaf7] text-[#3B2314] font-sans antialiased overflow-x-hidden">

  <!-- 1. BAR LOADING -->
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#F3A833] via-[#E60049] to-[#00A896] z-[100] transition-all duration-500 ease-out"></div>

  <!-- 2. NAVBAR -->
  @include('frontend.navbar')

  <!-- MAIN CONTENT CONTAINER -->
  <main class="w-full">

    <!-- 3. HERO & SEARCH FILTER SECTION -->
    <section class="relative px-4 sm:px-6 lg:px-8 pt-8 pb-14 overflow-hidden">
      <div class="absolute -top-24 -left-20 w-80 h-80 rounded-full bg-[#F3A833]/15 blur-3xl pointer-events-none"></div>
      <div class="absolute top-24 -right-24 w-96 h-96 rounded-full bg-[#00A896]/10 blur-3xl pointer-events-none"></div>
      <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
          <div class="lg:col-span-7 relative min-h-[420px] rounded-[2.5rem] overflow-hidden bg-[#3B2314] shadow-2xl reveal">
            <div class="absolute inset-0 bg-gradient-to-br from-[#3B2314] via-[#3B2314]/90 to-[#6B4630]/70"></div>
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full border-[30px] border-[#F3A833]/15"></div>
            <div class="relative h-full p-7 sm:p-10 flex flex-col justify-between">
              <div>
                <span class="inline-flex items-center gap-2 bg-[#F3A833] text-[#3B2314] px-4 py-2 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                  <i class="fa-solid fa-compass"></i> Jelajahi kosan
                </span>
                <h1 class="mt-7 text-4xl sm:text-5xl font-extrabold tracking-tight leading-[1.05] text-white max-w-2xl">
                  Semua kosan<br><span class="text-[#F3A833]">Terbaik Untukmu.</span>
                </h1>
                <p class="mt-5 text-white/70 text-xs sm:text-sm max-w-lg leading-relaxed">
                  Temukan kamar kos impianmu dengan fasilitas lengkap, lokasi strategis, dan harga terjangkau.
                </p>
              </div>
            </div>
          </div>

          <div class="lg:col-span-5 rounded-[2.5rem] bg-[#FFFCF8] border border-[#F3A833]/30 shadow-xl p-6 sm:p-8 reveal">
            <div class="flex items-start justify-between gap-4 mb-6">
              <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#3B2314] mt-1">Atur Pencarianmu</h2>
                <p class="text-[#6B4630]/70 text-xs mt-2 leading-relaxed">Gunakan kata kunci, wilayah, dan range harga untuk mempersempit pilihan.</p>
              </div>
            </div>

            <form action="{{ route('kosan.index') }}" method="GET" id="search-filter-form" class="space-y-4">
              <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-[#3B2314]/60 mb-2">Kata Kunci</label>
                <div class="flex items-center rounded-2xl bg-[#FFF8F1] border border-[#F3A833]/40 focus-within:border-[#00A896] focus-within:ring-2 focus-within:ring-[#00A896]/15 transition-all overflow-hidden">
                  <div class="w-11 h-11 flex items-center justify-center text-[#00A896] shrink-0"><i class="fa-solid fa-magnifying-glass text-sm"></i></div>
                  <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama kos, fasilitas, alamat..." class="w-full py-3 pr-3 bg-transparent text-xs text-[#3B2314] placeholder-[#3B2314]/45 focus:outline-none font-medium">
                </div>
              </div>

              <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-[#3B2314]/60 mb-2">Wilayah</label>
                <div class="relative">
                  <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-[#E60049] text-sm pointer-events-none"></i>
                  <select name="wilayah" class="w-full appearance-none bg-[#FFF8F1] border border-[#F3A833]/40 rounded-2xl pl-11 pr-10 py-3.5 text-xs font-bold text-[#3B2314] focus:outline-none focus:border-[#00A896] cursor-pointer">
                    <option value="semua">Semua Wilayah</option>
                    @php $wilayahList = ['Badung','Denpasar','Gianyar','Buleleng','Tabanan','Karangasem','Klungkung','Bangli','Jembrana','Jakarta Selatan','Jakarta Pusat','Jakarta Barat','Yogyakarta','Bandung','Surabaya','Malang','Semarang']; @endphp
                    @foreach($wilayahList as $w)
                      <option value="{{ $w }}" {{ request('wilayah') == $w ? 'selected' : '' }}>{{ $w }}</option>
                    @endforeach
                  </select>
                  <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[#6B4630]/50 text-[10px] pointer-events-none"></i>
                </div>
              </div>

              <!-- Status Pencarian sekarang menjadi 5 pilihan range harga -->
              <div>
                <div class="flex items-center justify-between mb-2">
                  <label class="block text-[10px] font-extrabold uppercase tracking-wider text-[#3B2314]/60">Status Pencarian · Range Harga</label>
                  @if(request('price_range')) <span class="text-[9px] font-extrabold text-[#E60049] uppercase">Filter Aktif</span> @endif
                </div>
                @php
                  $priceRanges = [
                    'under-500' => ['label'=>'< Rp 500 Ribu','min'=>'','max'=>'500000'],
                    '500-1000' => ['label'=>'Rp 500 Rb – 1 Jt','min'=>'500000','max'=>'1000000'],
                    '1000-1500' => ['label'=>'Rp 1 – 1,5 Jt','min'=>'1000000','max'=>'1500000'],
                    '1500-2500' => ['label'=>'Rp 1,5 – 2,5 Jt','min'=>'1500000','max'=>'2500000'],
                    'over-2500' => ['label'=>'> Rp 2,5 Jt','min'=>'2500000','max'=>''],
                  ];
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                  @foreach($priceRanges as $key => $range)
                    <label class="cursor-pointer group">
                      <input type="radio" name="price_range" value="{{ $key }}" class="sr-only price-range-option" {{ request('price_range') == $key ? 'checked' : '' }}>
                      <span class="block px-3 py-2.5 rounded-xl border text-[10px] font-extrabold text-center transition-all {{ request('price_range') == $key ? 'bg-[#3B2314] text-white border-[#3B2314] shadow-md' : 'bg-[#FFF8F1] text-[#6B4630] border-[#F3A833]/30 hover:border-[#E60049] hover:text-[#E60049]' }}">
                        {{ $range['label'] }}
                      </span>
                    </label>
                  @endforeach
                </div>
                <input type="hidden" name="price_min" id="price_min" value="{{ request('price_min') }}">
                <input type="hidden" name="price_max" id="price_max" value="{{ request('price_max') }}">
              </div>

              <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="rounded-2xl bg-[#00A896]/8 border border-[#00A896]/15 px-4 py-3">
                  <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#00A896]"></span>
                    <div>
                      <label class="block text-[9px] font-extrabold uppercase tracking-wider text-[#006F65]">Status Pencarian</label>
                      <span class="text-[10px] font-bold text-[#3B2314]">
                        {{ request('search') || (request('wilayah') && request('wilayah') != 'semua') || request('price_range') ? 'Filter Aktif' : 'Menampilkan Semua' }}
                      </span>
                    </div>
                  </div>
                </div>
                <div class="flex gap-2">
                  @if(request('search') || (request('wilayah') && request('wilayah') != 'semua') || request('price_range'))
                    <a href="{{ route('kosan.index') }}" class="w-11 h-11 rounded-xl border border-[#E60049]/20 text-[#E60049] hover:bg-[#E60049] hover:text-white flex items-center justify-center transition-all" title="Reset filter"><i class="fa-solid fa-rotate-left text-xs"></i></a>
                  @endif
                  <button type="submit" class="px-5 h-11 rounded-xl bg-[#E60049] hover:bg-[#3B2314] text-white text-xs font-extrabold shadow-md hover:shadow-lg transition-all active:scale-95"><i class="fa-solid fa-magnifying-glass mr-1"></i> Cari</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. LISTING KOS GRID SECTION -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

      <div class="flex items-center justify-between reveal">
        <div>
          <h2 class="text-2xl font-extrabold text-[#3B2314]">Daftar Kos</h2>
          <p class="text-xs sm:text-sm text-[#6B4630] mt-0.5">Total ditemukan {{ $kosanList->total() }} pilihan kos.</p>
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
          <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-[0_20px_50px_rgba(59,35,20,0.12)] transition-all duration-300 flex flex-col border border-[#E9DDD2] hover:border-[#F3A833] hover:-translate-y-2 group">
            <div class="relative h-56 overflow-hidden bg-[#3B2314]/5">
              <img src="{{ $imgUrl }}" alt="{{ $kos->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">

              <!-- Badges -->
              <div class="absolute top-3.5 left-3.5 flex flex-col items-start gap-1.5 z-10">
                <span class="bg-[#3B2314]/85 backdrop-blur-md text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-2 shadow-lg border border-white/10">
                  <span class="w-2 h-2 rounded-full {{ is_numeric($kos->tersedia) && $kos->tersedia > 0 ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                  <span>
                    @if(is_numeric($kos->tersedia))
                      {{ $kos->tersedia > 0 ? 'Sisa ' . $kos->tersedia . ' Kamar Siap Huni' : 'Kamar Penuh' }}
                    @else
                      {{ $kos->tersedia ?? 'Tersedia' }}
                    @endif
                  </span>
                </span>
              </div>

              <div class="absolute bottom-3 right-3 bg-[#3B2314]/75 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                <i class="fa-solid fa-eye text-cyan-400"></i> {{ number_format($kos->view ?? 0) }}
              </div>
            </div>

            <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
              <div>
                <h3 class="font-extrabold text-[#3B2314] text-lg truncate hover:text-[#00A896] transition-colors" title="{{ $kos->title }}">
                  <a href="{{ route('kosan.detail', $kos->slug) }}">{{ $kos->title }}</a>
                </h3>
                <p class="text-xs text-[#6B4630] flex items-center gap-1 mt-1 font-medium">
                  <i class="fa-solid fa-location-dot text-[#E60049] text-[11px]"></i> {{ $kos->wilayah ?? 'Lokasi Terdaftar' }}
                </p>
                <p class="text-[#6B4630] text-xs mt-2 line-clamp-2 leading-relaxed">
                  {{ Str::limit(strip_tags($kos->description), 95) }}
                </p>

                <!-- Fasilitas (Max 4) -->
                <div class="flex flex-wrap items-center gap-1.5 pt-3">
                  @forelse(array_slice($fasKosanArr, 0, 3) as $fas)
                    @php $iconClass = $iconMap[$fas] ?? 'fa-check'; @endphp
                    <span class="bg-[#00A896]/10 border border-[#00A896]/20 text-[#006F65] text-[10px] font-bold px-2.5 py-0.5 rounded-lg flex items-center gap-1">
                      <i class="fa-solid {{ $iconClass }} text-[#00A896]"></i> {{ $fas }}
                    </span>
                  @empty
                    <span class="text-[#6B4630]/60 text-xs">Fasilitas Lengkap</span>
                  @endforelse
                  @if(count($fasKosanArr) > 3)
                    <span class="text-[10px] text-[#6B4630] bg-[#3B2314]/5 px-2 py-0.5 rounded-lg font-bold border border-[#3B2314]/10">+{{ count($fasKosanArr) - 3 }}</span>
                  @endif
                </div>
              </div>

              <!-- Footer Price & Action -->
              <div class="flex items-center justify-between pt-4 border-t border-[#F3A833]/20">
                <div>
                  <span class="text-[10px] text-[#6B4630]/60 block font-semibold uppercase tracking-wider">Mulai dari</span>
                  <div class="flex items-baseline gap-1">
                    @if($minPrice)
                      <span class="text-base font-extrabold text-[#3B2314]">Rp {{ number_format($minPrice, 0, ',', '.') }}</span>
                      <span class="text-[10px] text-[#6B4630]/60 font-medium">/bln</span>
                    @else
                      <span class="text-xs font-bold text-[#6B4630]">Hubungi Admin</span>
                    @endif
                  </div>
                </div>

                <a href="{{ route('kosan.detail', $kos->slug) }}" class="px-5 py-2.5 bg-[#3B2314] hover:bg-[#E60049] text-white text-xs font-bold rounded-full shadow-md transition-all active:scale-95 flex items-center gap-1.5">
                  <span>Lihat Kamar</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full bg-white rounded-3xl p-12 text-center shadow-sm border border-[#F3A833]/20 space-y-4">
            <div class="w-16 h-16 rounded-full bg-[#E60049]/10 text-[#E60049] flex items-center justify-center mx-auto text-2xl shadow-inner">
              <i class="fa-solid fa-house-circle-xmark"></i>
            </div>
            <h3 class="text-lg font-extrabold text-[#3B2314]">Kos Tidak Ditemukan</h3>
            <p class="text-xs sm:text-sm text-[#6B4630] max-w-md mx-auto">
              Tidak ada data properti kos yang sesuai dengan kriteria pencarian Anda. Silakan coba kata kunci lain atau reset filter.
            </p>
            <div>
              <a href="{{ route('kosan.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#3B2314] hover:bg-[#E60049] text-white text-xs font-bold rounded-full transition-all shadow-md">
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

  <!-- JAVASCRIPT SYSTEM LOGIC -->
  <script>
    // Sinkronisasi 5 range harga ke price_min dan price_max
    const priceRanges = {
      'under-500': { min: '', max: '500000' },
      '500-1000': { min: '500000', max: '1000000' },
      '1000-1500': { min: '1000000', max: '1500000' },
      '1500-2500': { min: '1500000', max: '2500000' },
      'over-2500': { min: '2500000', max: '' }
    };

    document.querySelectorAll('.price-range-option').forEach(option => {
      option.addEventListener('change', function () {
        const range = priceRanges[this.value];
        if (!range) return;
        document.getElementById('price_min').value = range.min;
        document.getElementById('price_max').value = range.max;
        document.querySelectorAll('.price-range-option + span').forEach(span => {
          span.className = 'block px-3 py-2.5 rounded-xl border text-[10px] font-extrabold text-center transition-all bg-[#FFF8F1] text-[#6B4630] border-[#F3A833]/30 hover:border-[#E60049] hover:text-[#E60049]';
        });
        this.nextElementSibling.className = 'block px-3 py-2.5 rounded-xl border text-[10px] font-extrabold text-center transition-all bg-[#3B2314] text-white border-[#3B2314] shadow-md';
      });
    });

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