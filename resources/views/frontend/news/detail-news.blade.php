<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $artikel->title }} - Sinar Citra Lestari</title>
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
    $imgUrl = $artikel->image ? asset('storage/' . $artikel->image) : 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80';
  @endphp

  <!-- BREADCRUMB -->
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 reveal">
      <a href="{{ url('/') }}" class="hover:text-cyan-600 transition-colors">Beranda</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <a href="{{ route('news.index') }}" class="hover:text-cyan-600 transition-colors">News & Events</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <span class="text-slate-800 font-bold truncate max-w-xs sm:max-w-md">{{ $artikel->title }}</span>
    </nav>
  </div>

  <!-- MAIN CONTENT WRAPPER -->
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

      <!-- KOLOM UTAMA (ARTIKEL DETAIL) -->
      <article class="lg:col-span-8 space-y-6 reveal">

        <!-- Badge Kategori -->
        <div>
          <span class="inline-block px-3.5 py-1 bg-cyan-100 text-cyan-800 rounded-full text-[11px] font-bold uppercase tracking-wider">
            News & Event
          </span>
        </div>

        <!-- Judul Berita -->
        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
          {{ $artikel->title }}
        </h1>

        <!-- Author, Views, Date, & Social Share Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 py-2 border-y border-slate-200/80">
          <!-- Author Info & Viewer Stats -->
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-cyan-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">
              <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
              <h4 class="text-xs font-bold text-slate-900">Redaksi Sinar Citra Lestari</h4>
              <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5 font-medium">
                <span>{{ $artikel->created_at ? $artikel->created_at->translatedFormat('d F Y') : 'Terbaru' }}</span>
                <span>•</span>
                <span class="flex items-center gap-1">
                  <i class="fa-solid fa-eye text-cyan-600"></i> {{ number_format($artikel->view ?? 0) }} views
                </span>
              </div>
            </div>
          </div>

          <!-- Social Share Buttons -->
          <div class="flex items-center gap-2">
            <button onclick="shareToWhatsApp()" class="w-8 h-8 rounded-full bg-emerald-50 hover:bg-emerald-500 hover:text-white text-emerald-600 transition-all flex items-center justify-center text-xs shadow-sm hover:scale-110" title="Bagikan ke WhatsApp">
              <i class="fa-brands fa-whatsapp text-sm"></i>
            </button>
            <button onclick="shareToFacebook()" class="w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-600 transition-all flex items-center justify-center text-xs shadow-sm hover:scale-110" title="Bagikan ke Facebook">
              <i class="fa-brands fa-facebook-f text-xs"></i>
            </button>
            <button onclick="copyPageUrl()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-600 transition-all flex items-center justify-center text-xs shadow-sm hover:scale-110 relative" title="Salin Tautan">
              <i class="fa-solid fa-link text-xs"></i>
              <span id="copy-toast" class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-0.5 rounded opacity-0 transition-opacity pointer-events-none whitespace-nowrap font-bold">Tersalin!</span>
            </button>
          </div>
        </div>

        <!-- Featured Image Detail -->
        <div class="rounded-3xl overflow-hidden shadow-lg border border-slate-100 h-[280px] sm:h-[420px] bg-slate-100">
          <img src="{{ $imgUrl }}" alt="{{ $artikel->title }}" loading="lazy" class="w-full h-full object-cover">
        </div>

        <!-- Isi Konten Artikel -->
        <div class="space-y-4 text-sm sm:text-base text-slate-700 leading-relaxed pt-2 bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
          {!! nl2br(e($artikel->deskripsi ?? 'Konten artikel belum ditambahkan.')) !!}
        </div>

      </article>

      <!-- SIDEBAR DESKTOP SEARCH & BERITA LAIN -->
      <aside class="hidden lg:block lg:col-span-4 sticky top-28 space-y-6 reveal">
        <div class="bg-white p-6 rounded-3xl space-y-5 border border-slate-100 shadow-sm">
          <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
            <i class="fa-solid fa-magnifying-glass text-cyan-600"></i> Cari Artikel Lain
          </h3>

          <form action="{{ route('news.index') }}" method="GET" class="relative">
            <input type="text" name="search" placeholder="Ketik kata kunci..." class="w-full px-4 py-2.5 bg-slate-50 text-xs rounded-2xl border border-slate-200 focus:outline-none focus:border-cyan-600 font-medium">
          </form>

          <div class="space-y-4 pt-2">
            <span class="text-xs font-extrabold text-slate-900 block">Artikel Terkait Lainnya</span>
            @forelse($beritaLainnya as $itemLain)
              @php
                $imgLain = $itemLain->image ? asset('storage/' . $itemLain->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
              @endphp
              <a href="{{ route('news.detail', $itemLain->slug) }}" class="flex items-center gap-3 group hover:bg-slate-50 p-2 rounded-2xl transition-all">
                <img src="{{ $imgLain }}" alt="{{ $itemLain->title }}" loading="lazy" class="w-14 h-14 rounded-xl object-cover shrink-0">
                <div>
                  <span class="text-[9px] font-bold text-cyan-600 uppercase">Artikel</span>
                  <h5 class="text-xs font-bold text-slate-800 leading-snug group-hover:text-cyan-600 transition-colors line-clamp-2">{{ $itemLain->title }}</h5>
                  <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $itemLain->created_at ? $itemLain->created_at->format('d M Y') : '' }}</span>
                </div>
              </a>
            @empty
              <p class="text-xs text-slate-400 py-2">Berita lainnya belum tersedia.</p>
            @endforelse
          </div>
        </div>
      </aside>

    </div>
  </main>

  <!-- SECTION REKOMENDASI BERITA BOTTOM -->
  @if($beritaLainnya->count() > 0)
    <section class="bg-gradient-to-b from-cyan-50/50 to-slate-100/60 py-12 mt-12 border-t border-slate-100">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 reveal">

        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-extrabold text-slate-900">Rekomendasi News & Events Menarik</h2>
            <p class="text-xs text-slate-500 mt-0.5">Artikel dan tips lainnya yang informatif untuk dibaca.</p>
          </div>
          <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-600 hover:text-cyan-700 transition-colors">
            <span>Lihat Semua</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
          </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          @foreach($beritaLainnya->take(3) as $rekom)
            @php
              $rekomImg = $rekom->image ? asset('storage/' . $rekom->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
            @endphp
            <div class="bg-white rounded-3xl p-3 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between border border-slate-100 group">
              <div class="rounded-2xl overflow-hidden h-44 relative bg-slate-100">
                <img src="{{ $rekomImg }}" alt="{{ $rekom->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
              </div>
              <div class="p-4 space-y-3 flex-1 flex flex-col justify-between">
                <div>
                  <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-wider">Artikel</span>
                  <h4 class="font-extrabold text-slate-900 text-sm mt-1 leading-snug line-clamp-2 hover:text-cyan-600 transition-colors" title="{{ $rekom->title }}">
                    <a href="{{ route('news.detail', $rekom->slug) }}">{{ $rekom->title }}</a>
                  </h4>
                  <p class="text-slate-500 text-xs mt-1 line-clamp-2">{{ Str::limit(strip_tags($rekom->deskripsi), 80) }}</p>
                </div>
                <a href="{{ route('news.detail', $rekom->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-cyan-600 hover:text-cyan-700 pt-2 border-t border-slate-100">
                  <span>Baca Selengkapnya</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
              </div>
            </div>
          @endforeach
        </div>

      </div>
    </section>
  @endif

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
        setTimeout(() => { loader.style.opacity = '0'; }, 400);
      }
    });

    // Share Functions
    function shareToWhatsApp() {
      const pageUrl = window.location.href;
      const text = encodeURIComponent(`Baca artikel menarik di Sinar Citra Lestari: ${pageUrl}`);
      window.open(`https://wa.me/?text=${text}`, '_blank');
    }

    function shareToFacebook() {
      const pageUrl = window.location.href;
      window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`, '_blank');
    }

    function copyPageUrl() {
      navigator.clipboard.writeText(window.location.href).then(() => {
        const toast = document.getElementById('copy-toast');
        if (toast) {
          toast.classList.remove('opacity-0');
          setTimeout(() => { toast.classList.add('opacity-0'); }, 2000);
        }
      });
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
