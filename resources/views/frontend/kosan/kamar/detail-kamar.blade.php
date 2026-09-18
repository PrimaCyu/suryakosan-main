<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sinar Citra Lestari - {{ $kamar->room ?? 'Detail Kamar' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="icon" href="{{ asset('scl.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    :root{--brown:#3B2314;--red:#E60049;--yellow:#F3A833;--green:#00A896;--cream:#FFF8F1;--line:#E9DDD2}
    body{background:radial-gradient(circle at 8% 12%,rgba(243,168,51,.12),transparent 24rem),radial-gradient(circle at 92% 42%,rgba(230,0,73,.06),transparent 22rem),var(--cream)}
    .reveal{opacity:0;transform:translateY(24px);transition:opacity .65s ease,transform .65s cubic-bezier(.16,1,.3,1)}
    .reveal.active{opacity:1;transform:translateY(0)}
    .scl-card{background:rgba(255,255,255,.9);border:1px solid rgba(233,221,210,.9);box-shadow:0 18px 45px rgba(59,35,20,.07)}
    .scl-hover{transition:transform .3s ease,box-shadow .3s ease,border-color .3s ease}
    .scl-hover:hover{transform:translateY(-4px);box-shadow:0 24px 55px rgba(59,35,20,.12);border-color:rgba(243,168,51,.55)}
    .shine{position:relative;overflow:hidden}.shine:after{content:"";position:absolute;inset:0;transform:translateX(-110%);background:linear-gradient(110deg,transparent 35%,rgba(255,255,255,.2),transparent 65%);transition:transform .7s ease;pointer-events:none}.shine:hover:after{transform:translateX(110%)}
    @media(prefers-reduced-motion:reduce){.reveal,.scl-hover,.shine:after{transition:none}}
  </style>
</head>
<body class="text-[#3B2314] font-sans antialiased overflow-x-hidden">
  <!-- 1. BAR LOADING -->
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#3B2314] via-[#E60049] to-[#F3A833] z-[100] transition-all duration-500 ease-out"></div>

  <!-- 2. NAVBAR -->
  @include('frontend.navbar')

  @php
    $namaKosan = $kamar->productKosan->title ?? 'Sinar Citra Lestari';
    $namaKamar = $kamar->room ?? 'Kamar Exclusive';
  @endphp

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
    <!-- 3. BREADCRUMB -->
    <nav class="reveal mb-8" aria-label="Breadcrumb">
      <div class="inline-flex flex-wrap items-center gap-2 rounded-full border border-[#E9DDD2] bg-white/90 px-4 py-2.5 text-[11px] sm:text-xs shadow-sm">
        <a href="{{ route('kosan.index') }}" class="font-bold text-[#3B2314] hover:text-[#E60049] transition-colors">Kos-Kosan</a>
        <span class="text-[#F3A833]"><i class="fa-solid fa-minus text-[9px]"></i></span>
        <a href="{{ route('kosan.detail', $kamar->productKosan->slug ?? '#') }}" class="max-w-[180px] truncate font-semibold text-[#00A896] hover:text-[#E60049] transition-colors">{{ $namaKosan }}</a>
        <span class="text-[#F3A833]"><i class="fa-solid fa-minus text-[9px]"></i></span>
        <span class="max-w-[180px] truncate font-extrabold text-[#3B2314]">{{ $namaKamar }}</span>
      </div>
    </nav>

    <!-- 4. GALLERY GRID SECTION (PARTIAL) -->
    <section class="reveal grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-7 items-stretch mb-12">
      <aside class="lg:col-span-4 order-2 lg:order-1 rounded-[2rem] bg-[#3B2314] text-white p-6 sm:p-8 flex flex-col justify-between min-h-[430px] relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-40 h-40 rounded-full bg-[#E60049]/20"></div>
        <div class="absolute -left-10 bottom-10 w-28 h-28 rounded-full bg-[#F3A833]/15"></div>
        <div class="relative">
          <div class="flex items-center gap-2 mb-6"><span class="w-9 h-9 rounded-xl bg-[#F3A833] text-[#3B2314] flex items-center justify-center"><i class="fa-solid fa-door-open"></i></span><span class="text-[10px] uppercase tracking-[.2em] font-black text-[#F3A833]">Detail Kamar</span></div>
          <p class="text-sm font-semibold text-white/60 mb-2">{{ $namaKosan }}</p>
          <h1 class="text-3xl sm:text-4xl font-black leading-tight tracking-tight">{{ $namaKamar }}</h1>
          <div class="h-1 w-16 bg-[#E60049] rounded-full my-6"></div>
          <p class="text-sm leading-7 text-white/70 max-w-sm">Lihat kondisi kamar, fasilitas, dan rincian harga sebelum menentukan pilihan.</p>
        </div>
        <div class="relative grid grid-cols-2 gap-3 mt-8">
          <div class="rounded-2xl bg-white/10 border border-white/10 p-4"><i class="fa-solid fa-images text-[#F3A833] mb-2"></i><p class="text-[10px] uppercase tracking-wider text-white/50">Galeri</p><p class="font-bold text-sm">Foto Kamar</p></div>
          <div class="rounded-2xl bg-white/10 border border-white/10 p-4"><i class="fa-solid fa-circle-check text-[#00A896] mb-2"></i><p class="text-[10px] uppercase tracking-wider text-white/50">Info</p><p class="font-bold text-sm">Fasilitas</p></div>
        </div>
      </aside>

      <div class="lg:col-span-8 order-1 lg:order-2 rounded-[2rem] bg-white p-3 sm:p-4 border border-[#E9DDD2] shadow-[0_20px_55px_rgba(59,35,20,.08)]">
        <div class="rounded-[1.5rem] bg-[#FFF8F1] p-3 sm:p-4 h-full">
          @include('frontend.kosan.kamar.partials._gallery')
        </div>
      </div>
    </section>

    <!-- 5. KONTEN DETAIL & SIDEBAR HARGA -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
      <div class="lg:col-span-7 order-2 lg:order-1 reveal">
        <div class="scl-card rounded-[2rem] p-5 sm:p-7 lg:p-8 scl-hover">
          <div class="flex items-start justify-between gap-5 mb-7">
            <div><span class="inline-flex items-center gap-2 text-[10px] uppercase tracking-[.18em] font-black text-[#E60049]"><span class="w-7 h-7 rounded-full bg-[#E60049]/10 flex items-center justify-center"><i class="fa-solid fa-align-left"></i></span>Informasi Kamar</span><h2 class="text-2xl sm:text-3xl font-black mt-4">Kenali kamar sebelum booking</h2></div>
            <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-[#F3A833]/15 text-[#3B2314] items-center justify-center shrink-0"><i class="fa-solid fa-house-circle-check text-lg"></i></div>
          </div>
          <div class="rounded-2xl bg-[#FFF8F1] border border-[#E9DDD2] p-4 sm:p-5">
            @include('frontend.kosan.kamar.partials._description_facilities')
          </div>
        </div>
      </div>

      <aside class="lg:col-span-5 order-1 lg:order-2 reveal lg:sticky lg:top-24">
        <div class="rounded-[2rem] bg-[#F3A833] p-1 shadow-[0_22px_50px_rgba(59,35,20,.12)]">
          <div class="rounded-[1.7rem] bg-white p-5 sm:p-7">
            <div class="flex items-center justify-between gap-4 mb-6"><div><p class="text-[10px] uppercase tracking-[.18em] font-black text-[#00A896]">Rincian</p><h2 class="text-2xl font-black mt-1">Harga Kamar</h2></div><span class="w-11 h-11 rounded-2xl bg-[#3B2314] text-[#F3A833] flex items-center justify-center"><i class="fa-solid fa-receipt"></i></span></div>
            @include('frontend.kosan.kamar.partials._sidebar_price')
          </div>
        </div>
      </aside>
    </section>

    <!-- 6. BOTTOM ACTION BUTTONS -->
    <section class="reveal mt-10 rounded-[2rem] bg-[#3B2314] p-5 sm:p-7 lg:p-8 overflow-hidden relative">
      <div class="absolute right-0 top-0 w-64 h-64 rounded-full bg-[#E60049]/15 translate-x-1/3 -translate-y-1/3"></div>
      <div class="absolute left-1/2 bottom-0 w-44 h-44 rounded-full bg-[#F3A833]/10"></div>
      <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="max-w-xl"><div class="flex items-center gap-2 text-[#F3A833] mb-3"><i class="fa-solid fa-bolt"></i><span class="text-[10px] uppercase tracking-[.2em] font-black">Langkah berikutnya</span></div><h2 class="text-2xl sm:text-3xl font-black text-white">Sudah cocok dengan kamar ini?</h2><p class="text-sm text-white/65 mt-2 leading-6">Lanjutkan pemesanan atau tanyakan detail ketersediaan kepada pengelola.</p></div>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
          <a href="{{ route('form.booking.kamar', $kamar->id) }}" class="shine w-full sm:min-w-[230px] py-4 px-6 bg-[#E60049] hover:bg-[#c90040] text-white text-xs sm:text-sm font-extrabold rounded-2xl transition-all shadow-lg text-center flex items-center justify-center gap-3 active:scale-[.98]"><span>Pesan Kamar Ini Sekarang</span><i class="fa-solid fa-arrow-right"></i></a>
          <a id="btn-tanya-pemilik" href="https://wa.me/6282146138847?text={{ urlencode('Halo Admin Sinar Citra Lestari, saya ingin bertanya mengenai ketersediaan ' . $namaKamar . ' di ' . $namaKosan) }}" target="_blank" rel="noopener noreferrer" class="w-full sm:min-w-[190px] py-4 px-6 bg-[#FFF8F1] hover:bg-white text-[#3B2314] text-xs sm:text-sm font-extrabold rounded-2xl transition-all shadow-lg text-center flex items-center justify-center gap-3 active:scale-[.98]"><i class="fa-brands fa-whatsapp text-[#00A896] text-lg pointer-events-none"></i><span>Tanya Pengelola</span></a>
        </div>
      </div>
    </section>
  </main>

  <!-- 7. LIGHTBOX GALERI FOTO MODAL -->
  @include('frontend.kosan.kamar.partials._gallery_modal')

  <!-- 8. FOOTER -->
  @include('frontend.footer')

  <!-- 9. MOBILE FLOATING STICKY BOOKING BAR (KHUSUS SMARTPHONE) -->
  @php
    $monthlyPriceObj = $kamar->monthly_price;
    $monthlyPriceVal = $monthlyPriceObj ? $monthlyPriceObj->price : null;
    $monthlyDiscVal = $monthlyPriceObj ? $monthlyPriceObj->discount : 0;
  @endphp
  <div class="fixed bottom-0 left-0 right-0 z-40 bg-[#FFF8F1]/95 backdrop-blur-md border-t border-[#E9DDD2] p-3 px-4 shadow-[0_-6px_25px_rgba(59,35,20,0.1)] block lg:hidden">
    <div class="max-w-md mx-auto flex items-center justify-between gap-3">
      <div class="space-y-0.5">
        <span class="text-[10px] uppercase font-bold text-[#8E7B6D] tracking-wider block">Harga Sewa Unit</span>
        <div class="flex items-baseline gap-1.5">
          <span class="text-base sm:text-lg font-black text-[#E60049]">
            {{ $monthlyPriceVal ? 'Rp ' . number_format($monthlyPriceVal, 0, ',', '.') : 'Hubungi Kami' }}
          </span>
          @if($monthlyPriceVal)
            <span class="text-[10px] font-bold text-[#8E7B6D]">/bln</span>
          @endif
        </div>
      </div>
      <a href="{{ route('form.booking.kamar', $kamar->id) }}" class="shine px-5 py-3 bg-[#E60049] hover:bg-[#C90040] text-white font-black text-xs rounded-xl shadow-md active:scale-95 flex items-center gap-1.5 shrink-0">
        <span>Booking Kamar</span>
        <i class="fa-solid fa-arrow-right text-[10px]"></i>
      </a>
    </div>
  </div>

  <script>
    window.addEventListener('load',()=>{const loader=document.getElementById('page-loader');if(loader){loader.style.width='100%';setTimeout(()=>loader.style.opacity='0',350)}});
    const revealElements=document.querySelectorAll('.reveal');
    const revealOnScroll=()=>{const windowHeight=window.innerHeight;revealElements.forEach((el,index)=>{if(el.getBoundingClientRect().top<windowHeight-70){el.style.transitionDelay=`${Math.min(index*45,250)}ms`;el.classList.add('active')}})};
    window.addEventListener('scroll',revealOnScroll,{passive:true});window.addEventListener('load',revealOnScroll);
    document.querySelectorAll('a').forEach(link=>{link.addEventListener('click',()=>{if(link.id==='btn-tanya-pemilik'||link.href.includes('form.booking.kamar')){link.style.transform='scale(.985)';setTimeout(()=>link.style.transform='',120)}})});
  </script>
</body>
</html>
