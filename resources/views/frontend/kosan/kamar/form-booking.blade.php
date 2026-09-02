<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NemuKOS-Denpasar - Form Booking Kamar</title>
  <link rel="stylesheet" href="{{ asset('build/assets/app-D-OnOQL0.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- Flatpickr CSS & Custom Theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
  <link rel="icon" href="{{ asset('logo.png') }}">


  <style>
    /* Animation reveal saat scroll */
    .reveal {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }

    /* Help Menu Animation */
    .help-menu-enter {
      opacity: 0;
      transform: translateY(20px) scale(0.95);
      pointer-events: none;
    }
    .help-menu-active {
      opacity: 1;
      transform: translateY(0) scale(1);
      pointer-events: auto;
    }

    /* Custom Scrollbar */
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    /* Custom Styling Flatpickr to Match Modern UI */
    .flatpickr-calendar {
      border-radius: 1.25rem !important;
      box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
      border: 1px solid #f1f5f9 !important;
      padding: 0.75rem !important;
      font-family: inherit !important;
      max-width: calc(100vw - 2rem) !important;
      z-index: 99999 !important;
    }
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.startRange.inRange,
    .flatpickr-day.endRange.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.startRange:focus,
    .flatpickr-day.endRange:focus,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover {
      background: #06b6d4 !important;
      border-color: #06b6d4 !important;
      font-weight: 700 !important;
      border-radius: 0.75rem !important;
    }
    .flatpickr-day.inRange {
      box-shadow: -5px 0 0 #cffaff, 5px 0 0 #cffaff !important;
      background: #e0f2fe !important;
      border-color: transparent !important;
      color: #0369a1 !important;
    }
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.flatpickr-disabled:hover {
      color: #94a3b8 !important;
      background: #f8fafc !important;
      cursor: not-allowed !important;
      position: relative !important;
      border-radius: 0.5rem !important;
    }

    /* Bootstrap Calendar Style Single Horizontal Event Bar Indicator */
    .flatpickr-day.flatpickr-disabled::after {
      content: "";
      position: absolute;
      bottom: 5px;
      left: 50%;
      transform: translateX(-50%);
      width: 18px;
      height: 3.5px;
      background: #06b6d4;
      border-radius: 9999px;
      box-shadow: 0 1px 2px rgba(6, 182, 212, 0.4);
    }

    .flatpickr-calendar {
      width: auto !important;
      box-sizing: content-box !important;
    }
    .flatpickr-days {
      width: 308px !important;
    }
    .dayContainer {
      width: 308px !important;
      min-width: 308px !important;
      max-width: 308px !important;
    }
    .flatpickr-day {
      max-width: 39px !important;
      height: 38px !important;
      line-height: 34px !important;
      border-radius: 0.5rem !important;
    }
    .flatpickr-months .flatpickr-month {
      color: #0f172a !important;
      fill: #0f172a !important;
      font-weight: 700 !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
      font-weight: 700 !important;
    }
    .flatpickr-weekday {
      color: #64748b !important;
      font-weight: 600 !important;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

  <!-- 1. LOADING BAR -->
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-cyan-500 z-[100] transition-all duration-500 ease-out"></div>

  <!-- 2. NAVBAR CONTAINER -->
    @include('frontend.navbar')
  <!-- 3. STICKY STEPPER NAVIGASI -->
  <div class="sticky top-[64px] z-30 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm py-2.5 transition-all">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between sm:justify-center gap-1 sm:gap-8">

        <!-- Step 1 -->
        <button type="button" onclick="scrollToSection('sec-data-diri')" id="step-btn-1" class="step-btn flex items-center gap-1.5 sm:gap-2 pb-1 border-b-2 border-cyan-500 text-cyan-600 font-bold transition-all shrink-0">
          <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-cyan-500 text-white flex items-center justify-center text-[11px] sm:text-xs font-bold">1</span>
          <span class="text-[11px] sm:text-sm">Informasi Penyewa</span>
        </button>

        <div class="grow max-w-[30px] sm:max-w-[60px] h-[2px] bg-slate-200 shrink"></div>

        <!-- Step 2 -->
        <button type="button" onclick="scrollToSection('sec-durasi')" id="step-btn-2" class="step-btn flex items-center gap-1.5 sm:gap-2 pb-1 border-b-2 border-transparent text-slate-400 font-medium transition-all shrink-0 hover:text-slate-700">
          <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[11px] sm:text-xs font-bold">2</span>
          <span class="text-[11px] sm:text-sm">Durasi & Tanggal</span>
        </button>

        <div class="grow max-w-[30px] sm:max-w-[60px] h-[2px] bg-slate-200 shrink"></div>

        <!-- Step 3 -->
        <button type="button" onclick="scrollToSection('sec-pembayaran')" id="step-btn-3" class="step-btn flex items-center gap-1.5 sm:gap-2 pb-1 border-b-2 border-transparent text-slate-400 font-medium transition-all shrink-0 hover:text-slate-700">
          <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[11px] sm:text-xs font-bold">3</span>
          <span class="text-[11px] sm:text-sm">Pembayaran</span>
        </button>

      </div>
    </div>
  </div>

  <!-- MAIN CONTENT CONTAINER -->
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- BREADCRUMB -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 reveal">
      <a href="{{ route('kosan.index') }}" class="hover:text-cyan-600 transition-colors">Kos-Kosan</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <a href="{{ route('kosan.detail',$kamar->productKosan->slug) }}" class="hover:text-cyan-600 transition-colors">{{ $kamar->productKosan->title }}</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <a href="{{ route('kamar.detail',$kamar->id) }}" class="hover:text-cyan-600 transition-colors">{{ $kamar->room }}</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <span class="text-slate-800 font-bold">Booking Kamar</span>
    </nav>

    <!-- HEADER TITLE -->
    <div class="space-y-1 reveal">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Form Booking Kamar</h1>
      <p class="text-xs sm:text-sm text-slate-400">Lengkapi data di bawah ini untuk mengamankan unit pilihan Anda.</p>
    </div>

    <!-- FORM UTAMA -->
    <form id="booking-form" action="{{ route('tamu.booking', $kamar->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="total_price" id="input-hidden-total-price" value="0">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- KOLOM FORM (3 SECTION) -->
        <div class="lg:col-span-7 space-y-6">

          <!-- SECTION 1: DATA DIRI -->
          <section id="sec-data-diri" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4 reveal">
            <h2 class="text-base font-extrabold text-slate-900">Data Diri</h2>

            <div class="space-y-4 text-xs sm:text-sm">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Pilih Kamar / Tipe Unit</label>
                <input type="text" value="{{ $kamar->room }}" readonly class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-600 focus:outline-none cursor-not-allowed">
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap (sesuai KTP) <span class="text-rose-500">*</span></label>
                  <input type="text" name="name" id="input-nama" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-cyan-500 transition-all">
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                  <input type="tel" name="telp" id="input-wa" required placeholder="08123456XXXX" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-cyan-500 transition-all">
                </div>
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Email Aktif <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="input-email" required placeholder="budi@example.com" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-cyan-500 transition-all">
              </div>
            </div>
          </section>

          <!-- SECTION 2: DURASI & TANGGAL -->
          <section id="sec-durasi" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4 reveal">
            <h2 class="text-base font-extrabold text-slate-900">Pilihan Durasi & Tanggal Masuk</h2>

            <div class="space-y-4 text-xs sm:text-sm">
              <div>
                <span class="block font-semibold text-slate-700 mb-2">Jumlah Durasi Spesifik</span>
                <div class="grid grid-cols-5 gap-2">
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1">Jam</label>
                    <input type="number" id="durasi-jam" name="jam" value="0" min="0" class="w-full px-2 sm:px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-500">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1">Hari</label>
                    <input type="number" id="durasi-hari" name="hari" value="0" min="0" class="w-full px-2 sm:px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-500">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1">Minggu</label>
                    <input type="number" id="durasi-minggu" name="minggu" value="0" min="0" class="w-full px-2 sm:px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-500">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1">Bulan</label>
                    <input type="number" id="durasi-bulan" name="bulan" value="1" min="0" class="w-full px-2 sm:px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-500">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1">Tahun</label>
                    <input type="number" id="durasi-tahun" name="tahun" value="0" min="0" class="w-full px-2 sm:px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-500">
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Tanggal Mulai Masuk <span class="text-rose-500">*</span></label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                      <i class="fa-solid fa-calendar-day text-sm"></i>
                    </div>
                    <input type="text" id="input-tanggal" name="start_date" required placeholder="Pilih tanggal mulai masuk..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-cyan-500 transition-all cursor-pointer">
                  </div>
                  <div class="mt-1.5 flex items-center gap-1.5 text-[11px] text-slate-400">
                    <span class="w-3 h-1 rounded-full bg-cyan-500 inline-block shadow-sm"></span>
                    <span>Tanggal dengan **tanda garis cyan di bawahnya** sudah ter-booking & tidak dapat dipilih.</span>
                  </div>
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Jam Kedatangan <span class="text-rose-500">*</span></label>
                  <input type="time" id="input-jam" name="start_time" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-cyan-500">
                </div>
              </div>

            </div>
          </section>

          <!-- SECTION 3: PEMBAYARAN -->
          <section id="sec-pembayaran" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4 reveal">
            <h2 class="text-base font-extrabold text-slate-900">Opsi & Metode Pembayaran</h2>

            <div class="grid grid-cols-2 gap-4">
              <button type="button" onclick="selectPaymentOption('transfer')" id="opt-transfer" class="p-4 rounded-2xl border-2 border-cyan-400 bg-cyan-50/50 text-left transition-all active:scale-95 shadow-sm">
                <span class="block font-extrabold text-sm text-slate-800">TRANSFER</span>
                <span class="block text-[11px] text-slate-400">Pembayaran Online.</span>
              </button>
              <button type="button" onclick="selectPaymentOption('cod')" id="opt-cod" class="p-4 rounded-2xl border border-slate-200 bg-white text-left transition-all active:scale-95 hover:border-slate-300">
                <span class="block font-extrabold text-sm text-slate-800">COD</span>
                <span class="block text-[11px] text-slate-400">Pembayaran Offline.</span>
              </button>
            </div>

            <div id="transfer-method-container" class="space-y-4 pt-2 transition-all">
              <div>
                <label class="block font-semibold text-xs sm:text-sm text-slate-700 mb-1">Metode Pembayaran</label>
                <select id="select-bank" name="payment_method" onchange="toggleQrisDisplay()" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-cyan-500">
                  <option value="qris">QRIS (Scan Semua e-Wallet/Bank)</option>
                  <option value="bca">BCA: 1234567890</option>
                  <option value="mandiri">Mandiri: 0987654321</option>
                  <option value="bni">BNI: 1111111111</option>
                  <option value="dana">DANA: 081234567890</option>
                </select>
              </div>

              <!-- BLOK TAMPILAN KODE QRIS -->
              <div id="qris-display" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center space-y-3 text-center">
                <span class="text-xs font-bold text-slate-700">Scan QRIS di Bawah Ini</span>
                <div class="p-3 bg-white rounded-2xl border border-slate-200 shadow-sm inline-block">
                  <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=NemuKOS-Denpasar-QRIS-Payment" alt="Kode QRIS Pembayaran" class="w-40 h-40 object-contain">
                </div>
                <div class="text-[11px] text-slate-500 max-w-xs leading-relaxed">
                  Gunakan aplikasi e-wallet (Gopay, OVO, Dana, ShopeePay) atau Mobile Banking Anda untuk memindai kode QRIS di atas.
                </div>
              </div>

              <div>
                <label class="block font-semibold text-xs sm:text-sm text-slate-700 mb-1">Upload Bukti Transfer / Pembayaran <span class="text-rose-500">*</span></label>
                <div onclick="document.getElementById('file-bukti').click()" class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center bg-slate-50/50 hover:bg-slate-50 transition-colors cursor-pointer group relative">
                  <input type="file" id="file-bukti" name="proof_of_transfer" accept="image/*" class="hidden" onchange="handleFileUpload(event)">

                  <div id="upload-placeholder" class="space-y-1">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 group-hover:text-cyan-500 transition-colors mb-2"></i>
                    <p class="text-xs sm:text-sm font-semibold text-slate-700">Klik atau seret file ke sini</p>
                    <p class="text-[10px] text-slate-400">Format JPG, PNG (Max. 5MB)</p>
                  </div>

                  <div id="upload-preview" class="hidden flex-col items-center justify-center space-y-2">
                    <img id="preview-img" src="" alt="Bukti Transfer" class="h-28 rounded-lg object-contain border border-slate-200 shadow-sm">
                    <span id="preview-filename" class="text-xs font-semibold text-slate-700 truncate max-w-full block"></span>
                    <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2.5 py-1 rounded-full"><i class="fa-solid fa-check mr-1"></i> File Siap Disimpan</span>
                  </div>
                </div>
              </div>
            </div>

            <div id="cod-method-container" class="hidden p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs leading-relaxed">
              <i class="fa-solid fa-circle-info mr-1"></i> Pembayaran COD dilakukan secara langsung saat penerimaan kunci di lokasi kos. Anda tidak perlu mengunggah bukti transfer.
            </div>

          </section>

        </div>

        <!-- KOLOM SIDEBAR RINGKASAN -->
        <div class="lg:col-span-5 lg:sticky lg:top-[140px] space-y-4 reveal">
          <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6">

            <h2 class="text-base font-extrabold text-slate-900">Ringkasan Pesanan</h2>

            <div class="flex items-center gap-4">
                @foreach ($kamar->productKamarImageKosan->take(1) as $image )
                    <img src="{{ asset('storage/' . $image->image) }}" alt="Kamar 201" class="w-20 h-16 rounded-xl object-cover shrink-0">
                @endforeach
              <div>
                <h3 class="font-bold text-xs sm:text-sm text-slate-900">{{ $kamar->room }}</h3>
                <p class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                  <i class="fa-solid fa-location-dot text-cyan-600"></i> {{ $kamar->productKosan->wilayah }}
                </p>
              </div>
            </div>

            <hr class="border-slate-100">

            <div class="space-y-2.5 text-xs sm:text-sm">
              <div class="flex justify-between text-slate-500">
                <span>Subtotal Sewa (<span id="summary-durasi-text">1 Bulan</span>)</span>
                <span id="summary-subtotal" class="font-bold text-slate-800">Rp 0</span>
              </div>

              <!-- DISKON LAYOUT -->
              <div id="summary-discount-row" class="flex justify-between text-slate-500">
                <span>Diskon</span>
                <span id="summary-discount-amount" class="font-bold text-emerald-600">- Rp 0</span>
              </div>

              <div class="flex justify-between text-slate-500">
                <span>PPN (12%)</span>
                <span id="summary-ppn" class="font-bold text-rose-500">+ Rp 0</span>
              </div>
            </div>

            <hr class="border-slate-100">

            <div class="space-y-1">
              <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase block">Total Pembayaran</span>
              <div id="summary-grand-total" class="text-xl sm:text-2xl font-black text-slate-900">Rp 0</div>
            </div>

            <!-- Tombol Submit Form -->
            <button type="submit" class="w-full py-4 bg-cyan-200 hover:bg-cyan-300 text-slate-800 font-extrabold rounded-2xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 active:scale-95 text-xs sm:text-sm">
              Selesaikan Pembayaran <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>

            <div class="p-3.5 bg-cyan-50/50 rounded-2xl border border-cyan-100 flex items-start gap-2 text-[11px] text-slate-500 leading-snug">
              <i class="fa-solid fa-lightbulb text-cyan-500 text-xs mt-0.5 shrink-0"></i>
              <span>
                <a id="link-butuh-bantuan" href="#" target="_blank" class="font-bold text-cyan-700 hover:underline">Butuh Bantuan?</a> Hubungi kami melalui WhatsApp jika Anda memiliki pertanyaan tentang proses pembayaran atau ketersediaan unit.
              </span>
            </div>

          </div>
        </div>

      </div>
    </form>

  </main>


    <!-- 4. MODAL POP-UP FAILED BOOKING -->
    @if (session('failed'))
      <!-- MODAL POPUP FAILED BOOKING -->
      <div id="flash-failed-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[999] flex items-center justify-center p-4 transition-all">
        <div class="bg-white w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl relative space-y-5 border border-slate-100 animate-in fade-in zoom-in duration-200">

          <button type="button" onclick="closeFailedModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-200 hover:text-slate-700 transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
          </button>

          <div class="space-y-1 text-center">
            <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
              <i class="fa-solid fa-circle-xmark text-3xl"></i>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900">Booking Gagal</h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ session('failed') }}</p>
          </div>

          <button type="button" onclick="closeFailedModal()" class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-2xl transition-all shadow-md active:scale-95 text-xs sm:text-sm">
            Coba Lagi
          </button>

        </div>
      </div>
    @endif


  @include('frontend.footer')
  @include('frontend.need-help')

  <!-- Flatpickr JS & Locale ID -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

  <script>
    let currentPaymentType = 'transfer';
    let uploadedFileBase64 = '';
    let fpInstance = null;

    // DATA KAMAR & DISKON DARI BACKEND
    const kamarPriceData = @json($kamar->priceKamar);
    const cumulativeDiscountVal = parseFloat("{{ $kamar->cumulative_discount ?? 0 }}") || 0;

    // EVENT LISTENERS UNTUK INPUT DURASI SPESIFIK
    document.addEventListener("DOMContentLoaded", function() {
        // Standalone function untuk fetch data booking terkini & update kalender
        function fetchBookedDates() {
          fetch("{{ route('check.date.kamar', $kamar->id) }}")
          .then(response => {
              if (!response.ok) throw new Error("Gagal mengambil data booking");
              return response.json();
          })
          .then(data => {
              const disabledRanges = data.map(tamu => ({
                  from: tamu.start_date,
                  to: tamu.end_date
              }));
              if (fpInstance) {
                fpInstance.set('disable', disabledRanges);
              }
          })
          .catch(err => console.error("Error Fetch Date:", err));

          console.log('hit api');
        }

        // Flatpickr Datepicker dengan event onOpen (selalu fetch saat kalender dibuka)
        fpInstance = flatpickr("#input-tanggal", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y",
            minDate: "today",
            locale: "id",
            disable: [],
            appendTo: document.body,
            position: "auto center",
            onOpen: function() {
                fetchBookedDates();
            }
        });

        // Initial fetch
        fetchBookedDates();

        // Realtime Polling: Cek ketersediaan tanggal tiap 10 detik secara otomatis di background
        setInterval(fetchBookedDates, 10000);

        // Listen pada input durasi
        const durationInputs = ['durasi-jam', 'durasi-hari', 'durasi-minggu', 'durasi-bulan', 'durasi-tahun'];
        durationInputs.forEach(id => {
          const inputEl = document.getElementById(id);
          if (inputEl) {
            inputEl.addEventListener('input', calculateRealtimePrice);
          }
        });

        // Hitung awal saat load
        calculateRealtimePrice();
    });

    // SISTEM HITUNG HARGA REALTIME & RULE DISKON
    function calculateRealtimePrice() {
      const jam = parseInt(document.getElementById('durasi-jam')?.value) || 0;
      const hari = parseInt(document.getElementById('durasi-hari')?.value) || 0;
      const minggu = parseInt(document.getElementById('durasi-minggu')?.value) || 0;
      const bulan = parseInt(document.getElementById('durasi-bulan')?.value) || 0;
      const tahun = parseInt(document.getElementById('durasi-tahun')?.value) || 0;

      const durationMap = {
        'jam': jam,
        'hari': hari,
        'minggu': minggu,
        'bulan': bulan,
        'tahun': tahun
      };

      // Hitung berapa jenis kategori yang diisi (> 0)
      const filledCategories = Object.keys(durationMap).filter(cat => durationMap[cat] > 0);
      const isSingleInput = filledCategories.length === 1;
      const isMultipleInputs = filledCategories.length > 1;

      let totalBasePrice = 0;
      let totalDiscountAmount = 0;
      let durationTexts = [];

      // Fungsi Helper Cari Harga & Diskon Kategori dari DB
      function getCategoryPriceAndDiscount(categoryName) {
        if (!kamarPriceData || !Array.isArray(kamarPriceData)) return { price: 0, discount: 0 };

        const key = categoryName.toLowerCase().trim();
        // Cari exact match atau partial match (contoh: "harian" / "hari", "bulanan" / "bulan")
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

      // Hitung Base Price per Kategori yang diisi
      Object.keys(durationMap).forEach(cat => {
        const qty = durationMap[cat];
        if (qty > 0) {
          let label = cat.charAt(0).toUpperCase() + cat.slice(1);
          durationTexts.push(`${qty} ${label}`);

          const pInfo = getCategoryPriceAndDiscount(cat);
          const categorySubtotal = pInfo.price * qty;
          totalBasePrice += categorySubtotal;

          // ATURAN REVISI 1: Jika HANYA 1 inputan saja yang diisi (lainnya 0)
          // Gunakan diskon dari Price Kamar kategori tersebut
          if (isSingleInput) {
            const discVal = pInfo.discount;
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

      // ATURAN REVISI 2: Jika LEBIH DARI 1 inputan diisi (Kombinasi)
      // Gunakan diskon akumulatif yang ada di Product Kamar Kosan
      if (isMultipleInputs && cumulativeDiscountVal > 0) {
        if (cumulativeDiscountVal <= 100) {
          totalDiscountAmount = (totalBasePrice * cumulativeDiscountVal) / 100;
        } else {
          totalDiscountAmount = cumulativeDiscountVal;
        }
      }

      // Pastikan total discount tidak melebihi base price
      if (totalDiscountAmount > totalBasePrice) {
        totalDiscountAmount = totalBasePrice;
      }

      // Hitung PPN 12% dan Grand Total
      const priceAfterDiscount = totalBasePrice - totalDiscountAmount;
      const ppnAmount = priceAfterDiscount * 0.12;
      const grandTotal = priceAfterDiscount + ppnAmount;

      // UPDATE UI DOM SUMMARY REALTIME
      const durasiTextElem = document.getElementById('summary-durasi-text');
      const subtotalElem = document.getElementById('summary-subtotal');
      const ppnElem = document.getElementById('summary-ppn');
      const grandTotalElem = document.getElementById('summary-grand-total');

      if (durasiTextElem) durasiTextElem.textContent = durationTexts.length > 0 ? durationTexts.join(', ') : '0 Durasi';
      if (subtotalElem) subtotalElem.textContent = formatRupiah(totalBasePrice);

      const discountRow = document.getElementById('summary-discount-row');
      const discountAmountText = document.getElementById('summary-discount-amount');

      if (discountRow) {
        if (totalDiscountAmount > 0) {
          discountRow.classList.remove('hidden');
          if (discountAmountText) discountAmountText.textContent = `- ${formatRupiah(totalDiscountAmount)}`;
        } else {
          discountRow.classList.add('hidden');
        }
      }

      if (ppnElem) ppnElem.textContent = `+ ${formatRupiah(ppnAmount)}`;
      if (grandTotalElem) grandTotalElem.textContent = formatRupiah(grandTotal);

      // Sync ke hidden input untuk backend
      const hiddenTotalPrice = document.getElementById('input-hidden-total-price');
      if (hiddenTotalPrice) hiddenTotalPrice.value = grandTotal;
    }

    function formatRupiah(number) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
    }





    // 2. LOADING BAR
    window.addEventListener('load', () => {
      const loader = document.getElementById('page-loader');
      if (loader) {
        loader.style.width = '100%';
        setTimeout(() => { loader.style.opacity = '0'; }, 400);
      }
      toggleQrisDisplay();
    });

    // 3. TOGGLE TAMPILAN QRIS
    function toggleQrisDisplay() {
      const selectBank = document.getElementById('select-bank');
      const qrisDisplay = document.getElementById('qris-display');
      if (selectBank && qrisDisplay) {
        if (selectBank.value === 'qris') {
          qrisDisplay.classList.remove('hidden');
        } else {
          qrisDisplay.classList.add('hidden');
        }
      }
    }



    window.addEventListener('scroll', () => {
      const sections = ['sec-data-diri', 'sec-durasi', 'sec-pembayaran'];
      const scrollPosition = window.scrollY + 200;

      sections.forEach((secId, idx) => {
        const el = document.getElementById(secId);
        const btn = document.getElementById(`step-btn-${idx + 1}`);

        if (el && btn) {
          const top = el.offsetTop;
          const height = el.offsetHeight;
          const numSpan = btn.querySelector('span:first-child');

          if (scrollPosition >= top && scrollPosition < top + height) {
            btn.className = "step-btn flex items-center gap-1.5 sm:gap-2 pb-1 border-b-2 border-cyan-500 text-cyan-600 font-bold transition-all shrink-0";
            if (numSpan) numSpan.className = "w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-cyan-500 text-white flex items-center justify-center text-[11px] sm:text-xs font-bold";
          } else {
            btn.className = "step-btn flex items-center gap-1.5 sm:gap-2 pb-1 border-b-2 border-transparent text-slate-400 font-medium transition-all shrink-0 hover:text-slate-700";
            if (numSpan) numSpan.className = "w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[11px] sm:text-xs font-bold";
          }
        }
      });
    });

    // 6. TOGGLE METODE PEMBAYARAN
    function selectPaymentOption(type) {
      currentPaymentType = type;
      const optTransfer = document.getElementById('opt-transfer');
      const optCod = document.getElementById('opt-cod');
      const transferContainer = document.getElementById('transfer-method-container');
      const codContainer = document.getElementById('cod-method-container');

      if (type === 'transfer') {
        optTransfer.className = "p-4 rounded-2xl border-2 border-cyan-400 bg-cyan-50/50 text-left transition-all active:scale-95 shadow-sm";
        optCod.className = "p-4 rounded-2xl border border-slate-200 bg-white text-left transition-all active:scale-95 hover:border-slate-300";
        transferContainer.classList.remove('hidden');
        codContainer.classList.add('hidden');
      } else {
        optCod.className = "p-4 rounded-2xl border-2 border-cyan-400 bg-cyan-50/50 text-left transition-all active:scale-95 shadow-sm";
        optTransfer.className = "p-4 rounded-2xl border border-slate-200 bg-white text-left transition-all active:scale-95 hover:border-slate-300";
        transferContainer.classList.add('hidden');
        codContainer.classList.remove('hidden');
      }
    }

    // 7. LOGIKA UPLOAD BUKTI TRANSFER KE LOCALSTORAGE
    function handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        if (file.size > 5 * 1024 * 1024) {
          alert('Ukuran file maksimal 5MB!');
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


    // Navigasi Kembali ke Beranda (Redirect ke Home '/') ketika modal ditutup
    function goToHome() {
      window.location.href = "{{ url('/') }}";
    }

    // 9. ANIMASI REVEAL
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
