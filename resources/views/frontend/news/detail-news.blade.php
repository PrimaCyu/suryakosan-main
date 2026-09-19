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

    /* Rich Article Typography & Formatting */
    .article-copy {
      font-size: 1.05rem;
      line-height: 1.9;
      color: #4A3728;
      word-break: break-word;
    }
    .article-copy p {
      margin-bottom: 1.35rem;
      line-height: 1.9;
    }
    .article-copy h1,
    .article-copy h2,
    .article-copy h3,
    .article-copy h4,
    .article-copy h5,
    .article-copy h6 {
      font-weight: 800;
      color: #2D1A0E;
      line-height: 1.3;
      margin-top: 2.2rem;
      margin-bottom: 0.85rem;
      letter-spacing: -0.02em;
    }
    .article-copy h1 { font-size: 2rem; border-bottom: 2px solid #F3EAE1; padding-bottom: 0.5rem; }
    .article-copy h2 { font-size: 1.65rem; border-bottom: 2px solid #F3EAE1; padding-bottom: 0.4rem; }
    .article-copy h3 { font-size: 1.35rem; }
    .article-copy h4 { font-size: 1.15rem; }
    .article-copy h5, .article-copy h6 { font-size: 1rem; }
    
    .article-copy ul {
      list-style-type: disc !important;
      padding-left: 1.6rem !important;
      margin-top: 0.5rem !important;
      margin-bottom: 1.35rem !important;
    }
    .article-copy ol {
      list-style-type: decimal !important;
      padding-left: 1.6rem !important;
      margin-top: 0.5rem !important;
      margin-bottom: 1.35rem !important;
    }
    .article-copy li {
      margin-bottom: 0.5rem;
      line-height: 1.8;
      display: list-item !important;
    }
    .article-copy li > ul, .article-copy li > ol {
      margin-top: 0.35rem !important;
      margin-bottom: 0.35rem !important;
    }
    .article-copy strong, .article-copy b {
      font-weight: 800;
      color: #2D1A0E;
    }
    .article-copy em, .article-copy i {
      font-style: italic;
    }
    .article-copy u {
      text-decoration: underline;
      text-underline-offset: 3px;
    }
    .article-copy blockquote {
      border-left: 4px solid #E60049;
      background: #FFF9F3;
      padding: 1rem 1.35rem;
      margin: 1.6rem 0;
      border-radius: 0 1rem 1rem 0;
      font-style: italic;
      color: #6D4C3D;
      box-shadow: 0 2px 10px rgba(59,35,20,0.03);
    }
    .article-copy a {
      color: #00A896;
      font-weight: 700;
      text-decoration: underline;
      text-underline-offset: 3px;
      transition: color 0.2s ease;
    }
    .article-copy a:hover {
      color: #E60049;
    }
    .article-copy hr {
      border: 0;
      height: 1px;
      background: linear-gradient(to right, transparent, #DCCEC1, transparent);
      margin: 2.2rem 0;
    }
    .article-copy img {
      border-radius: 1.25rem;
      margin: 1.6rem auto;
      max-width: 100%;
      height: auto;
      box-shadow: 0 8px 25px rgba(59,35,20,0.08);
      display: block;
    }
    .article-copy table {
      width: 100%;
      border-collapse: collapse;
      margin: 1.6rem 0;
      font-size: 0.95rem;
      border-radius: 0.85rem;
      overflow: hidden;
      border: 1px solid #EADFD6;
    }
    .article-copy th, .article-copy td {
      padding: 0.8rem 1rem;
      border: 1px solid #EADFD6;
      text-align: left;
    }
    .article-copy th {
      background-color: #F8F2EC;
      font-weight: 700;
      color: #3B2314;
    }
    .article-copy tr:nth-child(even) {
      background-color: #FCF8F5;
    }
    .article-copy code {
      background: #F3EAE1;
      color: #C0003D;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
      font-size: 0.88em;
      padding: 0.2em 0.45em;
      border-radius: 0.35rem;
      font-weight: 600;
    }
    .article-copy pre {
      background: #2D1A0E;
      color: #FFF8F1;
      padding: 1.25rem;
      border-radius: 1rem;
      overflow-x: auto;
      margin: 1.6rem 0;
      font-size: 0.9rem;
    }
    .article-copy pre code {
      background: transparent;
      color: inherit;
      padding: 0;
    }

    .share-pop { animation: sharePop .25s ease-out; }
    @keyframes sharePop {
      from { opacity: 0; transform: translateY(5px) scale(.96); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Custom Slim Scrollbar for Sidebar */
    .sidebar-scroll::-webkit-scrollbar {
      width: 4px;
    }
    .sidebar-scroll::-webkit-scrollbar-track {
      background: transparent;
    }
    .sidebar-scroll::-webkit-scrollbar-thumb {
      background: #E4D5C9;
      border-radius: 9999px;
    }
    /* TOC Modern Timeline Styling */
    .toc-item {
      display: flex;
      align-items: flex-start;
      gap: 0.65rem;
      padding: 0.4rem 0.65rem;
      border-radius: 0.65rem;
      color: #795F4D;
      font-weight: 600;
      font-size: 0.82rem;
      line-height: 1.45;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      word-break: break-word;
      position: relative;
    }
    .toc-item:hover {
      background-color: #FFF5EB;
      color: #E60049;
    }
    .toc-item .toc-dot {
      width: 7px;
      height: 7px;
      border-radius: 9999px;
      background-color: #D6C2B4;
      margin-top: 0.38rem;
      flex-shrink: 0;
      transition: all 0.25s ease;
    }
    .toc-item:hover .toc-dot {
      background-color: #E60049;
      transform: scale(1.15);
    }
    .toc-item.active {
      background: linear-gradient(90deg, #FFF0D9 0%, #FFF9F2 100%);
      color: #2D1A0E;
      font-weight: 800;
      box-shadow: 0 1px 3px rgba(230, 0, 73, 0.05);
    }
    .toc-item.active .toc-dot {
      background-color: #E60049;
      box-shadow: 0 0 0 3px rgba(230, 0, 73, 0.2);
      transform: scale(1.25);
    }
    .toc-item-h3 {
      padding-left: 1.35rem;
      font-size: 0.77rem;
      opacity: 0.92;
    }
    .toc-item-h3 .toc-dot {
      width: 5px;
      height: 5px;
      margin-top: 0.42rem;
    }

    /* Heading Highlight Flash upon jump */
    @keyframes headingPulse {
      0% { background-color: rgba(243, 168, 51, 0.28); }
      100% { background-color: transparent; }
    }
    .heading-flash {
      animation: headingPulse 1.8s ease-out;
      border-radius: 0.5rem;
      padding: 0.2rem 0.4rem;
      margin-left: -0.4rem;
      margin-right: -0.4rem;
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
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-7 items-start">

      <!-- KOLOM UTAMA (ARTIKEL DETAIL) -->
      <article class="lg:col-span-8 reveal">

        <!-- Judul Berita -->
        <h1 class="text-2xl sm:text-4xl lg:text-[2.65rem] font-black text-[#3B2314] leading-[1.14] tracking-tight">
          {{ $artikel->title }}
        </h1>

        <!-- Author, Views, Date, & Social Share Bar -->
        <div class="mt-5 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <!-- Author Info & Viewer Stats -->
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#E60049] text-white flex items-center justify-center text-sm shadow-md rotate-[-3deg]">
              <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
              <h4 class="text-xs font-extrabold text-[#3B2314]">Redaksi Sinar Citra Lestari</h4>
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
        <div class="mt-6 relative rounded-2xl overflow-hidden shadow-lg h-[260px] sm:h-[430px] bg-[#EADFD6] group">
          <img src="{{ $imgUrl }}" alt="{{ $artikel->title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.025]">
          <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#3B2314]/40 to-transparent pointer-events-none"></div>
        </div>

        <!-- MOBILE ACCORDION DAFTAR ISI (Khusus Layar HP / Tablet) -->
        <div id="mobile-toc-container" class="lg:hidden mt-6 mb-2 bg-white rounded-2xl border border-[#EADFD6] overflow-hidden shadow-sm">
          <button type="button" id="mobile-toc-toggle" onclick="toggleMobileToc()" class="w-full px-4.5 py-3.5 flex items-center justify-between text-left hover:bg-[#FFF8F1] transition-colors">
            <div class="flex items-center gap-3 min-w-0">
              <span class="w-8 h-8 rounded-xl bg-[#FFF0D9] text-[#E60049] flex items-center justify-center text-xs shrink-0 shadow-sm">
                <i class="fa-solid fa-list-ol"></i>
              </span>
              <div class="min-w-0">
                <div class="flex items-center gap-2">
                  <span class="text-xs font-black text-[#3B2314] tracking-tight">Daftar Isi Artikel</span>
                  <span id="mobile-toc-count" class="text-[10px] font-extrabold text-[#E60049] bg-[#FFF0D9] px-2 py-0.5 rounded-full"></span>
                </div>
                <p id="mobile-toc-subtitle" class="text-[11px] text-[#8A6B58] truncate mt-0.5">Ketuk untuk lihat bab pembahasan</p>
              </div>
            </div>
            <span class="w-7 h-7 rounded-lg bg-[#FAF3EC] flex items-center justify-center text-[#8A6B58] shrink-0 ml-2">
              <i id="mobile-toc-arrow" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
            </span>
          </button>
          <div id="mobile-toc-content" class="hidden px-4 pb-4 pt-2 border-t border-[#F5EBE1] space-y-1.5 max-h-72 overflow-y-auto sidebar-scroll text-xs">
            <!-- Diisi otomatis oleh JavaScript -->
          </div>
        </div>

        <!-- Isi Konten Artikel (Rata Sempurna Tanpa Margin Buatan) -->
        <div class="mt-6 article-copy text-sm sm:text-base text-[#5B4537] leading-[1.85] sm:leading-[1.9] bg-white p-4.5 sm:p-7 md:p-9 rounded-2xl border border-[#EADFD6] shadow-sm">
          {!! $artikel->parsed_deskripsi ?: '<p class="text-[#9A8170] italic">Konten artikel belum ditambahkan.</p>' !!}
        </div>

      </article>

      <!-- SIDEBAR DESKTOP MODERN: PROGRES BACA, DAFTAR ISI & EKSPLORASI -->
      <aside class="hidden lg:block lg:col-span-4 sticky top-24 max-h-[calc(100vh-6rem)] overflow-y-auto sidebar-scroll pr-1 space-y-4 reveal">
        
        <!-- 1. WIDGET DAFTAR ISI & PROGRES BACA INTERAKTIF -->
        <div id="toc-container" class="bg-white p-4.5 sm:p-5 rounded-2xl border border-[#EADFD6] shadow-sm transition-all duration-300">
          <!-- Header Progres Membaca -->
          <div class="flex items-center justify-between pb-2.5 border-b border-[#F5EBE1]">
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-[#E60049] animate-pulse"></span>
              <span class="text-[10px] uppercase tracking-wider font-extrabold text-[#795F4D]">Progres Membaca</span>
            </div>
            <span id="reading-percent-badge" class="text-[11px] font-black text-[#E60049] bg-[#FFF0D9] px-2.5 py-0.5 rounded-full">0%</span>
          </div>
          
          <!-- Progress Bar Grafis -->
          <div class="w-full bg-[#F5EBE1] h-1.5 rounded-full overflow-hidden mt-2.5 mb-3">
            <div id="reading-progress-bar" class="h-full bg-gradient-to-r from-[#F3A833] via-[#E60049] to-[#00A896] transition-all duration-150 rounded-full" style="width: 0%;"></div>
          </div>

          <!-- Judul Daftar Isi -->
          <div class="flex items-center justify-between mb-2">
            <h4 class="font-black text-[#3B2314] text-xs uppercase tracking-wide flex items-center gap-1.5">
              <i class="fa-solid fa-list-ul text-[#F3A833] text-xs"></i>
              Daftar Isi Artikel
            </h4>
            <span id="toc-count-badge" class="text-[10px] font-bold text-[#8A6B58] bg-[#FFF8F1] px-2 py-0.5 rounded-lg border border-[#EADFD6]"></span>
          </div>

          <!-- Kontainer Navigasi Bab yang Aktif Mengikuti Posisi Scroll -->
          <div class="relative pl-1 my-2">
            <nav id="toc-list" class="space-y-1 max-h-[320px] overflow-y-auto pr-1 sidebar-scroll text-xs">
              <!-- Diisi otomatis secara dinamis oleh JavaScript dari tag H2 & H3 -->
            </nav>
          </div>

          <!-- Tombol Pintas: Kembali ke Atas & Bagikan -->
          <div class="mt-3 pt-2.5 border-t border-[#F5EBE1] flex items-center justify-between text-[11px] text-[#8A6B58]">
            <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="hover:text-[#E60049] font-bold flex items-center gap-1.5 transition-colors px-2 py-1 rounded-lg hover:bg-[#FFF8F1]">
              <i class="fa-solid fa-arrow-up text-[10px]"></i> Ke Atas
            </button>
            <span class="text-[#D0C0B2]">•</span>
            <button type="button" onclick="copyPageUrl()" class="hover:text-[#00A896] font-bold flex items-center gap-1.5 transition-colors px-2 py-1 rounded-lg hover:bg-[#FFF8F1]">
              <i class="fa-solid fa-link text-[10px]"></i> Salin Tautan
            </button>
          </div>
        </div>

        <!-- 2. WIDGET PENCARIAN MODERN & HANGAT -->
        <div class="rounded-2xl bg-gradient-to-br from-[#2E180C] via-[#3B2314] to-[#24130A] p-4.5 sm:p-5 text-white shadow-md border border-[#523522] relative overflow-hidden">
          <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-[#E60049]/20 blur-xl pointer-events-none"></div>
          <div class="absolute -right-2 bottom-[-30px] w-20 h-20 rounded-full bg-[#F3A833]/15 blur-lg pointer-events-none"></div>

          <div class="relative">
            <span class="text-[10px] uppercase tracking-[.2em] font-black text-[#F3A833]">Eksplorasi</span>
            <h3 class="text-sm sm:text-base font-black mt-1 leading-tight text-white">Temukan bacaan lainnya.</h3>
            <p class="text-[11px] text-[#E0D0C4] mt-1 leading-relaxed">Cari informasi, tips, dan kabar terbaru seputar kosan.</p>

            <form action="{{ route('news.index') }}" method="GET" class="mt-3.5">
              <div class="relative flex items-center">
                <input type="text" name="search" placeholder="Ketik kata kunci..." class="w-full pl-3.5 pr-10 py-2.5 bg-white text-[#3B2314] text-xs rounded-xl border border-white/20 focus:outline-none focus:ring-2 focus:ring-[#F3A833] font-medium placeholder-[#9A8170] shadow-sm">
                <button type="submit" class="absolute right-1 w-8 h-8 rounded-lg bg-[#E60049] hover:bg-[#C90040] text-white transition-all flex items-center justify-center shadow-sm" title="Cari">
                  <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- 3. WIDGET ARTIKEL LAINNYA -->
        <div class="bg-white p-4.5 sm:p-5 rounded-2xl border border-[#EADFD6] shadow-sm">
          <div class="flex items-center justify-between mb-3.5">
            <div>
              <span class="text-[10px] uppercase tracking-wider font-bold text-[#00A896]">Pilihan bacaan</span>
              <h3 class="font-black text-[#3B2314] text-sm mt-0.5">Artikel Terkait</h3>
            </div>
            <span class="w-7 h-7 rounded-xl bg-[#FFF0D9] text-[#F3A833] flex items-center justify-center">
              <i class="fa-solid fa-book-open text-xs"></i>
            </span>
          </div>

          <div class="space-y-1.5">
            @forelse($beritaLainnya as $itemLain)
              @php
                $imgLain = $itemLain->image ? asset('storage/' . $itemLain->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80';
              @endphp
              <a href="{{ route('news.detail', $itemLain->slug) }}" class="flex items-center gap-3 group p-2 rounded-xl hover:bg-[#FFF8F1] transition-all">
                <img src="{{ $imgLain }}" alt="{{ $itemLain->title }}" loading="lazy" class="w-14 h-14 rounded-xl object-cover shrink-0 group-hover:scale-[1.03] transition-transform">
                <div class="min-w-0">
                  <span class="text-[9px] font-bold text-[#E60049] uppercase">Artikel</span>
                  <h5 class="text-xs font-extrabold text-[#3B2314] leading-snug group-hover:text-[#E60049] transition-colors line-clamp-2">{{ $itemLain->title }}</h5>
                  <span class="text-[10px] text-[#9A8170] mt-0.5 block">{{ $itemLain->created_at ? $itemLain->created_at->format('d M Y') : '' }}</span>
                </div>
              </a>
            @empty
              <p class="text-xs text-[#9A8170] py-2">Berita lainnya belum tersedia.</p>
            @endforelse
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

  <!-- 4. FLOATING MOBILE READING ISLAND (Khusus Layar HP & Tablet) -->
  <div id="mobile-reading-pill" class="lg:hidden fixed bottom-4 inset-x-3 sm:inset-x-6 max-w-sm mx-auto z-40 bg-[#2D1A0E]/95 backdrop-blur-md text-white rounded-full p-2 pl-3.5 pr-2 shadow-2xl border border-white/15 flex items-center justify-between gap-2.5 transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none">
    <div class="flex items-center gap-2.5 min-w-0 flex-1">
      <!-- Mini Circular Progress Indicator -->
      <div class="relative w-8 h-8 shrink-0 flex items-center justify-center">
        <svg class="w-8 h-8 transform -rotate-90" viewBox="0 0 36 36">
          <path class="text-white/20" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
          <path id="mobile-float-circle" class="text-[#E60049] transition-all duration-150" stroke-dasharray="0, 100" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
        </svg>
        <span id="mobile-float-percent" class="absolute text-[9px] font-black">0%</span>
      </div>
      <!-- Current Chapter Name -->
      <div class="min-w-0 flex-1">
        <span class="text-[9px] uppercase tracking-wider text-[#F3A833] font-extrabold block">Sedang Dibaca</span>
        <span id="mobile-float-title" class="text-xs font-bold text-white truncate block">Pengantar Artikel</span>
      </div>
    </div>
    <!-- Quick Actions -->
    <div class="flex items-center gap-1.5 shrink-0">
      <button type="button" onclick="openMobileTocAccordion()" class="px-2.5 py-1.5 rounded-full bg-white/10 hover:bg-[#E60049] text-white text-[11px] font-bold flex items-center gap-1 transition-all" title="Buka Bab Pembahasan">
        <i class="fa-solid fa-list-ul text-[10px]"></i>
        <span>Bab</span>
      </button>
      <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="w-7 h-7 rounded-full bg-white/10 hover:bg-[#F3A833] hover:text-[#3B2314] text-white text-[11px] flex items-center justify-center transition-all" title="Ke Atas">
        <i class="fa-solid fa-arrow-up text-[10px]"></i>
      </button>
    </div>
  </div>

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

    // Toggle Mobile TOC Accordion
    function toggleMobileToc() {
      const content = document.getElementById('mobile-toc-content');
      const arrow = document.getElementById('mobile-toc-arrow');
      if (!content) return;
      const isHidden = content.classList.contains('hidden');
      if (isHidden) {
        content.classList.remove('hidden');
        if (arrow) arrow.style.transform = 'rotate(180deg)';
      } else {
        content.classList.add('hidden');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
      }
    }

    // Open Mobile TOC and Scroll to Accordion
    function openMobileTocAccordion() {
      const container = document.getElementById('mobile-toc-container');
      const content = document.getElementById('mobile-toc-content');
      const arrow = document.getElementById('mobile-toc-arrow');
      if (container) {
        const navbarOffset = 80;
        const elementPosition = container.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - navbarOffset;
        window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
      }
      if (content && content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        if (arrow) arrow.style.transform = 'rotate(180deg)';
      }
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

    // Dynamic Table of Contents (Daftar Isi Otomatis) & Live Reading Progress
    document.addEventListener('DOMContentLoaded', () => {
      const articleBody = document.querySelector('.article-copy');
      const tocContainer = document.getElementById('toc-container');
      const tocList = document.getElementById('toc-list');
      const tocCountBadge = document.getElementById('toc-count-badge');
      const mobileTocContent = document.getElementById('mobile-toc-content');
      const mobileTocCount = document.getElementById('mobile-toc-count');
      const mobileTocSubtitle = document.getElementById('mobile-toc-subtitle');
      const progressBar = document.getElementById('reading-progress-bar');
      const progressPercentBadge = document.getElementById('reading-percent-badge');
      const mobileReadingPill = document.getElementById('mobile-reading-pill');
      const mobileFloatCircle = document.getElementById('mobile-float-circle');
      const mobileFloatPercent = document.getElementById('mobile-float-percent');
      const mobileFloatTitle = document.getElementById('mobile-float-title');

      if (!articleBody) return;

      const headings = articleBody.querySelectorAll('h2, h3');

      if (headings.length < 1) {
        if (tocList) {
          tocList.innerHTML = `
            <div class="py-2.5 px-2 text-[11px] text-[#9A8170] italic leading-relaxed">
              Bacalah naskah dengan panduan progres di atas.
            </div>
          `;
        }
        if (tocCountBadge) tocCountBadge.textContent = 'Ringkasan';
        if (mobileTocCount) mobileTocCount.textContent = 'Ringkas';
      } else {
        if (tocCountBadge) tocCountBadge.textContent = `${headings.length} Bab`;
        if (mobileTocCount) mobileTocCount.textContent = `${headings.length} Bab`;

        headings.forEach((heading, idx) => {
          if (!heading.id) {
            heading.id = 'section-heading-' + (idx + 1);
          }

          const isH3 = heading.tagName.toLowerCase() === 'h3';
          const rawText = heading.textContent.trim();

          // A. Desktop Link (Timeline Style with Dot)
          if (tocList) {
            const link = document.createElement('a');
            link.href = '#' + heading.id;
            link.className = 'toc-item' + (isH3 ? ' toc-item-h3' : '');
            link.dataset.targetId = heading.id;
            link.title = rawText;
            link.innerHTML = `
              <span class="toc-dot"></span>
              <span class="line-clamp-2 leading-snug">${rawText}</span>
            `;

            link.addEventListener('click', (e) => {
              e.preventDefault();
              const targetEl = document.getElementById(heading.id);
              if (targetEl) {
                const navbarOffset = 90;
                const elementPosition = targetEl.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - navbarOffset;
                window.scrollTo({ top: offsetPosition, behavior: 'smooth' });

                targetEl.classList.remove('heading-flash');
                void targetEl.offsetWidth; // trigger reflow
                targetEl.classList.add('heading-flash');
              }
            });

            tocList.appendChild(link);
          }

          // B. Mobile Accordion Link (Touch Friendly)
          if (mobileTocContent) {
            const mLink = document.createElement('a');
            mLink.href = '#' + heading.id;
            mLink.className = `flex items-center gap-2.5 p-2 rounded-xl text-[#6D5445] hover:text-[#E60049] hover:bg-[#FFF8F1] transition-colors font-semibold ${isH3 ? 'pl-6 text-[11px]' : 'text-xs'}`;
            mLink.dataset.targetId = heading.id;
            mLink.innerHTML = `
              <span class="w-1.5 h-1.5 rounded-full ${isH3 ? 'bg-[#9A8170]' : 'bg-[#E60049]'} shrink-0"></span>
              <span class="line-clamp-1">${rawText}</span>
            `;

            mLink.addEventListener('click', (e) => {
              e.preventDefault();
              const targetEl = document.getElementById(heading.id);
              if (targetEl) {
                const navbarOffset = 80;
                const elementPosition = targetEl.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - navbarOffset;
                window.scrollTo({ top: offsetPosition, behavior: 'smooth' });

                targetEl.classList.remove('heading-flash');
                void targetEl.offsetWidth; // trigger reflow
                targetEl.classList.add('heading-flash');

                // Auto collapse mobile accordion after selection
                setTimeout(() => {
                  mobileTocContent.classList.add('hidden');
                  const arrow = document.getElementById('mobile-toc-arrow');
                  if (arrow) arrow.style.transform = 'rotate(0deg)';
                }, 200);
              }
            });

            mobileTocContent.appendChild(mLink);
          }
        });
      }

      // Live Reading Progress, Active Heading & Floating Mobile Pill
      const updateReadingTracker = () => {
        const articleRect = articleBody.getBoundingClientRect();
        const articleTop = articleRect.top + window.scrollY;
        const articleHeight = articleBody.offsetHeight;
        const windowHeight = window.innerHeight;
        const currentScrollY = window.scrollY;

        let percent = 0;
        if (currentScrollY > articleTop - 120) {
          const scrolledPast = currentScrollY - (articleTop - 120);
          const totalScrollable = Math.max(1, articleHeight - windowHeight / 2);
          percent = Math.min(100, Math.max(0, Math.round((scrolledPast / totalScrollable) * 100)));
        }

        // Update Desktop Progress Bar & Badge
        if (progressBar) progressBar.style.width = percent + '%';
        if (progressPercentBadge) progressPercentBadge.textContent = percent + '%';

        // Update Mobile Floating Pill Progress
        if (mobileFloatCircle) mobileFloatCircle.setAttribute('stroke-dasharray', `${percent}, 100`);
        if (mobileFloatPercent) mobileFloatPercent.textContent = percent + '%';

        // Show/Hide Mobile Floating Pill
        if (mobileReadingPill) {
          if (currentScrollY > 320 && percent < 98) {
            mobileReadingPill.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
            mobileReadingPill.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
          } else {
            mobileReadingPill.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
            mobileReadingPill.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
          }
        }

        // Active Heading Detection
        if (headings.length > 0) {
          let currentActiveId = null;
          let currentActiveText = 'Pengantar Artikel';

          headings.forEach(heading => {
            const top = heading.getBoundingClientRect().top;
            if (top <= 160) {
              currentActiveId = heading.id;
              currentActiveText = heading.textContent.trim();
            }
          });

          if (!currentActiveId && headings[0].getBoundingClientRect().top < windowHeight * 0.75) {
            currentActiveId = headings[0].id;
            currentActiveText = headings[0].textContent.trim();
          }

          // Update Desktop Active Link with Smooth Auto-Scroll
          if (tocList) {
            const tocLinks = tocList.querySelectorAll('.toc-item');
            let activeEl = null;
            tocLinks.forEach(link => {
              if (link.dataset.targetId === currentActiveId) {
                if (!link.classList.contains('active')) {
                  link.classList.add('active');
                  activeEl = link;
                }
              } else {
                link.classList.remove('active');
              }
            });

            // Smoothly scroll active element into view inside the TOC container without jumping!
            if (activeEl) {
              activeEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
          }

          // Update Mobile Preview Subtitle and Floating Pill Title
          if (mobileFloatTitle) {
            mobileFloatTitle.textContent = currentActiveText;
          }
          if (mobileTocSubtitle && currentActiveId) {
            mobileTocSubtitle.textContent = `Aktif: ${currentActiveText}`;
          }
        }
      };

      window.addEventListener('scroll', updateReadingTracker, { passive: true });
      updateReadingTracker();
    });
  </script>
</body>
</html>