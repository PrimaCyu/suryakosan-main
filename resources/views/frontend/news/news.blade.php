<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News & Events - NemuKOS</title>
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
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

    @php
      $topArtikels = $artikels->take(3);
    @endphp

    <!-- 3. HERO CAROUSEL BANNER -->
    @if($topArtikels->count() > 0)
      <section class="relative rounded-3xl overflow-hidden shadow-xl bg-slate-900 h-[380px] md:h-[440px] reveal">
        @foreach($topArtikels as $index => $item)
          @php
            $imgUrl = $item->image ? asset('storage/' . $item->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1400&q=80';
          @endphp
          <div class="hero-slide absolute inset-0 {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }} transition-opacity duration-700 ease-in-out z-10">
            <img src="{{ $imgUrl }}" alt="{{ $item->title }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/50 to-transparent flex items-end p-6 sm:p-12">
              <div class="relative z-20 max-w-2xl space-y-3 text-white">
                <div class="flex items-center gap-2">
                  <span class="inline-block px-3 py-1 bg-cyan-400 text-slate-900 rounded-full text-[11px] font-bold uppercase tracking-wider">
                    News & Event
                  </span>
                  @if($index === 0)
                    <span class="inline-block px-3 py-1 bg-rose-500 text-white rounded-full text-[11px] font-bold uppercase tracking-wider">
                      Terpopuler
                    </span>
                  @endif
                </div>
                <h1 class="text-2xl md:text-4xl font-extrabold leading-tight text-white line-clamp-2">
                  <a href="{{ route('news.detail', $item->slug) }}" class="hover:text-cyan-300 transition-colors">{{ $item->title }}</a>
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 line-clamp-2 max-w-xl font-normal leading-relaxed">
                  {{ Str::limit(strip_tags($item->deskripsi), 120) }}
                </p>
                <div class="flex flex-wrap items-center gap-4 pt-2">
                  <a href="{{ route('news.detail', $item->slug) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold rounded-full shadow-lg transition-all active:scale-95">
                    <span>Baca Selengkapnya</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                  </a>
                  <span class="text-xs text-slate-300 font-medium">{{ $item->created_at ? $item->created_at->format('d M Y') : 'Terbaru' }}</span>
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-800/80 backdrop-blur-sm text-slate-200 text-xs rounded-full font-medium border border-slate-700">
                    <i class="fa-solid fa-eye text-cyan-400 text-xs"></i> {{ number_format($item->view ?? 0) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        @endforeach

        @if($topArtikels->count() > 1)
          <!-- Indicator Dots -->
          <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2 pointer-events-auto" id="slide-indicators">
            @foreach($topArtikels as $index => $item)
              <button type="button" aria-label="Slide {{ $index + 1 }}" class="indicator-dot {{ $index === 0 ? 'w-8 bg-cyan-400' : 'w-2 bg-white/50' }} h-2 rounded-full transition-all cursor-pointer" onclick="setSlide({{ $index }})"></button>
            @endforeach
          </div>
        @endif
      </section>
    @endif

    <!-- 4. SEARCH SECTION -->
    <section class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 reveal">
      <form action="{{ route('news.index') }}" method="GET" class="relative w-full md:w-96 flex items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita, tips, artikel kos..." class="w-full pl-4 pr-24 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-cyan-600 transition-colors font-medium">
        <button type="submit" class="absolute right-1.5 px-4 py-1.5 bg-slate-900 hover:bg-cyan-600 text-white text-xs font-bold rounded-xl transition-all">Cari</button>
      </form>
      @if(request('search'))
        <a href="{{ route('news.index') }}" class="text-xs text-rose-600 font-bold hover:underline flex items-center gap-1">
          <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Pencarian
        </a>
      @endif
    </section>

    <!-- 5. LIST BERITA GRID -->
    <section class="space-y-6 reveal">
      <div>
        <h2 class="text-2xl font-extrabold text-slate-900">Semua Artikel & Informasi</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Dapatkan tips seputar hunian, kabar promo menarik, dan aktivitas komunitas.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="news-grid">
        @forelse($artikels as $artikel)
          @php
            $imgUrl = $artikel->image ? asset('storage/' . $artikel->image) : 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80';
          @endphp

          <!-- ARTICLE CARD -->
          <article class="bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between border border-slate-100 overflow-hidden group">
            <div class="p-3">
              <div class="relative rounded-2xl overflow-hidden h-48 bg-slate-100">
                <img src="{{ $imgUrl }}" alt="{{ $artikel->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-3 left-3 flex gap-1.5 flex-wrap">
                  <span class="bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">Artikel</span>
                </div>
              </div>
            </div>
            <div class="p-5 pt-1 space-y-3 flex-1 flex flex-col justify-between">
              <div>
                <h3 class="font-extrabold text-slate-900 text-base leading-snug line-clamp-2 hover:text-cyan-600 transition-colors" title="{{ $artikel->title }}">
                  <a href="{{ route('news.detail', $artikel->slug) }}">{{ $artikel->title }}</a>
                </h3>
                <p class="text-slate-500 text-xs mt-2 line-clamp-2 leading-relaxed">
                  {{ Str::limit(strip_tags($artikel->deskripsi), 100) }}
                </p>
              </div>
              <div class="flex items-center justify-between pt-4 text-[11px] font-medium border-t border-slate-100">
                <a href="{{ route('news.detail', $artikel->slug) }}" class="text-cyan-600 hover:text-cyan-700 flex items-center gap-1 font-bold">
                  <span>Baca Artikel</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
                <div class="flex items-center gap-2">
                  <span class="text-slate-400">{{ $artikel->created_at ? $artikel->created_at->format('d M Y') : '' }}</span>
                  <span class="inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded-md text-slate-600 font-semibold text-[10px]">
                    <i class="fa-solid fa-eye text-cyan-600"></i> {{ number_format($artikel->view ?? 0) }}
                  </span>
                </div>
              </div>
            </div>
          </article>
        @empty
          <div class="col-span-full bg-white rounded-3xl p-12 text-center shadow-sm border border-slate-100 space-y-3">
            <i class="fa-regular fa-newspaper text-5xl text-slate-300"></i>
            <h4 class="font-bold text-slate-800 text-base">Belum Ada Artikel Berita</h4>
            <p class="text-slate-500 text-xs max-w-sm mx-auto">Belum ada berita atau artikel yang cocok dengan kriteria pencarian Anda.</p>
          </div>
        @endforelse
      </div>

      <!-- PAGINATION -->
      @if($artikels->hasPages())
        <div class="pt-6 flex justify-center reveal">
          {{ $artikels->links() }}
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

    // Hero Carousel Auto Slide
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.indicator-dot');

    function setSlide(index) {
      if (!slides.length) return;
      slides.forEach((slide, idx) => {
        if (idx === index) {
          slide.classList.remove('opacity-0', 'pointer-events-none');
          slide.classList.add('opacity-100');
        } else {
          slide.classList.remove('opacity-100');
          slide.classList.add('opacity-0', 'pointer-events-none');
        }
      });

      indicators.forEach((dot, idx) => {
        if (idx === index) {
          dot.className = 'indicator-dot w-8 h-2 bg-cyan-400 rounded-full transition-all cursor-pointer';
        } else {
          dot.className = 'indicator-dot w-2 h-2 bg-white/50 rounded-full transition-all cursor-pointer';
        }
      });
      currentSlide = index;
    }

    if (slides.length > 1) {
      setInterval(() => {
        let nextSlide = (currentSlide + 1) % slides.length;
        setSlide(nextSlide);
      }, 5000);
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
