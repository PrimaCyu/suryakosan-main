<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $artikel->title }} - NemuKOS</title>
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
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden pt-20">

  <!-- 1. BAR ANIMASI LOADING HALAMAN -->
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-cyan-500 z-[100] transition-all duration-500 ease-out"></div>

  <!-- 2. NAVBAR COMPONENT -->
  @include('frontend.navbar')

  @php
    $imgUrl = $artikel->image ? asset('storage/' . $artikel->image) : 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80';
  @endphp

  <!-- SEARCH BAR KHUSUS MOBILE & TABLET -->
  <div class="sticky top-20 z-30 block lg:hidden w-full bg-slate-50/95 backdrop-blur-md py-3 shadow-sm border-b border-slate-200/50 transition-all duration-300">
    <div class="max-w-4xl mx-auto px-4">
      <form action="{{ route('news.index') }}" method="GET" class="bg-white p-2.5 rounded-2xl shadow-md border border-slate-100 flex items-center gap-2">
        <i class="fa-solid fa-magnifying-glass text-slate-400 pl-2 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Berita Lain..." class="w-full bg-transparent text-xs text-slate-800 focus:outline-none">
        <button type="submit" class="px-4 py-1.5 bg-black text-white text-xs font-semibold rounded-xl hover:bg-slate-800 active:scale-95 transition-all">Cari</button>
      </form>
    </div>
  </div>

  <!-- MAIN CONTENT WRAPPER -->
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

      <!-- KOLOM UTAMA (ARTIKEL DETAIL) -->
      <article class="lg:col-span-8 space-y-6 reveal">

        <!-- Badge Kategori -->
        <div>
          <span class="inline-block px-3.5 py-1 bg-teal-200/80 text-teal-900 rounded-full text-[11px] font-bold uppercase tracking-wider">
            News & Event
          </span>
        </div>

        <!-- Judul Berita -->
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
          {{ $artikel->title }}
        </h1>

        <!-- Author, Views, Date, & Social Share Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 py-2 border-b border-slate-200/60 pb-4">
          <!-- Author Info & Viewer Stats -->
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-cyan-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">
              <i class="fa-regular fa-user"></i>
            </div>
            <div>
              <h4 class="text-xs font-bold text-slate-900">Admin NemuKOS</h4>
              <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                <span>{{ $artikel->created_at ? $artikel->created_at->format('d M Y') : 'Terbaru' }}</span>
                <span>•</span>
                <span class="flex items-center gap-1">
                  <i class="fa-solid fa-eye text-xs text-slate-400"></i> {{ number_format($artikel->view ?? 0) }} views
                </span>
              </div>
            </div>
          </div>

          <!-- Social Share Buttons -->
          <div class="flex items-center gap-2">
            <!-- WhatsApp -->
            <button onclick="shareToWhatsApp()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-emerald-500 hover:text-white text-slate-600 transition-all flex items-center justify-center text-xs shadow-sm hover:scale-110" title="Bagikan ke WhatsApp">
              <i class="fa-brands fa-whatsapp"></i>
            </button>
            <!-- Instagram -->
            <a href="https://ig.me/m/" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-pink-600 hover:text-white text-slate-600 transition-all flex items-center justify-center text-xs shadow-sm hover:scale-110" title="Kirim Pesan Instagram">
              <i class="fa-brands fa-instagram"></i>
            </a>
            <!-- Facebook -->
            <button onclick="shareToFacebook()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 transition-all flex items-center justify-center text-xs shadow-sm hover:scale-110" title="Kirim Pesan / Bagikan ke Facebook">
              <i class="fa-brands fa-facebook-f"></i>
            </button>
            <!-- Copy Link -->
            <button onclick="copyPageUrl()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-black hover:text-white text-slate-600 transition-all flex items-center justify-center text-xs shadow-sm hover:scale-110 relative" title="Salin Tautan">
              <i class="fa-solid fa-link"></i>
              <span id="copy-toast" class="absolute -top-8 left-1/2 -translate-x-1/2 bg-black text-white text-[10px] px-2 py-0.5 rounded opacity-0 transition-opacity pointer-events-none whitespace-nowrap">Tersalin!</span>
            </button>
          </div>
        </div>

        <!-- Featured Image Detail -->
        <div class="space-y-2">
          <div class="rounded-3xl overflow-hidden shadow-lg border border-slate-100 h-[280px] sm:h-[400px]">
            <img src="{{ $imgUrl }}" alt="{{ $artikel->title }}" loading="lazy" class="w-full h-full object-cover">
          </div>
        </div>

        <!-- Isi Konten Artikel -->
        <div class="space-y-5 text-sm sm:text-base text-slate-600 leading-relaxed pt-2">
          {!! nl2br(e($artikel->deskripsi ?? 'Konten artikel belum ditambahkan.')) !!}
        </div>

      </article>

      <!-- SIDEBAR DESKTOP SEARCH & BERITA LAIN -->
      <aside class="hidden lg:block lg:col-span-4 sticky top-28 space-y-6 reveal">
        <div class="bg-slate-100/80 backdrop-blur-sm p-6 rounded-3xl space-y-5 border border-slate-200/60 shadow-sm">
          <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
            <i class="fa-solid fa-magnifying-glass text-cyan-600"></i> Cari Berita Lain
          </h3>

          <!-- Input Search Sidebar -->
          <form action="{{ route('news.index') }}" method="GET" class="relative">
            <input type="text" name="search" placeholder="Ketik kata kunci..." class="w-full px-4 py-2.5 bg-white text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 shadow-sm">
          </form>

          <!-- List Berita Sidebar -->
          <div class="space-y-4">
            @forelse($beritaLainnya as $itemLain)
              @php
                $imgLain = $itemLain->image ? asset('storage/' . $itemLain->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
              @endphp
              <a href="{{ route('news.detail', $itemLain->slug) }}" class="flex items-center gap-3 group hover:bg-slate-200/50 p-2 rounded-2xl transition-all">
                <img src="{{ $imgLain }}" alt="{{ $itemLain->title }}" loading="lazy" class="w-14 h-14 rounded-xl object-cover shrink-0">
                <div>
                  <span class="text-[9px] font-bold text-cyan-600 uppercase">Artikel</span>
                  <h5 class="text-xs font-bold text-slate-800 leading-snug group-hover:text-cyan-600 transition-colors line-clamp-2">{{ $itemLain->title }}</h5>
                  <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $itemLain->created_at ? $itemLain->created_at->format('d M Y') : '' }}</span>
                </div>
              </a>
            @empty
              <p class="text-xs text-slate-400 py-2">Berita lainnya tidak ditemukan.</p>
            @endforelse
          </div>
        </div>
      </aside>

    </div>
  </main>

  <!-- SECTION REKOMENDASI BERITA BOTTOM -->
  <section class="bg-cyan-50/50 py-12 mt-12 border-t border-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 reveal">

      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-slate-900">Rekomendasi News & Events Menarik</h2>
          <p class="text-xs text-slate-500 mt-1">Berita dan Acara lainnya yang tidak kalah menarik untuk dibaca.</p>
        </div>
        <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-900 hover:text-cyan-600 transition-colors">
          Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
      </div>

      <!-- Grid Rekomendasi Berita -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($beritaLainnya->take(3) as $rekom)
          @php
            $rekomImg = $rekom->image ? asset('storage/' . $rekom->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
          @endphp
          <div class="bg-white rounded-3xl p-3 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between border border-slate-100">
            <div class="rounded-2xl overflow-hidden h-44 relative">
              <img src="{{ $rekomImg }}" alt="{{ $rekom->title }}" loading="lazy" class="w-full h-full object-cover">
            </div>
            <div class="p-4 space-y-3 flex-1 flex flex-col justify-between">
              <div>
                <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-wider">Artikel</span>
                <h4 class="font-bold text-slate-900 text-sm mt-1 leading-snug line-clamp-2" title="{{ $rekom->title }}">{{ $rekom->title }}</h4>
                <p class="text-slate-500 text-xs mt-1 line-clamp-2">{{ Str::limit(strip_tags($rekom->deskripsi), 80) }}</p>
              </div>
              <a href="{{ route('news.detail', $rekom->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-cyan-600 hover:text-cyan-700 pt-2">
                <i class="fa-solid fa-arrow-right text-[10px]"></i> Baca Artikel
              </a>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- FOOTER COMPONENT -->
  @include('frontend.footer')

  <!-- NEED HELP COMPONENT -->
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
      const text = encodeURIComponent(`Baca berita menarik ini: ${pageUrl}`);
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
