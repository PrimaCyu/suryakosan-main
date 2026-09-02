<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NemuKOS - {{ $kamar->room ?? 'Detail Kamar' }}</title>
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
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

        @php
          $namaKosan = $kamar->productKosan->title ?? 'NemuKOS Properti';
          $namaKamar = $kamar->room ?? 'Kamar Exclusive';
        @endphp

        <!-- 3. BREADCRUMB -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 reveal">
          <a href="{{ route('kosan.index') }}" class="hover:text-cyan-600 transition-colors">Kos-Kosan</a>
          <i class="fa-solid fa-chevron-right text-[9px]"></i>
          <a href="{{ route('kosan.detail', $kamar->productKosan->slug ?? '#') }}" class="text-slate-500 hover:text-cyan-600 transition-colors font-medium">{{ $namaKosan }}</a>
          <i class="fa-solid fa-chevron-right text-[9px]"></i>
          <span class="text-slate-800 font-bold">{{ $namaKamar }}</span>
        </nav>

        <!-- 4. GALLERY GRID SECTION (PARTIAL) -->
        @include('frontend.kosan.kamar.partials._gallery')

        <!-- 5. KONTEN DETAIL & SIDEBAR HARGA -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-4">
          <!-- Deskripsi & Fasilitas Kamar (Partial) -->
          @include('frontend.kosan.kamar.partials._description_facilities')

          <!-- Sidebar Rincian Harga (Partial) -->
          @include('frontend.kosan.kamar.partials._sidebar_price')
        </div>

        <!-- 6. BOTTOM ACTION BUTTONS -->
        <div class="flex flex-col sm:flex-row items-center gap-3 pt-6 reveal">
          <a href="{{ route('form.booking.kamar', $kamar->id) }}" class="w-full sm:flex-1 py-4 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white text-xs sm:text-sm font-extrabold rounded-2xl transition-all shadow-md hover:shadow-cyan-500/25 text-center flex items-center justify-center gap-2 active:scale-95">
            <span>Pesan Kamar Ini Sekarang</span> <i class="fa-solid fa-chevron-right text-xs"></i>
          </a>
          <a id="btn-tanya-pemilik" href="https://wa.me/6282146138847?text={{ urlencode('Halo Admin NemuKOS, saya ingin bertanya mengenai ketersediaan ' . $namaKamar . ' di ' . $namaKosan) }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto px-8 py-4 bg-white border border-slate-200 hover:bg-slate-50 text-slate-800 text-xs sm:text-sm font-extrabold rounded-2xl transition-all shadow-sm hover:shadow text-center flex items-center justify-center gap-2 active:scale-95">
            <i class="fa-brands fa-whatsapp text-emerald-500 text-lg"></i> <span>Tanya Pengelola</span>
          </a>
        </div>

    </main>

    <!-- 7. LIGHTBOX GALERI FOTO MODAL -->
    @include('frontend.kosan.kamar.partials._gallery_modal')

    <!-- 8. FOOTER -->
    @include('frontend.footer')

    <!-- 9. NEED HELP WIDGET -->
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
