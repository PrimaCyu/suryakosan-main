<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News & Events - Sinar Citra Lestari</title>
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
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#E60049] via-[#F3A833] to-[#00A896] z-[100] transition-all duration-500 ease-out"></div>

  <!-- 2. NAVBAR -->
  @include('frontend.navbar')

  <!-- MAIN CONTENT CONTAINER -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @php
      $topArtikels = $artikels->take(3);
    @endphp

    <!-- 3. HERO CAROUSEL BANNER -->
    @if($topArtikels->count() > 0)
      <section class="reveal grid lg:grid-cols-[0.8fr_1.7fr] gap-5 items-stretch">
        <div class="rounded-[2rem] bg-[#3B2314] text-[#FFF8F1] p-7 sm:p-9 flex flex-col justify-between min-h-[420px]">
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#F3A833] text-[#3B2314] text-[10px] font-black uppercase tracking-[0.18em]">
              <i class="fa-solid fa-bolt"></i> News & Event
            </div>
            <h1 class="mt-6 text-3xl sm:text-4xl font-black leading-tight">
              Cerita, informasi,<br>
              dan kabar terbaru.
            </h1>
            <p class="mt-4 text-sm leading-7 text-[#FFF8F1]/70">
              Temukan informasi seputar hunian, tips anak kos, promo, serta aktivitas terbaru dari Sinar Citra Lestari.
            </p>
          </div>
          <div class="pt-8">
            <div class="flex items-center gap-3 text-xs text-[#FFF8F1]/60">
              <span class="w-9 h-9 rounded-xl bg-[#00A896] text-white grid place-items-center">
                <i class="fa-solid fa-newspaper"></i>
              </span>
              <span>Pilihan artikel untuk menemani pencarian kosmu.</span>
            </div>
          </div>
        </div>

        <div class="relative min-h-[420px] rounded-[2rem] overflow-hidden bg-[#EADFD5] shadow-[0_18px_50px_rgba(59,35,20,0.12)]">
          @foreach($topArtikels as $index => $item)
            @php
              $imgUrl = $item->image ? asset('storage/' . $item->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1400&q=80';
            @endphp
            <div class="hero-slide absolute inset-0 {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }} transition-opacity duration-700 ease-in-out">
              <img src="{{ $imgUrl }}" alt="{{ $item->title }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-r from-[#3B2314]/85 via-[#3B2314]/30 to-transparent"></div>
              <div class="absolute inset-x-0 bottom-0 p-6 sm:p-9">
                <div class="max-w-xl text-white">
                  <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="px-3 py-1 rounded-full bg-[#E60049] text-white text-[10px] font-black uppercase tracking-wider">Artikel</span>
                    @if($index === 0)
                      <span class="px-3 py-1 rounded-full bg-[#F3A833] text-[#3B2314] text-[10px] font-black uppercase tracking-wider">Terpopuler</span>
                    @endif
                  </div>
                  <h2 class="text-2xl sm:text-3xl font-black leading-tight line-clamp-2">
                    <a href="{{ route('news.detail', $item->slug) }}" class="hover:text-[#F3A833] transition-colors">{{ $item->title }}</a>
                  </h2>
                  <p class="mt-2 text-xs sm:text-sm text-white/75 line-clamp-2 leading-relaxed">
                    {{ Str::limit(strip_tags($item->deskripsi), 120) }}
                  </p>
                  <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a href="{{ route('news.detail', $item->slug) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#00A896] hover:bg-[#008f80] text-white text-xs font-black transition-all hover:-translate-y-0.5">
                      Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                    <span class="text-[11px] text-white/65">{{ $item->created_at ? $item->created_at->format('d M Y') : 'Terbaru' }}</span>
                    <span class="inline-flex items-center gap-1.5 text-[11px] text-white/75">
                      <i class="fa-solid fa-eye text-[#F3A833]"></i> {{ number_format($item->view ?? 0) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          @endforeach

          @if($topArtikels->count() > 1)
            <div id="slide-indicators" class="absolute top-6 right-6 flex items-center gap-2">
              @foreach($topArtikels as $index => $item)
                <button type="button" aria-label="Slide {{ $index + 1 }}" class="indicator-dot {{ $index === 0 ? 'w-8 bg-[#F3A833]' : 'w-2 bg-white/60' }} h-2 rounded-full transition-all cursor-pointer" onclick="setSlide({{ $index }})"></button>
              @endforeach
            </div>
          @endif
        </div>
      </section>
    @endif

    <!-- 4. SEARCH SECTION -->
    <section class="reveal mt-7 rounded-[2rem] border border-[#EADFD5] bg-white p-3 shadow-[0_12px_35px_rgba(59,35,20,0.07)]">
      <form action="{{ route('news.index') }}" method="GET" class="grid md:grid-cols-[1fr_auto] gap-3">
        <div class="relative">
          <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#00A896] text-sm"></i>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita, tips, artikel kos..." class="w-full pl-11 pr-4 py-3.5 bg-[#FFF8F1] border border-transparent rounded-2xl text-xs text-[#3B2314] placeholder-[#9C8576] focus:outline-none focus:border-[#F3A833] focus:ring-2 focus:ring-[#F3A833]/20 transition-all font-medium">
        </div>
        <div class="flex gap-2">
          <button type="submit" class="px-6 py-3.5 rounded-2xl bg-[#E60049] hover:bg-[#c90040] text-white text-xs font-black transition-all hover:-translate-y-0.5">
            Cari Artikel
          </button>
          @if(request('search'))
            <a href="{{ route('news.index') }}" class="px-4 py-3.5 rounded-2xl bg-[#F3A833]/15 text-[#3B2314] hover:bg-[#F3A833]/25 text-xs font-black flex items-center gap-2 transition-colors">
              <i class="fa-solid fa-rotate-left"></i> Reset
            </a>
          @endif
        </div>
      </form>
    </section>

    <!-- 5. LIST BERITA GRID -->
    <section class="mt-12 reveal">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-7">
        <div>
          <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#E60049]">Explore</span>
          <h2 class="mt-2 text-2xl sm:text-3xl font-black text-[#3B2314]">Semua Artikel & Informasi</h2>
        </div>
        <div class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#00A896]/10 text-[#007d70] text-[11px] font-bold">
          <i class="fa-solid fa-layer-group"></i>
          {{ $artikels->total() }} artikel
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5" id="news-grid">
        @forelse($artikels as $artikel)
          @php
            $imgUrl = $artikel->image ? asset('storage/' . $artikel->image) : 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80';
          @endphp

          <!-- ARTICLE CARD -->
          <article class="group bg-white rounded-[1.7rem] border border-[#EADFD5] overflow-hidden shadow-[0_8px_25px_rgba(59,35,20,0.06)] hover:shadow-[0_18px_40px_rgba(59,35,20,0.12)] hover:-translate-y-1 transition-all duration-300 flex flex-col">
            <a href="{{ route('news.detail', $artikel->slug) }}" class="relative h-52 overflow-hidden block bg-[#F3E9E0]">
              <img src="{{ $imgUrl }}" alt="{{ $artikel->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
              <div class="absolute inset-0 bg-gradient-to-t from-[#3B2314]/45 to-transparent opacity-70"></div>
              <span class="absolute top-4 left-4 px-3 py-1.5 rounded-lg bg-[#FFF8F1]/95 text-[#3B2314] text-[10px] font-black uppercase tracking-wider">
                Artikel
              </span>
              <span class="absolute bottom-4 right-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#3B2314]/80 text-white text-[10px] font-bold backdrop-blur-sm">
                <i class="fa-solid fa-eye text-[#F3A833]"></i> {{ number_format($artikel->view ?? 0) }}
              </span>
            </a>

            <div class="p-5 flex-1 flex flex-col">
              <div class="flex items-center gap-2 text-[10px] font-bold text-[#9C8576]">
                <i class="fa-regular fa-calendar text-[#E60049]"></i>
                {{ $artikel->created_at ? $artikel->created_at->format('d M Y') : '' }}
              </div>
              <h3 class="mt-3 font-black text-[#3B2314] text-base leading-snug line-clamp-2 group-hover:text-[#E60049] transition-colors" title="{{ $artikel->title }}">
                <a href="{{ route('news.detail', $artikel->slug) }}">{{ $artikel->title }}</a>
              </h3>
              <p class="text-[#806B5D] text-xs mt-2 line-clamp-3 leading-relaxed flex-1">
                {{ Str::limit(strip_tags($artikel->deskripsi), 120) }}
              </p>
              <div class="mt-5 pt-4 border-t border-[#F0E5DC]">
                <a href="{{ route('news.detail', $artikel->slug) }}" class="inline-flex items-center gap-2 text-[#00A896] hover:text-[#007d70] text-xs font-black transition-colors">
                  Baca Artikel <span class="w-6 h-6 rounded-full bg-[#00A896]/10 grid place-items-center group-hover:translate-x-1 transition-transform"><i class="fa-solid fa-arrow-right text-[9px]"></i></span>
                </a>
              </div>
            </div>
          </article>
        @empty
          <div class="col-span-full rounded-[2rem] bg-white border border-[#EADFD5] p-14 text-center shadow-sm">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-[#F3A833]/15 text-[#F3A833] grid place-items-center">
              <i class="fa-regular fa-newspaper text-2xl"></i>
            </div>
            <h4 class="mt-5 font-black text-[#3B2314] text-base">Belum Ada Artikel Berita</h4>
            <p class="text-[#8F796B] text-xs max-w-sm mx-auto mt-2">Belum ada berita atau artikel yang cocok dengan kriteria pencarian Anda.</p>
          </div>
        @endforelse
      </div>

      <!-- PAGINATION -->
      @if($artikels->hasPages())
        <div class="pt-8 flex justify-center reveal">
          {{ $artikels->links() }}
        </div>
      @endif
    </section>

  </main>

  <!-- FOOTER -->
  @include('frontend.footer')

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

    // Hero Carousel
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.indicator-dot');

    function setSlide(index) {
      if (!slides.length) return;

      slides.forEach((slide, idx) => {
        slide.classList.toggle('opacity-100', idx === index);
        slide.classList.toggle('opacity-0', idx !== index);
        slide.classList.toggle('pointer-events-none', idx !== index);
      });

      indicators.forEach((dot, idx) => {
        dot.className = idx === index
          ? 'indicator-dot w-8 h-2 bg-[#F3A833] rounded-full transition-all cursor-pointer'
          : 'indicator-dot w-2 h-2 bg-white/60 rounded-full transition-all cursor-pointer';
      });

      currentSlide = index;
    }

    if (slides.length > 1) {
      setInterval(() => {
        setSlide((currentSlide + 1) % slides.length);
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

    window.addEventListener('scroll', revealOnScroll, { passive: true });
    window.addEventListener('load', revealOnScroll);
  </script>
</body>
</html>