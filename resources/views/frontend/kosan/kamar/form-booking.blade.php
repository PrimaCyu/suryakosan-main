<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Booking - {{ $kamar->room ?? 'Kamar Kos' }} - Sinar Citra Lestari</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="icon" href="{{ asset('scl.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <style>
    :root {
      --brown: #3B2314;
      --red: #E60049;
      --yellow: #F3A833;
      --green: #00A896;
      --cream: #FFF8F1;
      --line: #E9DDD2;
    }

    body {
      background:
        radial-gradient(circle at 5% 10%, rgba(243,168,51,.13), transparent 23rem),
        radial-gradient(circle at 95% 55%, rgba(230,0,73,.06), transparent 25rem),
        var(--cream);
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

    .booking-card {
      background: rgba(255,255,255,.9);
      border: 1px solid rgba(233,221,210,.95);
      box-shadow: 0 18px 50px rgba(59,35,20,.07);
    }

    .input-scl {
      transition: border-color .25s ease, box-shadow .25s ease, background .25s ease;
    }

    .input-scl:focus {
      border-color: var(--yellow) !important;
      box-shadow: 0 0 0 4px rgba(243,168,51,.14);
      outline: none;
    }

    .payment-option {
      transition: transform .25s ease, border-color .25s ease, background .25s ease, box-shadow .25s ease;
    }

    .payment-option:hover {
      transform: translateY(-2px);
    }

    /* FLATPICKR LUXURY THEME (RESPONSIVE & ZERO CLIPPING) */
    .flatpickr-calendar {
      background: #FFFFFF !important;
      border-radius: 1.5rem !important;
      box-shadow: 0 24px 60px rgba(59,35,20,0.18) !important;
      border: 1px solid var(--line) !important;
      font-family: inherit !important;
      width: 320px !important;
      padding: 12px !important;
      box-sizing: border-box !important;
      z-index: 99999 !important;
    }

    .flatpickr-calendar::before,
    .flatpickr-calendar::after {
      border-bottom-color: #FFFFFF !important;
    }

    .flatpickr-months {
      position: relative !important;
      align-items: center !important;
      padding: 4px 0 10px 0 !important;
      border-bottom: 1px solid #F3ECE6 !important;
    }

    .flatpickr-months .flatpickr-month {
      color: #3B2314 !important;
      height: 38px !important;
    }

    .flatpickr-current-month {
      font-size: 105% !important;
      font-weight: 800 !important;
      padding-top: 2px !important;
    }

    .flatpickr-current-month .cur-month {
      font-weight: 800 !important;
      color: #3B2314 !important;
    }

    .flatpickr-current-month input.cur-year {
      font-weight: 800 !important;
      color: #3B2314 !important;
    }

    /* Month Navigation Prev / Next Buttons */
    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
      position: absolute !important;
      top: 6px !important;
      width: 32px !important;
      height: 32px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      border-radius: 10px !important;
      background: #FFF8F1 !important;
      border: 1px solid #E9DDD2 !important;
      color: #3B2314 !important;
      transition: all 0.2s ease !important;
      cursor: pointer !important;
      z-index: 10 !important;
    }

    .flatpickr-months .flatpickr-prev-month {
      left: 6px !important;
    }

    .flatpickr-months .flatpickr-next-month {
      right: 6px !important;
    }

    .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-months .flatpickr-next-month:hover {
      background: #E60049 !important;
      color: #FFFFFF !important;
      border-color: #E60049 !important;
    }

    .flatpickr-months .flatpickr-prev-month svg,
    .flatpickr-months .flatpickr-next-month svg {
      width: 14px !important;
      height: 14px !important;
      fill: currentColor !important;
      stroke: currentColor !important;
      stroke-width: 1px !important;
    }

    /* Weekday Headers */
    .flatpickr-weekdays {
      margin-top: 6px !important;
      height: 30px !important;
    }

    span.flatpickr-weekday {
      color: #8E7B6D !important;
      font-weight: 800 !important;
      font-size: 11px !important;
      letter-spacing: 0.05em !important;
    }

    /* Days Grid Layout (7 columns, guaranteed fit with zero cut off) */
    .flatpickr-innerContainer {
      width: 100% !important;
      justify-content: center !important;
      overflow: visible !important;
    }

    .flatpickr-rContainer {
      width: 100% !important;
    }

    .flatpickr-days {
      width: 100% !important;
    }

    .dayContainer {
      width: 100% !important;
      min-width: 100% !important;
      max-width: 100% !important;
      display: grid !important;
      grid-template-columns: repeat(7, 1fr) !important;
      gap: 3px !important;
      padding: 4px 0 !important;
    }

    .flatpickr-day {
      width: auto !important;
      max-width: none !important;
      height: 38px !important;
      line-height: 36px !important;
      margin: 0 !important;
      border-radius: 10px !important;
      font-weight: 700 !important;
      font-size: 13px !important;
      color: #3B2314 !important;
      border: 1px solid transparent !important;
      transition: all 0.15s ease !important;
    }

    .flatpickr-day:hover {
      background: #FFF0F3 !important;
      border-color: #E60049 !important;
      color: #E60049 !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.selected:hover {
      background: #E60049 !important;
      border-color: #E60049 !important;
      color: #FFFFFF !important;
      font-weight: 800 !important;
      box-shadow: 0 4px 14px rgba(230,0,73,0.35) !important;
    }

    .flatpickr-day.today {
      border-color: #F3A833 !important;
      background: #FFF8F1 !important;
      color: #3B2314 !important;
      font-weight: 800 !important;
    }

    /* Disabled Dates (Past or Booked) */
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.flatpickr-disabled:hover {
      color: #C0B4AB !important;
      background: #F9F6F3 !important;
      cursor: not-allowed !important;
      border-color: transparent !important;
      opacity: 0.65 !important;
      text-decoration: line-through !important;
    }

    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
      color: #D3C7BD !important;
    }

    .shine {
      position: relative;
      overflow: hidden;
    }

    .shine::after {
      content: "";
      position: absolute;
      inset: 0;
      transform: translateX(-110%);
      background: linear-gradient(110deg, transparent 35%, rgba(255,255,255,.22), transparent 65%);
      transition: transform .7s ease;
      pointer-events: none;
    }

    .shine:hover::after {
      transform: translateX(110%);
    }

    @media (prefers-reduced-motion: reduce) {
      .reveal, .payment-option, .shine::after, .input-scl {
        transition: none;
      }
    }
  </style>
</head>

<body class="text-[#3B2314] font-sans antialiased overflow-x-hidden">

  <!-- 1. BAR LOADING -->
  <div id="page-loader"
       class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#3B2314] via-[#E60049] to-[#F3A833] z-[100] transition-all duration-500 ease-out">
  </div>

  <!-- 2. NAVBAR -->
  @include('frontend.navbar')

  <!-- 3. STICKY STEPPER NAVIGASI -->
  <div class="sticky top-[64px] sm:top-[80px] z-30 bg-[#FFF8F1]/95 backdrop-blur-md border-b border-[#E9DDD2] py-3 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between gap-2">

        <button type="button" onclick="scrollToSection('sec-data-diri')" id="step-btn-1"
          class="step-btn flex items-center gap-2 text-left transition-all shrink-0">
          <span class="w-9 h-9 rounded-2xl bg-[#E60049] text-white flex items-center justify-center text-xs font-black shadow-sm">1</span>
          <span class="hidden sm:block">
            <span class="block text-[10px] uppercase tracking-wider font-black text-[#E60049]">Langkah 01</span>
            <span class="block text-xs font-extrabold text-[#3B2314]">Informasi Penyewa</span>
          </span>
        </button>

        <div class="flex-1 h-px bg-[#E9DDD2] max-w-[70px]"></div>

        <button type="button" onclick="scrollToSection('sec-durasi')" id="step-btn-2"
          class="step-btn flex items-center gap-2 text-left transition-all shrink-0">
          <span class="w-9 h-9 rounded-2xl bg-[#E9DDD2] text-[#7B6759] flex items-center justify-center text-xs font-black">2</span>
          <span class="hidden sm:block">
            <span class="block text-[10px] uppercase tracking-wider font-black text-[#8E7B6D]">Langkah 02</span>
            <span class="block text-xs font-bold text-[#7B6759]">Durasi & Tanggal</span>
          </span>
        </button>

        <div class="flex-1 h-px bg-[#E9DDD2] max-w-[70px]"></div>

        <button type="button" onclick="scrollToSection('sec-pembayaran')" id="step-btn-3"
          class="step-btn flex items-center gap-2 text-left transition-all shrink-0">
          <span class="w-9 h-9 rounded-2xl bg-[#E9DDD2] text-[#7B6759] flex items-center justify-center text-xs font-black">3</span>
          <span class="hidden sm:block">
            <span class="block text-[10px] uppercase tracking-wider font-black text-[#8E7B6D]">Langkah 03</span>
            <span class="block text-xs font-bold text-[#7B6759]">Pembayaran</span>
          </span>
        </button>

      </div>
    </div>
  </div>

  <!-- MAIN CONTENT CONTAINER -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

    @php
      $namaKosan = $kamar->productKosan->title ?? 'Sinar Citra Lestari';

      $bookedRanges = [];
      $latestBookedEnd = null;
      if ($kamar && $kamar->tamu) {
          foreach ($kamar->tamu as $t) {
              if (in_array(strtolower($t->status), ['approved', 'pending']) && $t->start_date && $t->end_date) {
                  $sDate = \Carbon\Carbon::parse($t->start_date)->format('Y-m-d');
                  $eDate = \Carbon\Carbon::parse($t->end_date)->format('Y-m-d');
                  $bookedRanges[] = [
                      'from' => $sDate,
                      'to' => $eDate,
                  ];
                  if (!$latestBookedEnd || $eDate > $latestBookedEnd) {
                      $latestBookedEnd = $eDate;
                  }
              }
          }
      }

      $todayStr = now()->format('Y-m-d');
      $isTodayBooked = false;
      foreach ($bookedRanges as $r) {
          if ($todayStr >= $r['from'] && $todayStr <= $r['to']) {
              $isTodayBooked = true;
              break;
          }
      }

      $firstAvailableDate = $todayStr;
      if ($isTodayBooked && $latestBookedEnd) {
          $firstAvailableDate = \Carbon\Carbon::parse($latestBookedEnd)->addDay()->format('Y-m-d');
      }
    @endphp

    <!-- BREADCRUMB -->
    <nav class="reveal mb-7" aria-label="Breadcrumb">
      <div class="inline-flex flex-wrap items-center gap-2 bg-white/90 border border-[#E9DDD2] rounded-full px-4 py-2.5 shadow-sm text-[11px] sm:text-xs">
        <a href="{{ route('kosan.index') }}" class="font-bold hover:text-[#E60049] transition-colors">Kos-Kosan</a>
        <i class="fa-solid fa-minus text-[#F3A833] text-[9px]"></i>
        @if($kamar->productKosan)
          <a href="{{ route('kosan.detail', $kamar->productKosan->slug) }}" class="font-semibold text-[#00A896] hover:text-[#E60049] transition-colors">{{ $namaKosan }}</a>
          <i class="fa-solid fa-minus text-[#F3A833] text-[9px]"></i>
        @endif
        <a href="{{ route('kamar.detail', $kamar->id) }}" class="font-semibold hover:text-[#E60049] transition-colors">{{ $kamar->room }}</a>
        <i class="fa-solid fa-minus text-[#F3A833] text-[9px]"></i>
        <span class="font-black">Booking Kamar</span>
      </div>
    </nav>

    <!-- HEADER TITLE -->
    <header class="reveal mb-10 grid lg:grid-cols-[1fr_auto] gap-6 items-end">
      <div>
        <span class="inline-flex items-center gap-2 text-[10px] uppercase tracking-[.2em] font-black text-[#E60049]">
          <span class="w-8 h-8 rounded-xl bg-[#E60049]/10 flex items-center justify-center">
            <i class="fa-solid fa-key"></i>
          </span>
          Reservasi Kamar
        </span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight mt-4 text-[#3B2314]">
          Amankan kamar pilihanmu.
        </h1>
        <p class="text-sm sm:text-base text-[#7B6759] mt-3 max-w-2xl leading-7">
          Isi informasi penyewa, tentukan durasi, lalu pilih metode pembayaran. Ringkasan biaya akan diperbarui otomatis.
        </p>
      </div>

      <div class="hidden sm:flex items-center gap-3 rounded-2xl bg-[#3B2314] text-white px-5 py-4 shadow-lg">
        <span class="w-10 h-10 rounded-xl bg-[#F3A833] text-[#3B2314] flex items-center justify-center">
          <i class="fa-solid fa-house"></i>
        </span>
        <div>
          <p class="text-[10px] uppercase tracking-wider text-white/50 font-bold">Kamar dipilih</p>
          <p class="text-sm font-black">{{ $kamar->room }}</p>
        </div>
      </div>
    </header>

    <!-- FORM UTAMA -->
    <form id="booking-form" action="{{ route('tamu.booking', $kamar->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="total_price" id="input-hidden-total-price" value="0">

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 lg:gap-10 items-start">

        <!-- KOLOM FORM (3 SECTION) -->
        <div class="lg:col-span-8 space-y-7">

          <!-- SECTION 1: DATA DIRI -->
          <section id="sec-data-diri" class="booking-card rounded-[2rem] overflow-hidden reveal">
            <div class="bg-[#3B2314] text-white p-6 sm:p-8">
              <div class="flex items-center gap-4">
                <span class="w-12 h-12 rounded-2xl bg-[#F3A833] text-[#3B2314] flex items-center justify-center font-black text-lg">1</span>
                <div>
                  <p class="text-[10px] uppercase tracking-[.18em] font-black text-[#F3A833]">Data utama</p>
                  <h2 class="text-xl sm:text-2xl font-black">Informasi Penyewa</h2>
                </div>
              </div>
            </div>

            <div class="p-6 sm:p-8 space-y-5">
              <div>
                <label class="block text-xs font-bold text-[#5D483A] mb-2">Tipe Unit Kamar Terpilih</label>
                <div class="flex items-center gap-3 rounded-2xl bg-[#FFF8F1] border border-[#E9DDD2] px-4 py-3">
                  <i class="fa-solid fa-bed text-[#E60049]"></i>
                  <input type="text" value="{{ $kamar->room }} - {{ $namaKosan }}" readonly
                    class="w-full bg-transparent font-bold text-[#3B2314] focus:outline-none cursor-not-allowed">
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label class="block text-xs font-bold text-[#5D483A] mb-2">Nama Lengkap (sesuai KTP) <span class="text-[#E60049]">*</span></label>
                  <input type="text" name="name" id="input-nama" required placeholder="Contoh: Budi Santoso"
                    class="input-scl w-full px-4 py-3.5 bg-white border border-[#E9DDD2] rounded-2xl font-medium text-[#3B2314]">
                </div>

                <div>
                  <label class="block text-xs font-bold text-[#5D483A] mb-2">Nomor WhatsApp / HP <span class="text-[#E60049]">*</span></label>
                  <input type="tel" name="telp" id="input-wa" required placeholder="08123456XXXX"
                    class="input-scl w-full px-4 py-3.5 bg-white border border-[#E9DDD2] rounded-2xl font-medium text-[#3B2314]">
                  <p class="text-[10px] text-[#9A887A] mt-2">Gunakan format biasa, misalnya <code>081234567890</code>.</p>
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-[#5D483A] mb-2">Email Aktif (untuk penerimaan PDF Booking) <span class="text-[#E60049]">*</span></label>
                <input type="email" name="email" id="input-email" required placeholder="budi@example.com"
                  class="input-scl w-full px-4 py-3.5 bg-white border border-[#E9DDD2] rounded-2xl font-medium text-[#3B2314]">
                <p class="text-[10px] text-[#9A887A] mt-2">E-tiket dan invoice PDF akan dikirimkan otomatis ke alamat email ini.</p>
              </div>
            </div>
          </section>

          <!-- SECTION 2: DURASI & TANGGAL -->
          <section id="sec-durasi" class="booking-card rounded-[2rem] p-6 sm:p-8 reveal">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-7">
              <div>
                <span class="text-[10px] uppercase tracking-[.18em] font-black text-[#00A896]">Langkah berikutnya</span>
                <h2 class="text-2xl sm:text-3xl font-black mt-2">Durasi & tanggal masuk</h2>
              </div>
              <span class="w-11 h-11 rounded-2xl bg-[#00A896]/10 text-[#00A896] flex items-center justify-center">
                <i class="fa-solid fa-calendar-days"></i>
              </span>
            </div>

            <div class="space-y-6 text-xs sm:text-sm">
              <div>
                <span class="block font-bold text-[#5D483A] mb-3">Pilih Durasi Sewa <span class="text-[#E60049]">*</span></span>

                <input type="hidden" id="durasi-jam" name="jam" value="0">
                <input type="hidden" id="durasi-hari" name="hari" value="0">
                <input type="hidden" id="durasi-minggu" name="minggu" value="0">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div class="rounded-2xl border-2 border-[#F3A833] bg-[#FFF8F1] p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                      <label for="durasi-bulan" class="font-black text-[#3B2314] cursor-pointer flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-[#F3A833]/20 flex items-center justify-center">
                          <i class="fa-solid fa-calendar text-[#3B2314]"></i>
                        </span>
                        Durasi Bulanan
                      </label>
                      <span class="text-[9px] font-black text-[#3B2314] bg-[#F3A833]/30 px-2 py-1 rounded-full">PER BULAN</span>
                    </div>
                    <div class="relative">
                      <input type="number" id="durasi-bulan" name="bulan" value="1" min="0"
                        class="input-scl w-full pl-4 pr-16 py-3 bg-white border border-[#E9DDD2] rounded-xl font-black text-lg text-[#3B2314]">
                      <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-[#9A887A] pointer-events-none">Bulan</span>
                    </div>
                  </div>

                  <div class="rounded-2xl border border-[#E9DDD2] bg-white p-4 hover:border-[#00A896] transition-all">
                    <div class="flex items-center justify-between mb-3">
                      <label for="durasi-tahun" class="font-black text-[#3B2314] cursor-pointer flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-[#00A896]/10 flex items-center justify-center">
                          <i class="fa-solid fa-calendar-days text-[#00A896]"></i>
                        </span>
                        Durasi Tahunan
                      </label>
                      <span class="text-[9px] font-black text-[#00A896] bg-[#00A896]/10 px-2 py-1 rounded-full">PER TAHUN</span>
                    </div>
                    <div class="relative">
                      <input type="number" id="durasi-tahun" name="tahun" value="0" min="0"
                        class="input-scl w-full pl-4 pr-16 py-3 bg-white border border-[#E9DDD2] rounded-xl font-black text-lg text-[#3B2314]">
                      <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-[#9A887A] pointer-events-none">Tahun</span>
                    </div>
                  </div>
                </div>

                <p class="mt-3 text-[10px] text-[#8E7B6D] flex items-start gap-2">
                  <i class="fa-solid fa-circle-info text-[#E60049] mt-0.5"></i>
                  <span>Sewa kos ini khusus bulanan atau tahunan. Masukkan durasi yang diinginkan (minimal 1 bulan atau 1 tahun).</span>
                </p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <div class="flex items-center justify-between mb-2">
                    <label class="block font-bold text-[#5D483A]">Tanggal Mulai Masuk <span class="text-[#E60049]">*</span></label>
                    @if($isTodayBooked && $latestBookedEnd)
                      <span class="text-[10px] font-extrabold text-[#E60049] bg-rose-50 border border-rose-200/80 px-2 py-0.5 rounded-md">
                        Ter-booking s/d {{ \Carbon\Carbon::parse($latestBookedEnd)->locale('id')->isoFormat('D MMM Y') }}
                      </span>
                    @endif
                  </div>

                  @if($isTodayBooked && $latestBookedEnd)
                    <div class="mb-3 p-3.5 rounded-2xl bg-[#FFF8F1] border-2 border-[#F3A833] shadow-sm flex items-start gap-3 text-xs text-[#3B2314] leading-relaxed">
                      <span class="w-8 h-8 rounded-xl bg-[#F3A833]/20 text-[#D97706] flex items-center justify-center shrink-0 text-sm mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                      </span>
                      <div class="flex-1">
                        <span class="font-black text-[#3B2314] block">Kamar sedang terisi saat ini.</span>
                        <p class="text-[11px] text-[#7B6759] mt-0.5">
                          Tersedia mulai: <strong class="text-[#E60049]">{{ \Carbon\Carbon::parse($firstAvailableDate)->locale('id')->isoFormat('D MMMM Y') }}</strong>
                        </p>
                        <button
                          type="button"
                          onclick="selectQuickDate('{{ $firstAvailableDate }}')"
                          style="background-color: #E60049; color: #FFFFFF;"
                          class="mt-2 inline-flex items-center gap-1.5 px-3.5 py-2 hover:bg-[#C90040] text-white rounded-xl font-black text-xs shadow-md transition-all active:scale-95 cursor-pointer"
                        >
                          <i class="fa-solid fa-calendar-check text-[#F3A833]"></i>
                          <span>Pilih Cepat ({{ \Carbon\Carbon::parse($firstAvailableDate)->locale('id')->isoFormat('D MMMM Y') }})</span>
                        </button>
                      </div>
                    </div>
                  @endif

                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#E60049] z-10">
                      <i class="fa-solid fa-calendar-day"></i>
                    </div>
                    <input type="text" id="input-tanggal" name="start_date" required placeholder="Pilih tanggal mulai masuk..."
                      class="input-scl w-full pl-11 pr-10 py-3.5 bg-white border border-[#E9DDD2] rounded-2xl font-bold text-[#3B2314] cursor-pointer">
                    <button type="button" onclick="if(fpInstance) fpInstance.toggle()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#8E7B6D] hover:text-[#E60049] transition-colors cursor-pointer z-10" title="Buka/Tutup Kalender">
                      <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                  </div>

                  <!-- Legend Status Kalender -->
                  <div class="mt-2.5 flex items-center gap-3 text-[10px] text-[#7B6759]">
                    <span class="flex items-center gap-1.5 font-bold">
                      <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                      <span>Bisa Dipilih</span>
                    </span>
                    <span class="flex items-center gap-1.5 font-bold text-rose-700">
                      <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                      <span>Terisi / Booked</span>
                    </span>
                    <span class="text-[#9A887A] ml-auto">Bisa digeser ↔</span>
                  </div>
                </div>

                <div>
                  <label class="block font-bold text-[#5D483A] mb-2">Perkiraan Jam Check-in <span class="text-[#E60049]">*</span></label>
                  <div class="relative">
                    <i class="fa-solid fa-clock absolute left-4 top-1/2 -translate-y-1/2 text-[#00A896] pointer-events-none"></i>
                    <input type="time" id="input-jam" name="start_time" value="13:00" required
                      class="input-scl w-full pl-11 pr-4 py-3.5 bg-white border border-[#E9DDD2] rounded-2xl font-medium text-[#3B2314]">
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- SECTION 3: PEMBAYARAN -->
          <section id="sec-pembayaran" class="booking-card rounded-[2rem] overflow-hidden reveal">
            <div class="p-6 sm:p-8 border-b border-[#E9DDD2]">
              <span class="text-[10px] uppercase tracking-[.18em] font-black text-[#E60049]">Langkah terakhir</span>
              <div class="flex items-center justify-between gap-4 mt-2">
                <div>
                  <h2 class="text-2xl sm:text-3xl font-black">Opsi & metode pembayaran</h2>
                  <p class="text-xs text-[#8E7B6D] mt-2">Pilih cara pembayaran yang paling sesuai.</p>
                </div>
                <span class="hidden sm:flex w-12 h-12 rounded-2xl bg-[#E60049]/10 text-[#E60049] items-center justify-center">
                  <i class="fa-solid fa-wallet"></i>
                </span>
              </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <button type="button" onclick="selectPaymentOption('transfer')" id="opt-transfer"
                  class="payment-option p-5 rounded-2xl border-2 border-[#E60049] bg-[#E60049]/5 text-left shadow-sm">
                  <span class="flex items-center justify-between gap-3">
                    <span class="font-black text-sm text-[#3B2314]">TRANSFER ONLINE</span>
                    <i class="fa-solid fa-circle-check text-[#E60049]"></i>
                  </span>
                  <span class="block text-[11px] text-[#7B6759] mt-2">QRIS / Rekening Bank</span>
                </button>

                <button type="button" onclick="selectPaymentOption('cod')" id="opt-cod"
                  class="payment-option p-5 rounded-2xl border border-[#E9DDD2] bg-white text-left">
                  <span class="flex items-center justify-between gap-3">
                    <span class="font-black text-sm text-[#3B2314]">BAYAR DI TEMPAT (COD)</span>
                    <i class="fa-regular fa-circle text-[#9A887A]"></i>
                  </span>
                  <span class="block text-[11px] text-[#7B6759] mt-2">Bayar saat check-in</span>
                </button>
              </div>

              <div id="transfer-method-container" class="space-y-5">
                <div>
                  <input type="hidden" name="payment_method" id="input-payment-method" value="qris">
                  <label class="block font-bold text-xs sm:text-sm text-[#5D483A] mb-2">Pilih Rekening Tujuan</label>
                  <select id="select-bank" onchange="updateSelectedBank(this.value); toggleQrisDisplay();"
                    class="input-scl w-full px-4 py-3.5 bg-white border border-[#E9DDD2] rounded-2xl font-medium text-[#3B2314]">
                    <option value="qris">QRIS (Semua Bank & E-Wallet: BCA, Mandiri, Dana, GoPay)</option>
                    <option value="bca">BCA: 1234567890 a/n Sinar Citra Lestari</option>
                    <option value="mandiri">Mandiri: 0987654321 a/n Sinar Citra Lestari</option>
                    <option value="bni">BNI: 1111111111 a/n Sinar Citra Lestari</option>
                  </select>
                </div>

                <!-- BLOK QRIS -->
                <div id="qris-display" class="rounded-3xl bg-[#3B2314] p-5 sm:p-7 text-center text-white">
                  <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="shrink-0">
                      <p class="text-[10px] uppercase tracking-widest text-[#F3A833] font-black mb-3">Pembayaran QRIS</p>
                      <div class="p-3 bg-white rounded-2xl shadow-xl">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=SCL-Sewa-Kosan"
                          alt="Kode QRIS Pembayaran" class="w-40 h-40 object-contain">
                      </div>
                    </div>
                    <div class="text-left">
                      <h3 class="text-xl font-black">Scan & selesaikan pembayaran</h3>
                      <p class="text-xs leading-6 text-white/65 mt-2">
                        Buka aplikasi m-Banking atau e-Wallet favorit Anda, pilih menu QRIS / Scan, lalu selesaikan pembayaran sesuai total tagihan.
                      </p>
                      <span class="inline-flex items-center gap-2 mt-4 px-3 py-2 rounded-full bg-[#00A896]/15 text-[#6DE2D5] text-[10px] font-bold">
                        <i class="fa-solid fa-shield-halved"></i> Pembayaran aman
                      </span>
                    </div>
                  </div>
                </div>

                <!-- BLOK BANK TRANSFER -->
                <div id="bank-transfer-card" class="hidden rounded-3xl bg-[#3B2314] p-5 sm:p-7 text-white space-y-4 shadow-lg border border-white/10">
                  <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-2xl bg-white text-[#3B2314] font-black flex items-center justify-center text-xs tracking-wider uppercase shadow-md" id="bank-badge-name">
                        BCA
                      </div>
                      <div>
                        <h3 class="text-sm sm:text-base font-black text-white" id="bank-name-label">Transfer Bank BCA</h3>
                        <p class="text-[11px] text-white/60">Verifikasi otomatis & konfirmasi instan</p>
                      </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-[#00A896]/20 text-[#6DE2D5] text-[10px] font-bold">
                      <i class="fa-solid fa-circle-check mr-1"></i> Aktif
                    </span>
                  </div>

                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                      <span class="text-[10px] uppercase font-bold tracking-wider text-white/50 block">Nomor Rekening Tujuan</span>
                      <div class="flex items-center justify-between gap-2 mt-1">
                        <span id="bank-account-number" class="text-base sm:text-lg font-black tracking-wider text-[#F3A833]">1234567890</span>
                        <button
                          type="button"
                          id="btn-copy-rekening"
                          onclick="copyToClipboard(document.getElementById('bank-account-number').textContent, 'btn-copy-rekening', 'Tersalin! ✅')"
                          class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-[11px] font-bold text-white transition-all active:scale-95 flex items-center gap-1 shrink-0"
                          title="Salin Nomor Rekening"
                        >
                          <i class="fa-regular fa-copy text-[10px]"></i>
                          <span>Salin</span>
                        </button>
                      </div>
                      <span class="text-[10px] text-white/60 block mt-1">a/n PT Sinar Citra Lestari</span>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                      <span class="text-[10px] uppercase font-bold tracking-wider text-white/50 block">Nominal Harus Ditransfer</span>
                      <div class="flex items-center justify-between gap-2 mt-1">
                        <span id="bank-nominal-transfer" class="text-base sm:text-lg font-black text-[#F3A833]">Rp 0</span>
                        <button
                          type="button"
                          id="btn-copy-nominal"
                          onclick="copyNominalTransfer()"
                          class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-[11px] font-bold text-white transition-all active:scale-95 flex items-center gap-1 shrink-0"
                          title="Salin Nominal Transfer"
                        >
                          <i class="fa-regular fa-copy text-[10px]"></i>
                          <span>Salin</span>
                        </button>
                      </div>
                      <span class="text-[10px] text-white/60 block mt-1">Transfer tepat sesuai angka di atas</span>
                    </div>
                  </div>

                  <div class="p-3 bg-white/5 rounded-2xl border border-white/10 flex items-start gap-2.5 text-[11px] text-white/70">
                    <i class="fa-solid fa-circle-info text-[#F3A833] mt-0.5 shrink-0"></i>
                    <span>Setelah transfer berhasil, simpan dan lampirkan bukti transfer/struk di bawah ini untuk verifikasi admin yang lebih cepat.</span>
                  </div>
                </div>

                <div>
                  <label class="block font-bold text-xs sm:text-sm text-[#5D483A] mb-2">Unggah Bukti Transfer / Resi <span class="text-[#E60049]">*</span></label>
                  <div onclick="document.getElementById('file-bukti').click()"
                    class="border-2 border-dashed border-[#D8C9BC] hover:border-[#E60049] rounded-3xl p-7 text-center bg-[#FFF8F1]/70 hover:bg-[#E60049]/5 transition-all cursor-pointer group">
                    <input type="file" id="file-bukti" name="proof_of_transfer" accept="image/*" class="hidden" onchange="handleFileUpload(event)">

                    <div id="upload-placeholder" class="space-y-2">
                      <span class="mx-auto w-14 h-14 rounded-2xl bg-[#F3A833]/15 text-[#3B2314] flex items-center justify-center">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                      </span>
                      <p class="text-sm font-black text-[#3B2314]">Klik untuk upload bukti pembayaran</p>
                      <p class="text-[10px] text-[#9A887A]">JPG, PNG, WEBP · Maksimal 5MB</p>
                    </div>

                    <div id="upload-preview" class="hidden flex-col items-center justify-center space-y-2">
                      <img id="preview-img" src="" alt="Bukti Transfer" class="h-32 rounded-xl object-contain border border-[#E9DDD2] shadow-sm">
                      <span id="preview-filename" class="text-xs font-semibold text-[#3B2314] truncate max-w-full block"></span>
                      <span class="text-[10px] text-[#00A896] font-bold bg-[#00A896]/10 px-3 py-1 rounded-full">
                        <i class="fa-solid fa-check mr-1"></i> File Siap Diunggah
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div id="cod-method-container" class="hidden rounded-3xl bg-[#FFF3D9] border border-[#F3A833]/50 p-5 text-[#5C3D14] text-xs leading-relaxed">
                <span class="font-black block mb-2">
                  <i class="fa-solid fa-circle-info text-[#E60049] mr-1"></i> Informasi Pembayaran Tunai
                </span>
                <p>Pembayaran dilakukan secara langsung kepada pengelola kos saat serah terima kunci di lokasi. Pastikan nomor kontak aktif untuk konfirmasi.</p>
              </div>
            </div>
          </section>

        </div>

        <!-- KOLOM SIDEBAR RINGKASAN -->
        <aside class="lg:col-span-4 lg:sticky lg:top-[140px] reveal">
          @php
            $imgKamar = $kamar->productKamarImageKosan->first();
            $previewKamarImg = $imgKamar
              ? asset('storage/' . $imgKamar->image)
              : 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=300&q=80';
          @endphp

          <div class="rounded-[2rem] bg-[#3B2314] p-2 shadow-[0_25px_60px_rgba(59,35,20,.16)]">
            <div class="rounded-[1.7rem] bg-white overflow-hidden">

              <div class="relative h-52">
                <img src="{{ $previewKamarImg }}" alt="{{ $kamar->room }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#3B2314]/85 via-transparent to-transparent"></div>
                <div class="absolute left-5 right-5 bottom-5 text-white">
                  <p class="text-[10px] uppercase tracking-widest text-[#F3A833] font-black">Ringkasan Booking</p>
                  <h2 class="text-2xl font-black mt-1">{{ $kamar->room }}</h2>
                  <p class="text-xs text-white/75 mt-1">
                    <i class="fa-solid fa-location-dot text-[#F3A833] mr-1"></i>
                    {{ $kamar->productKosan->wilayah ?? 'Lokasi Terdaftar' }}
                  </p>
                </div>
              </div>

              <div class="p-5 sm:p-6 space-y-5">
                <div class="rounded-2xl bg-[#FFF8F1] border border-[#E9DDD2] p-4">
                  <div class="flex items-center justify-between gap-3">
                    <span class="text-[10px] uppercase tracking-wider font-black text-[#8E7B6D]">Durasi</span>
                    <span id="summary-durasi-text" class="text-xs font-black text-[#E60049]">1 Bulan</span>
                  </div>
                </div>

                <div class="space-y-3 text-xs sm:text-sm">
                  <div class="flex justify-between gap-4 text-[#7B6759]">
                    <span>Subtotal Sewa</span>
                    <span id="summary-subtotal" class="font-black text-[#3B2314]">Rp 0</span>
                  </div>

                  <div id="summary-discount-row" class="flex justify-between gap-4 hidden">
                    <span class="text-[#00A896] font-bold">Diskon Promo</span>
                    <span id="summary-discount-amount" class="font-black text-[#00A896]">- Rp 0</span>
                  </div>

                  <div class="flex justify-between gap-4 text-[#7B6759]">
                    <span>PPN (12%)</span>
                    <span id="summary-ppn" class="font-black text-[#3B2314]">+ Rp 0</span>
                  </div>
                </div>

                <div class="h-px bg-[#E9DDD2]"></div>

                <div class="rounded-2xl bg-[#3B2314] p-5 text-white">
                  <span class="text-[10px] uppercase tracking-widest text-white/50 font-black">Total Pembayaran</span>
                  <div id="summary-grand-total" class="text-2xl sm:text-3xl font-black text-[#F3A833] mt-1">Rp 0</div>
                </div>

                <!-- Tombol Submit Form -->
                <button id="btn-submit-booking" type="submit"
                  class="shine w-full py-4 bg-[#E60049] hover:bg-[#C90040] text-white font-black rounded-2xl transition-all shadow-lg flex items-center justify-center gap-2 active:scale-[.98] text-xs sm:text-sm">
                  <span>Konfirmasi & Selesaikan Booking</span>
                  <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>

                <div class="p-4 rounded-2xl bg-[#00A896]/8 border border-[#00A896]/20 flex items-start gap-3 text-[10px] text-[#5D483A] leading-relaxed">
                  <i class="fa-solid fa-shield-halved text-[#00A896] text-sm mt-0.5 shrink-0"></i>
                  <span>Data Anda dilindungi dengan enkripsi aman. Invoice & Tiket PDF diterbitkan instan pasca-transaksi.</span>
                </div>
              </div>
            </div>
          </div>
        </aside>

      </div>
    </form>

  </main>

  <!-- 4. MODAL POP-UP FAILED BOOKING -->
  @if (session('failed'))
    <div id="flash-failed-modal" class="fixed inset-0 bg-[#3B2314]/70 backdrop-blur-sm z-[999] flex items-center justify-center p-4">
      <div class="bg-white w-full max-w-md rounded-[2rem] p-6 sm:p-8 shadow-2xl relative space-y-5 border border-[#E9DDD2]">
        <button type="button" onclick="closeFailedModal()"
          class="absolute top-5 right-5 w-9 h-9 rounded-full bg-[#FFF8F1] flex items-center justify-center text-[#8E7B6D] hover:bg-[#E9DDD2] transition-colors">
          <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="text-center">
          <div class="w-16 h-16 rounded-2xl bg-[#E60049]/10 text-[#E60049] flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-circle-xmark text-3xl"></i>
          </div>
          <h3 class="text-2xl font-black text-[#3B2314]">Booking Gagal</h3>
          <p class="text-xs sm:text-sm text-[#7B6759] mt-2 leading-6">{{ session('failed') }}</p>
        </div>

        <button type="button" onclick="closeFailedModal()"
          class="w-full py-3.5 bg-[#E60049] hover:bg-[#C90040] text-white font-black rounded-2xl transition-all shadow-md active:scale-95 text-xs sm:text-sm">
          Coba Lagi
        </button>
      </div>
    </div>
  @endif

  @include('frontend.footer')

  <!-- Flatpickr JS & Locale ID -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

  <script>
    let currentPaymentType = 'transfer';
    let uploadedFileBase64 = '';
    let fpInstance = null;

    const kamarPriceData = @json($kamar->priceKamar);
    const cumulativeDiscountVal = parseFloat("{{ $kamar->cumulative_discount ?? 0 }}") || 0;
    const bookedRanges = @json($bookedRanges);
    const firstAvailableDateStr = "{{ $firstAvailableDate }}";

    function selectQuickDate(dateStr) {
      if (fpInstance) {
        fpInstance.setDate(dateStr, true);
        fpInstance.jumpToDate(dateStr);
      }
    }

    document.addEventListener("DOMContentLoaded", function() {
      // Initialize Flatpickr with preloaded booked ranges and swipe gestures
      fpInstance = flatpickr("#input-tanggal", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        minDate: "today",
        locale: "id",
        disable: bookedRanges,
        defaultDate: null,
        appendTo: document.body,
        position: "auto left",
        onReady: function(selectedDates, dateStr, instance) {
          if (firstAvailableDateStr) {
            instance.jumpToDate(firstAvailableDateStr);
          }
          attachFlatpickrSwipe(instance);
        },
        onOpen: function(selectedDates, dateStr, instance) {
          if (!instance.selectedDates.length && firstAvailableDateStr) {
            instance.jumpToDate(firstAvailableDateStr);
          }
        }
      });

      // Enable touch swipe / drag gestures between months on mobile & tablet
      function attachFlatpickrSwipe(instance) {
        if (!instance || !instance.calendarContainer) return;
        const container = instance.calendarContainer;
        let startX = 0;
        let endX = 0;

        container.addEventListener('touchstart', function(e) {
          startX = e.changedTouches[0].screenX;
        }, { passive: true });

        container.addEventListener('touchend', function(e) {
          endX = e.changedTouches[0].screenX;
          const deltaX = endX - startX;
          if (Math.abs(deltaX) > 35) {
            if (deltaX < 0) {
              instance.changeMonth(1); // Swipe left -> Next month
            } else {
              instance.changeMonth(-1); // Swipe right -> Previous month
            }
          }
        }, { passive: true });
      }

      const durationInputs = ['durasi-jam', 'durasi-hari', 'durasi-minggu', 'durasi-bulan', 'durasi-tahun'];

      durationInputs.forEach(id => {
        const inputEl = document.getElementById(id);

        if (inputEl) {
          inputEl.addEventListener('input', calculateRealtimePrice);
        }
      });

      calculateRealtimePrice();

      const bookingForm = document.getElementById('booking-form');
      const submitBtn = document.getElementById('btn-submit-booking');

      if (bookingForm && submitBtn) {
        bookingForm.addEventListener('submit', function() {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-sm"></i> <span>Memproses Pesanan...</span>';
        });
      }
    });

    function calculateRealtimePrice() {
      const bulan = parseInt(document.getElementById('durasi-bulan')?.value) || 0;
      const tahun = parseInt(document.getElementById('durasi-tahun')?.value) || 0;

      const durationMap = {
        'bulan': bulan,
        'tahun': tahun
      };

      const filledCategories = Object.keys(durationMap).filter(cat => durationMap[cat] > 0);
      const isSingleInput = filledCategories.length === 1;
      const isMultipleInputs = filledCategories.length > 1;

      let totalBasePrice = 0;
      let totalDiscountAmount = 0;
      let durationTexts = [];

      function getCategoryPriceAndDiscount(categoryName) {
        if (!kamarPriceData || !Array.isArray(kamarPriceData)) {
          return { price: 0, discount: 0 };
        }

        const key = categoryName.toLowerCase().trim();

        const found = kamarPriceData.find(item => {
          const itemCat = (item.kategori || '').toLowerCase().trim();
          return itemCat === key || itemCat.startsWith(key) || key.startsWith(itemCat);
        });

        if (found) {
          return {
            price: parseFloat(found.price) || 0,
            discount: parseFloat(found.discount) || 0
          };
        }

        return { price: 0, discount: 0 };
      }

      Object.keys(durationMap).forEach(cat => {
        const qty = durationMap[cat];

        if (qty > 0) {
          let label = cat.charAt(0).toUpperCase() + cat.slice(1);
          durationTexts.push(`${qty} ${label}`);

          const pInfo = getCategoryPriceAndDiscount(cat);
          const categorySubtotal = pInfo.price * qty;

          totalBasePrice += categorySubtotal;

          if (isSingleInput) {
            let discVal = pInfo.discount;
            if (discVal <= 0 && cat === 'bulan') {
              discVal = cumulativeDiscountVal;
            }

            if (discVal > 0) {
              if (discVal <= 100) {
                totalDiscountAmount = (categorySubtotal * discVal) / 100;
              } else {
                totalDiscountAmount = discVal * qty;
              }
            }
          }
        }
      });

      if (isMultipleInputs && cumulativeDiscountVal > 0) {
        if (cumulativeDiscountVal <= 100) {
          totalDiscountAmount = (totalBasePrice * cumulativeDiscountVal) / 100;
        } else {
          totalDiscountAmount = cumulativeDiscountVal;
        }
      }

      if (totalDiscountAmount > totalBasePrice) {
        totalDiscountAmount = totalBasePrice;
      }

      const priceAfterDiscount = totalBasePrice - totalDiscountAmount;
      const ppnAmount = priceAfterDiscount * 0.12;
      const grandTotal = priceAfterDiscount + ppnAmount;

      const durasiTextElem = document.getElementById('summary-durasi-text');
      const subtotalElem = document.getElementById('summary-subtotal');
      const ppnElem = document.getElementById('summary-ppn');
      const grandTotalElem = document.getElementById('summary-grand-total');

      if (durasiTextElem) {
        durasiTextElem.textContent = durationTexts.length > 0 ? durationTexts.join(', ') : '0 Durasi';
      }

      if (subtotalElem) {
        subtotalElem.textContent = formatRupiah(totalBasePrice);
      }

      const discountRow = document.getElementById('summary-discount-row');
      const discountAmountText = document.getElementById('summary-discount-amount');

      if (discountRow) {
        if (totalDiscountAmount > 0) {
          discountRow.classList.remove('hidden');

          if (discountAmountText) {
            discountAmountText.textContent = `- ${formatRupiah(totalDiscountAmount)}`;
          }
        } else {
          discountRow.classList.add('hidden');
        }
      }

      if (ppnElem) {
        ppnElem.textContent = `+ ${formatRupiah(ppnAmount)}`;
      }

      if (grandTotalElem) {
        grandTotalElem.textContent = formatRupiah(grandTotal);
      }

      const bankNominal = document.getElementById('bank-nominal-transfer');
      if (bankNominal) {
        bankNominal.textContent = formatRupiah(grandTotal);
      }

      const hiddenTotalPrice = document.getElementById('input-hidden-total-price');

      if (hiddenTotalPrice) {
        hiddenTotalPrice.value = grandTotal;
      }
    }

    function formatRupiah(number) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
      }).format(number);
    }

    const bankDetails = {
      'bca': { name: 'Bank Central Asia (BCA)', code: 'BCA', no: '1234567890' },
      'mandiri': { name: 'Bank Mandiri', code: 'MDR', no: '0987654321' },
      'bni': { name: 'Bank Negara Indonesia (BNI)', code: 'BNI', no: '1111111111' }
    };

    window.addEventListener('load', () => {
      const loader = document.getElementById('page-loader');

      if (loader) {
        loader.style.width = '100%';

        setTimeout(() => {
          loader.style.opacity = '0';
        }, 400);
      }

      toggleQrisDisplay();
    });

    function toggleQrisDisplay() {
      const selectBank = document.getElementById('select-bank');
      const qrisDisplay = document.getElementById('qris-display');
      const bankCard = document.getElementById('bank-transfer-card');
      const bankBadge = document.getElementById('bank-badge-name');
      const bankNameLabel = document.getElementById('bank-name-label');
      const bankAccNo = document.getElementById('bank-account-number');

      if (!selectBank) return;

      const val = selectBank.value;
      if (val === 'qris') {
        if (qrisDisplay) qrisDisplay.classList.remove('hidden');
        if (bankCard) bankCard.classList.add('hidden');
      } else if (bankDetails[val]) {
        if (qrisDisplay) qrisDisplay.classList.add('hidden');
        if (bankCard) {
          bankCard.classList.remove('hidden');
          if (bankBadge) bankBadge.textContent = bankDetails[val].code;
          if (bankNameLabel) bankNameLabel.textContent = `Transfer ${bankDetails[val].name}`;
          if (bankAccNo) bankAccNo.textContent = bankDetails[val].no;
        }
      }
    }

    function copyToClipboard(text, btnId, successMsg) {
      const cleanText = text.replace(/[^0-9]/g, '') || text;
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(cleanText).then(() => showBtnFeedback(btnId, successMsg)).catch(() => fallbackClipboardCopy(cleanText, btnId, successMsg));
      } else {
        fallbackClipboardCopy(cleanText, btnId, successMsg);
      }
    }

    function copyNominalTransfer() {
      const hiddenPrice = document.getElementById('input-hidden-total-price');
      const nominal = hiddenPrice ? hiddenPrice.value : '0';
      copyToClipboard(nominal, 'btn-copy-nominal', 'Tersalin! ✅');
    }

    function fallbackClipboardCopy(text, btnId, successMsg) {
      const ta = document.createElement("textarea");
      ta.value = text;
      ta.style.position = "fixed";
      ta.style.left = "-999999px";
      document.body.appendChild(ta);
      ta.focus();
      ta.select();
      try {
        document.execCommand('copy');
        showBtnFeedback(btnId, successMsg);
      } catch(e) {}
      document.body.removeChild(ta);
    }

    function showBtnFeedback(btnId, msg) {
      const btn = document.getElementById(btnId);
      if (!btn) return;
      const span = btn.querySelector('span');
      if (span) {
        const orig = span.textContent;
        span.textContent = msg || 'Tersalin! ✅';
        btn.classList.add('bg-emerald-600');
        setTimeout(() => {
          span.textContent = orig;
          btn.classList.remove('bg-emerald-600');
        }, 2000);
      }
    }

    function scrollToSection(id) {
      const element = document.getElementById(id);

      if (element) {
        const offset = 120;
        const top = element.getBoundingClientRect().top + window.scrollY - offset;

        window.scrollTo({
          top,
          behavior: 'smooth'
        });
      }
    }

    function updateStepUI(activeIndex) {
      const configs = [
        { id: 'step-btn-1', color: '#E60049' },
        { id: 'step-btn-2', color: '#F3A833' },
        { id: 'step-btn-3', color: '#00A896' }
      ];

      configs.forEach((config, index) => {
        const btn = document.getElementById(config.id);

        if (!btn) return;

        const numSpan = btn.querySelector('span:first-child');
        const textSpans = btn.querySelectorAll('span span');

        if (index === activeIndex) {
          numSpan.style.backgroundColor = config.color;
          numSpan.style.color = '#FFFFFF';

          if (textSpans[0]) textSpans[0].style.color = config.color;
          if (textSpans[1]) textSpans[1].style.color = '#3B2314';

          btn.style.transform = 'translateY(-2px)';
        } else {
          numSpan.style.backgroundColor = '#E9DDD2';
          numSpan.style.color = '#7B6759';

          if (textSpans[0]) textSpans[0].style.color = '#8E7B6D';
          if (textSpans[1]) textSpans[1].style.color = '#7B6759';

          btn.style.transform = '';
        }
      });
    }

    window.addEventListener('scroll', () => {
      const sections = ['sec-data-diri', 'sec-durasi', 'sec-pembayaran'];
      const scrollPosition = window.scrollY + 220;

      let activeIndex = 0;

      sections.forEach((secId, idx) => {
        const el = document.getElementById(secId);

        if (el && scrollPosition >= el.offsetTop) {
          activeIndex = idx;
        }
      });

      updateStepUI(activeIndex);
    }, { passive: true });

    function selectPaymentOption(type) {
      currentPaymentType = type;

      const optTransfer = document.getElementById('opt-transfer');
      const optCod = document.getElementById('opt-cod');
      const transferContainer = document.getElementById('transfer-method-container');
      const codContainer = document.getElementById('cod-method-container');

      const transferIcon = optTransfer?.querySelector('i');
      const codIcon = optCod?.querySelector('i');

      if (type === 'transfer') {
        optTransfer.className = "payment-option p-5 rounded-2xl border-2 border-[#E60049] bg-[#E60049]/5 text-left shadow-sm";
        optCod.className = "payment-option p-5 rounded-2xl border border-[#E9DDD2] bg-white text-left";

        if (transferIcon) {
          transferIcon.className = "fa-solid fa-circle-check text-[#E60049]";
        }

        if (codIcon) {
          codIcon.className = "fa-regular fa-circle text-[#9A887A]";
        }

        transferContainer.classList.remove('hidden');
        codContainer.classList.add('hidden');

        const selectBank = document.getElementById('select-bank');
        const paymentInput = document.getElementById('input-payment-method');
        if (paymentInput) {
          paymentInput.value = selectBank ? selectBank.value : 'qris';
        }
      } else {
        optCod.className = "payment-option p-5 rounded-2xl border-2 border-[#00A896] bg-[#00A896]/5 text-left shadow-sm";
        optTransfer.className = "payment-option p-5 rounded-2xl border border-[#E9DDD2] bg-white text-left";

        if (codIcon) {
          codIcon.className = "fa-solid fa-circle-check text-[#00A896]";
        }

        if (transferIcon) {
          transferIcon.className = "fa-regular fa-circle text-[#9A887A]";
        }

        transferContainer.classList.add('hidden');
        codContainer.classList.remove('hidden');

        const paymentInput = document.getElementById('input-payment-method');
        if (paymentInput) {
          paymentInput.value = 'cash';
        }
      }
    }

    function updateSelectedBank(val) {
      if (currentPaymentType === 'transfer') {
        const paymentInput = document.getElementById('input-payment-method');
        if (paymentInput) {
          paymentInput.value = val;
        }
      }
    }

    function handleFileUpload(event) {
      const file = event.target.files[0];

      if (file) {
        if (file.size > 5 * 1024 * 1024) {
          alert('Ukuran file maksimal 5MB!');
          event.target.value = '';
          return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
          uploadedFileBase64 = e.target.result;

          document.getElementById('upload-placeholder').classList.add('hidden');

          const previewContainer = document.getElementById('upload-preview');
          previewContainer.classList.remove('hidden');
          previewContainer.classList.add('flex');

          document.getElementById('preview-img').src = uploadedFileBase64;
          document.getElementById('preview-filename').textContent = file.name;
        };

        reader.readAsDataURL(file);
      }
    }

    function closeFailedModal() {
      const modal = document.getElementById('flash-failed-modal');

      if (modal) {
        modal.remove();
      }
    }

    const revealElements = document.querySelectorAll('.reveal');

    const revealOnScroll = () => {
      const windowHeight = window.innerHeight;

      revealElements.forEach((el, index) => {
        if (el.getBoundingClientRect().top < windowHeight - 70) {
          el.style.transitionDelay = `${Math.min(index * 45, 250)}ms`;
          el.classList.add('active');
        }
      });
    };

    const bookingForm = document.getElementById('booking-form');
    const submitBtn = document.getElementById('btn-submit-booking');
    if (bookingForm && submitBtn) {
      bookingForm.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        submitBtn.innerHTML = `
          <svg class="animate-spin mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>Memproses Reservasi...</span>
        `;
      });
    }

    window.addEventListener('scroll', revealOnScroll, { passive: true });
    window.addEventListener('load', revealOnScroll);
  </script>
</body>
</html>
