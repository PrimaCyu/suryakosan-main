<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $artikel->title }} - Sinar Citra Lestari</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="icon" href="{{ asset('scl.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    :root {
      --scl-brown: #3B2314;
      --scl-red: #E60049;
      --scl-yellow: #F3A833;
      --scl-green: #00A896;
      --scl-cream: #FFF8F1;
    }

    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity .65s ease, transform .65s cubic-bezier(.16,1,.3,1);
    }
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }
    .article-copy p { margin-bottom: 1rem; }
    .article-copy img { border-radius: 1.25rem; margin: 1.5rem 0; max-width: 100%; }
    .share-pop { animation: sharePop .25s ease-out; }
    @keyframes sharePop {
      from { opacity: 0; transform: translateY(5px) scale(.96); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
  </style>
</head>

<body class="bg-[#FFF8F1] text-[#3B2314] font-sans antialiased overflow-x-hidden">

  <!-- 1. BAR LOADING -->
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#F3A833] via-[#E60049] to-[#00A896] transition-all duration-500 ease-out"></div>

  <!-- 2. NAVBAR -->
  @include('frontend.navbar')

  @php
    $imgUrl = $artikel->image ? asset('storage/' . $artikel->image) : 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80';
  @endphp

  <!-- MAIN CONTENT WRAPPER -->
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-7">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

      <!-- KOLOM UTAMA (ARTIKEL DETAIL) -->
      <article class="lg:col-span-8 reveal">

        <!-- Judul Berita -->
        <h1 class="text-3xl sm:text-5xl font-black text-[#3B2314] leading-[1.08] tracking-tight max-w-4xl">
          {{ $artikel->title }}
        </h1>

        <!-- Author, Views, Date, & Social Share Bar -->
        <div class="mt-6 flex flex-col sm:flex-row sm:items-end justify-between gap-5">
          <!-- Author Info & Viewer Stats -->
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#E60049] text-white flex items-center justify-center text-sm shadow-md rotate-[-3deg]">
              <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
              <h4 class="text-xs font-extrabold text-[#3B2314]">Redaksi NemuKOS</h4>
              <div class="flex flex-wrap items-center gap-2 text-[11px] text-[#8A6B58] mt-1 font-medium">
                <span>{{ $artikel->created_at ? $artikel->created_at->translatedFormat('d F Y') : 'Terbaru' }}</span>
                <span class="text-[#F3A833]">•</span>
                <span class="flex items-center gap-1">
                  <i class="fa-solid fa-eye text-[#00A896]"></i>
                  {{ number_format($artikel->view ?? 0) }} views
                </span>
              </div>
            </div>
          </div>

          <!-- Social Share Buttons -->
          <div class="flex items-center gap-2">
            <span class="hidden sm:inline text-[10px] font-bold uppercase tracking-wider text-[#A78A77] mr-1">Bagikan</span>
            <button onclick="shareToWhatsApp()" class="w-9 h-9 rounded-xl bg-[#E8F7F4] hover:bg-[#00A896] hover:text-white text-[#008F80] transition-all flex items-center justify-center text-xs shadow-sm hover:-translate-y-1" title="Bagikan ke WhatsApp">
              <i class="fa-brands fa-whatsapp text-sm"></i>
            </button>
            <button onclick="shareToFacebook()" class="w-9 h-9 rounded-xl bg-[#FFF0D9] hover:bg-[#F3A833] hover:text-[#3B2314] text-[#B76E00] transition-all flex items-center justify-center text-xs shadow-sm hover:-translate-y-1" title="Bagikan ke Facebook">
              <i class="fa-brands fa-facebook-f text-xs"></i>
            </button>
            <button onclick="copyPageUrl()" class="w-9 h-9 rounded-xl bg-[#F7E6EC] hover:bg-[#E60049] hover:text-white text-[#C0003D] transition-all flex items-center justify-center text-xs shadow-sm hover:-translate-y-1 relative" title="Salin Tautan">
              <i class="fa-solid fa-link text-xs"></i>
              <span id="copy-toast" class="absolute -top-9 left-1/2 -translate-x-1/2 bg-[#3B2314] text-[#FFF8F1] text-[10px] px-2.5 py-1 rounded-lg opacity-0 transition-opacity pointer-events-none whitespace-nowrap font-bold">Tersalin!</span>
            </button>
          </div>
        </div>

        <!-- Featured Image Detail -->
        <div class="mt-7 relative rounded-[2rem] overflow-hidden shadow-xl h-[300px] sm:h-[470px] bg-[#EADFD6] group">
          <img src="{{ $imgUrl }}" alt="{{ $artikel->title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.025]">
          <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#3B2314]/40 to-transparent pointer-events-none"></div>
        </div>

        <!-- Isi Konten Artikel -->
        <div class="mt-7 grid grid-cols-1 sm:grid-cols-[8px_1fr] gap-5 items-stretch">
          <div class="hidden sm:block rounded-full bg-gradient-to-b from-[#E60049] via-[#F3A833] to-[#00A896]"></div>
          <div class="article-copy text-sm sm:text-base text-[#5B4537] leading-[1.9] bg-white p-6 sm:p-9 rounded-[2rem] border border-[#EADFD6] shadow-sm">
            {!! nl2br(e($artikel->deskripsi ?? 'Konten artikel belum ditambahkan.')) !!}
          </div>
        </div>

      </article>

      <!-- SIDEBAR DESKTOP SEARCH & BERITA LAIN -->
      <aside class="hidden lg:block lg:col-span-4 sticky top-28 reveal">
        <div class="space-y-5">

          <div class="rounded-[2rem] bg-[#3B2314] p-6 text-[#FFF8F1] shadow-lg overflow-hidden relative">
            <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full bg-[#E60049]/30"></div>
            <div class="absolute -right-2 bottom-[-35px] w-24 h-24 rounded-full bg-[#F3A833]/20"></div>

            <div class="relative">
              <span class="text-[10px] uppercase tracking-[.2em] font-bold text-[#F3A833]">Eksplorasi</span>
              <h3 class="text-xl font-black mt-2 leading-tight">Temukan bacaan lainnya.</h3>
              <p class="text-xs text-[#E8D9CD] mt-2 leading-relaxed">Cari informasi, tips, dan kabar terbaru seputar kosan.</p>

              <form action="{{ route('news.index') }}" method="GET" class="mt-5">
                <div class="relative">
                  <input type="text" name="search" placeholder="Ketik kata kunci..." class="w-full px-4 py-3 pr-11 bg-[#FFF8F1] text-[#3B2314] text-xs rounded-2xl border-0 focus:outline-none focus:ring-2 focus:ring-[#F3A833] font-medium placeholder-[#9A8170]">
                  <button type="submit" class="absolute right-1.5 top-1.5 w-9 h-9 rounded-xl bg-[#E60049] hover:bg-[#C90040] text-white transition-all flex items-center justify-center">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div class="bg-white p-6 rounded-[2rem] border border-[#EADFD6] shadow-sm">
            <div class="flex items-center justify-between mb-5">
              <div>
                <span class="text-[10px] uppercase tracking-wider font-bold text-[#00A896]">Pilihan bacaan</span>
                <h3 class="font-black text-[#3B2314] text-base mt-1">Artikel Lainnya</h3>
              </div>
              <span class="w-8 h-8 rounded-xl bg-[#FFF0D9] text-[#F3A833] flex items-center justify-center">
                <i class="fa-solid fa-book-open text-xs"></i>
              </span>
            </div>

            <div class="space-y-2">
              @forelse($beritaLainnya as $itemLain)
                @php
                  $imgLain = $itemLain->image ? asset('storage/' . $itemLain->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
                @endphp
                <a href="{{ route('news.detail', $itemLain->slug) }}" class="flex items-center gap-3 group p-2.5 rounded-2xl hover:bg-[#FFF8F1] transition-all">
                  <img src="{{ $imgLain }}" alt="{{ $itemLain->title }}" loading="lazy" class="w-16 h-16 rounded-xl object-cover shrink-0 group-hover:scale-[1.03] transition-transform">
                  <div class="min-w-0">
                    <span class="text-[9px] font-bold text-[#E60049] uppercase">Artikel</span>
                    <h5 class="text-xs font-extrabold text-[#3B2314] leading-snug group-hover:text-[#E60049] transition-colors line-clamp-2">{{ $itemLain->title }}</h5>
                    <span class="text-[10px] text-[#9A8170] mt-1 block">{{ $itemLain->created_at ? $itemLain->created_at->format('d M Y') : '' }}</span>
                  </div>
                </a>
              @empty
                <p class="text-xs text-[#9A8170] py-2">Berita lainnya belum tersedia.</p>
              @endforelse
            </div>
          </div>

        </div>
      </aside>

    </div>
  </main>

  <!-- SECTION REKOMENDASI BERITA BOTTOM -->
  @if($beritaLainnya->count() > 0)
    <section class="bg-[#3B2314] py-14 mt-10 overflow-hidden">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-7 reveal">

        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <span class="inline-flex items-center gap-2 text-[10px] uppercase tracking-[.18em] font-bold text-[#F3A833]">
              <span class="w-7 h-px bg-[#F3A833]"></span> Lanjut membaca
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-[#FFF8F1] mt-2">Rekomendasi Menarik</h2>
          </div>
          <a href="{{ route('news.index') }}" class="self-start sm:self-auto inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-[#FFF8F1] text-[#3B2314] text-xs font-extrabold hover:bg-[#F3A833] transition-all hover:-translate-y-0.5">
            <span>Lihat Semua</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
          </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          @foreach($beritaLainnya->take(3) as $rekom)
            @php
              $rekomImg = $rekom->image ? asset('storage/' . $rekom->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
            @endphp
            <div class="bg-[#FFF8F1] rounded-[2rem] p-3 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between group">
              <a href="{{ route('news.detail', $rekom->slug) }}" class="block rounded-[1.5rem] overflow-hidden h-48 relative bg-[#EADFD6]">
                <img src="{{ $rekomImg }}" alt="{{ $rekom->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-[#3B2314]/90 text-[#FFF8F1] text-[9px] font-bold uppercase">Artikel</span>
              </a>

              <div class="p-4 space-y-3 flex-1 flex flex-col justify-between">
                <div>
                  <span class="text-[10px] font-bold text-[#00A896] uppercase tracking-wider">{{ $rekom->created_at ? $rekom->created_at->format('d M Y') : 'Terbaru' }}</span>
                  <h4 class="font-black text-[#3B2314] text-sm mt-1.5 leading-snug line-clamp-2" title="{{ $rekom->title }}">
                    <a href="{{ route('news.detail', $rekom->slug) }}" class="hover:text-[#E60049] transition-colors">{{ $rekom->title }}</a>
                  </h4>
                  <p class="text-[#795F4D] text-xs mt-2 line-clamp-2 leading-relaxed">{{ Str::limit(strip_tags($rekom->deskripsi), 80) }}</p>
                </div>
                <a href="{{ route('news.detail', $rekom->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#E60049] hover:text-[#B9003A] pt-3 border-t border-[#EADFD6]">
                  <span>Baca Selengkapnya</span>
                  <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
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
          toast.classList.add('share-pop');
          setTimeout(() => {
            toast.classList.add('opacity-0');
            toast.classList.remove('share-pop');
          }, 2000);
        }
      }).catch(() => {
        const toast = document.getElementById('copy-toast');
        if (toast) {
          toast.textContent = 'Gagal menyalin';
          toast.classList.remove('opacity-0');
          setTimeout(() => {
            toast.classList.add('opacity-0');
            toast.textContent = 'Tersalin!';
          }, 2000);
        }
      });
    }

    // Scroll Reveal
    const revealElements = document.querySelectorAll('.reveal');
    const revealOnScroll = () => {
      const windowHeight = window.innerHeight;
      revealElements.forEach(el => {
        if (el.getBoundingClientRect().top < windowHeight - 70) {
          el.classList.add('active');
        }
      });
    };

    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', revealOnScroll);
  </script>
</body>
</html>