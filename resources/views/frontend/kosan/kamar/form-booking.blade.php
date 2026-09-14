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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">

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
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover {
      background: #0891b2 !important;
      border-color: #0891b2 !important;
      font-weight: 700 !important;
      border-radius: 0.75rem !important;
    }
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.flatpickr-disabled:hover {
      color: #94a3b8 !important;
      background: #f8fafc !important;
      cursor: not-allowed !important;
      position: relative !important;
      border-radius: 0.5rem !important;
    }
    .flatpickr-day.flatpickr-disabled::after {
      content: "";
      position: absolute;
      bottom: 5px;
      left: 50%;
      transform: translateX(-50%);
      width: 18px;
      height: 3px;
      background: #06b6d4;
      border-radius: 9999px;
    }
  </style>
</head>
<body class="bg-[#fffaf7] text-[#3B2314] font-sans antialiased overflow-x-hidden">

  <!-- 1. BAR LOADING -->
  <div id="page-loader" class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-500 to-blue-600 z-[100] transition-all duration-500 ease-out"></div>

  <!-- 2. NAVBAR -->
  @include('frontend.navbar')

  <!-- 3. STICKY STEPPER NAVIGASI -->
  <div class="sticky top-[64px] sm:top-[80px] z-30 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm py-2.5 transition-all">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between sm:justify-center gap-1 sm:gap-8">

        <!-- Step 1 -->
        <button type="button" onclick="scrollToSection('sec-data-diri')" id="step-btn-1" class="step-btn flex items-center gap-1.5 sm:gap-2 pb-1 border-b-2 border-cyan-600 text-cyan-600 font-bold transition-all shrink-0">
          <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-cyan-600 text-white flex items-center justify-center text-[11px] sm:text-xs font-bold">1</span>
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
  <main class="scl-surface max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- BREADCRUMB -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 reveal">
      <a href="{{ route('kosan.index') }}" class="hover:text-cyan-600 transition-colors">Kos-Kosan</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      @if($kamar->productKosan)
        <a href="{{ route('kosan.detail', $kamar->productKosan->slug) }}" class="hover:text-cyan-600 transition-colors">{{ $kamar->productKosan->title }}</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
      @endif
      <a href="{{ route('kamar.detail', $kamar->id) }}" class="hover:text-cyan-600 transition-colors">{{ $kamar->room }}</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <span class="text-slate-800 font-bold">Booking Kamar</span>
    </nav>

    <!-- HEADER TITLE -->
    <div class="space-y-1 reveal">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Formulir Booking Kamar</h1>
      <p class="text-xs sm:text-sm text-slate-500">Lengkapi data berikut untuk reservasi unit kamar impian Anda secara aman.</p>
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
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center text-xs font-bold">1</span>
              Informasi Penyewa
            </h2>

            <div class="space-y-4 text-xs sm:text-sm">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Tipe Unit Kamar Terpilih</label>
                <input type="text" value="{{ $kamar->room }} - {{ $kamar->productKosan->title ?? 'NemuKOS' }}" readonly class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-medium text-slate-600 focus:outline-none cursor-not-allowed">
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap (sesuai KTP) <span class="text-rose-500">*</span></label>
                  <input type="text" name="name" id="input-nama" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl font-medium text-slate-800 focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 transition-all">
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                  <input type="tel" name="telp" id="input-wa" required placeholder="08123456XXXX" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl font-medium text-slate-800 focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 transition-all">
                </div>
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Email Aktif (untuk penerimaan PDF Booking) <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="input-email" required placeholder="budi@example.com" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl font-medium text-slate-800 focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 transition-all">
                <p class="text-[11px] text-slate-400 mt-1">E-tiket dan invoice PDF akan dikirimkan otomatis ke alamat email ini.</p>
              </div>
            </div>
          </section>

          <!-- SECTION 2: DURASI & TANGGAL -->
          <section id="sec-durasi" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4 reveal">
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center text-xs font-bold">2</span>
              Pilihan Durasi & Tanggal Masuk
            </h2>

            <div class="space-y-4 text-xs sm:text-sm">
              <div>
                <span class="block font-semibold text-slate-700 mb-2">Durasi Sewa</span>
                <div class="grid grid-cols-5 gap-2">
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1 text-center">Jam</label>
                    <input type="number" id="durasi-jam" name="jam" value="0" min="0" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-600">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1 text-center">Hari</label>
                    <input type="number" id="durasi-hari" name="hari" value="0" min="0" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-600">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1 text-center">Minggu</label>
                    <input type="number" id="durasi-minggu" name="minggu" value="0" min="0" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-600">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1 text-center">Bulan</label>
                    <input type="number" id="durasi-bulan" name="bulan" value="1" min="0" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-600">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1 text-center">Tahun</label>
                    <input type="number" id="durasi-tahun" name="tahun" value="0" min="0" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-center font-bold text-slate-800 focus:outline-none focus:border-cyan-600">
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
                    <input type="text" id="input-tanggal" name="start_date" required placeholder="Pilih tanggal mulai..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl font-medium text-slate-800 focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 transition-all cursor-pointer">
                  </div>
                  <p class="mt-1 text-[11px] text-slate-400">Tanggal dengan garis bawah menandakan sudah ter-booking.</p>
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Perkiraan Jam Check-in <span class="text-rose-500">*</span></label>
                  <input type="time" id="input-jam" name="start_time" value="13:00" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl font-medium text-slate-800 focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 transition-all">
                </div>
              </div>

            </div>
          </section>

          <!-- SECTION 3: PEMBAYARAN -->
          <section id="sec-pembayaran" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4 reveal">
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center text-xs font-bold">3</span>
              Opsi & Metode Pembayaran
            </h2>

            <div class="grid grid-cols-2 gap-4">
              <button type="button" onclick="selectPaymentOption('transfer')" id="opt-transfer" class="p-4 rounded-2xl border-2 border-cyan-500 bg-cyan-50/50 text-left transition-all active:scale-95 shadow-sm">
                <span class="block font-extrabold text-sm text-slate-800">TRANSFER ONLINE</span>
                <span class="block text-[11px] text-slate-500">QRIS / Rekening Bank</span>
              </button>
              <button type="button" onclick="selectPaymentOption('cod')" id="opt-cod" class="p-4 rounded-2xl border border-slate-200 bg-white text-left transition-all active:scale-95 hover:border-slate-300">
                <span class="block font-extrabold text-sm text-slate-800">BAYAR DI TEMPAT (COD)</span>
                <span class="block text-[11px] text-slate-400">Bayar saat check-in</span>
              </button>
            </div>

            <div id="transfer-method-container" class="space-y-4 pt-2 transition-all">
              <div>
                <label class="block font-semibold text-xs sm:text-sm text-slate-700 mb-1">Pilih Rekening Tujuan</label>
                <select id="select-bank" name="payment_method" onchange="toggleQrisDisplay()" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl font-medium text-slate-800 focus:outline-none focus:border-cyan-600">
                  <option value="qris">QRIS (Semua Bank & E-Wallet: BCA, Mandiri, Dana, GoPay)</option>
                  <option value="bca">BCA: 1234567890 a/n NemuKOS Management</option>
                  <option value="mandiri">Mandiri: 0987654321 a/n NemuKOS Management</option>
                  <option value="bni">BNI: 1111111111 a/n NemuKOS Management</option>
                </select>
              </div>

              <!-- BLOK QRIS -->
              <div id="qris-display" class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center space-y-3 text-center">
                <span class="text-xs font-bold text-slate-700">Scan QRIS Resmi Pembayaran</span>
                <div class="p-3 bg-white rounded-2xl border border-slate-200 shadow-md inline-block">
                  <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=NemuKOS-Sewa-Kosan" alt="Kode QRIS Pembayaran" class="w-40 h-40 object-contain">
                </div>
                <p class="text-[11px] text-slate-500 max-w-xs leading-relaxed">
                  Buka aplikasi m-Banking atau e-Wallet favorit Anda, pilih menu QRIS / Scan, lalu selesaikan pembayaran sesuai total tagihan.
                </p>
              </div>

              <div>
                <label class="block font-semibold text-xs sm:text-sm text-slate-700 mb-1">Unggah Bukti Transfer / Resi <span class="text-rose-500">*</span></label>
                <div onclick="document.getElementById('file-bukti').click()" class="border-2 border-dashed border-slate-300 hover:border-cyan-500 rounded-2xl p-6 text-center bg-slate-50/50 hover:bg-cyan-50/30 transition-all cursor-pointer group relative">
                  <input type="file" id="file-bukti" name="proof_of_transfer" accept="image/*" class="hidden" onchange="handleFileUpload(event)">

                  <div id="upload-placeholder" class="space-y-1">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 group-hover:text-cyan-600 transition-colors mb-2"></i>
                    <p class="text-xs sm:text-sm font-bold text-slate-700">Klik untuk upload bukti pembayaran</p>
                    <p class="text-[10px] text-slate-400">Format: JPG, PNG, WEBP (Maksimal 5MB)</p>
                  </div>

                  <div id="upload-preview" class="hidden flex-col items-center justify-center space-y-2">
                    <img id="preview-img" src="" alt="Bukti Transfer" class="h-32 rounded-xl object-contain border border-slate-200 shadow-sm">
                    <span id="preview-filename" class="text-xs font-semibold text-slate-700 truncate max-w-full block"></span>
                    <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-3 py-1 rounded-full"><i class="fa-solid fa-check mr-1"></i> File Siap Diunggah</span>
                  </div>
                </div>
              </div>
            </div>

            <div id="cod-method-container" class="hidden p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed space-y-1">
              <span class="font-bold block"><i class="fa-solid fa-circle-info text-amber-600 mr-1"></i> Informasi Pembayaran Tunai</span>
              <p>Pembayaran dilakukan secara langsung kepada pengelola kos saat serah terima kunci di lokasi. Pastikan nomor kontak aktif untuk konfirmasi.</p>
            </div>

          </section>

        </div>

        <!-- KOLOM SIDEBAR RINGKASAN -->
        <div class="lg:col-span-5 lg:sticky lg:top-[140px] space-y-4 reveal">
          <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl space-y-6">

            <h2 class="text-base font-extrabold text-slate-900">Ringkasan Pesanan</h2>

            <div class="flex items-center gap-4">
              @php
                $imgKamar = $kamar->productKamarImageKosan->first();
                $previewKamarImg = $imgKamar ? asset('storage/' . $imgKamar->image) : 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=300&q=80';
              @endphp
              <img src="{{ $previewKamarImg }}" alt="{{ $kamar->room }}" class="w-20 h-16 rounded-2xl object-cover shrink-0 border border-slate-100">
              <div>
                <h3 class="font-bold text-sm text-slate-900">{{ $kamar->room }}</h3>
                <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5 font-medium">
                  <i class="fa-solid fa-location-dot text-rose-500 text-[10px]"></i> {{ $kamar->productKosan->wilayah ?? 'Lokasi Terdaftar' }}
                </p>
              </div>
            </div>

            <hr class="border-slate-100">

            <div class="space-y-2.5 text-xs sm:text-sm">
              <div class="flex justify-between text-slate-600">
                <span>Subtotal Sewa (<span id="summary-durasi-text">1 Bulan</span>)</span>
                <span id="summary-subtotal" class="font-bold text-slate-900">Rp 0</span>
              </div>

              <div id="summary-discount-row" class="flex justify-between text-slate-600 hidden">
                <span class="text-emerald-700 font-semibold">Diskon Promo</span>
                <span id="summary-discount-amount" class="font-bold text-emerald-600">- Rp 0</span>
              </div>

              <div class="flex justify-between text-slate-600">
                <span>PPN (12%)</span>
                <span id="summary-ppn" class="font-bold text-slate-800">+ Rp 0</span>
              </div>
            </div>

            <hr class="border-slate-100">

            <div class="space-y-1">
              <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase block">Total Pembayaran</span>
              <div id="summary-grand-total" class="text-2xl font-extrabold text-slate-900">Rp 0</div>
            </div>

            <!-- Tombol Submit Form -->
            <button id="btn-submit-booking" type="submit" class="w-full py-4 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-extrabold rounded-2xl transition-all shadow-lg hover:shadow-cyan-500/25 flex items-center justify-center gap-2 active:scale-95 text-xs sm:text-sm">
              <span>Konfirmasi & Selesaikan Booking</span> <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>

            <div class="p-3.5 bg-cyan-50/60 rounded-2xl border border-cyan-100 flex items-start gap-2.5 text-[11px] text-slate-600 leading-snug">
              <i class="fa-solid fa-shield-check text-cyan-600 text-sm mt-0.5 shrink-0"></i>
              <span>
                Data Anda dilindungi dengan enkripsi aman. Invoice & Tiket PDF diterbitkan instan pasca-transaksi.
              </span>
            </div>

          </div>
        </div>

      </div>
    </form>

  </main>

  <!-- 4. MODAL POP-UP FAILED BOOKING -->
  @if (session('failed'))
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

  <!-- Flatpickr JS & Locale ID -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

  <script>
    let currentPaymentType = 'transfer';
    let uploadedFileBase64 = '';
    let fpInstance = null;

    const kamarPriceData = @json($kamar->priceKamar);
    const cumulativeDiscountVal = parseFloat("{{ $kamar->cumulative_discount ?? 0 }}") || 0;

    document.addEventListener("DOMContentLoaded", function() {
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
        }

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

        fetchBookedDates();
        setInterval(fetchBookedDates, 10000);

        const durationInputs = ['durasi-jam', 'durasi-hari', 'durasi-minggu', 'durasi-bulan', 'durasi-tahun'];
        durationInputs.forEach(id => {
          const inputEl = document.getElementById(id);
          if (inputEl) {
            inputEl.addEventListener('input', calculateRealtimePrice);
          }
        });

        calculateRealtimePrice();

        // Submit button protection
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

      const filledCategories = Object.keys(durationMap).filter(cat => durationMap[cat] > 0);
      const isSingleInput = filledCategories.length === 1;
      const isMultipleInputs = filledCategories.length > 1;

      let totalBasePrice = 0;
      let totalDiscountAmount = 0;
      let durationTexts = [];

      function getCategoryPriceAndDiscount(categoryName) {
        if (!kamarPriceData || !Array.isArray(kamarPriceData)) return { price: 0, discount: 0 };

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

      const hiddenTotalPrice = document.getElementById('input-hidden-total-price');
      if (hiddenTotalPrice) hiddenTotalPrice.value = grandTotal;
    }

    function formatRupiah(number) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
    }

    window.addEventListener('load', () => {
      const loader = document.getElementById('page-loader');
      if (loader) {
        loader.style.width = '100%';
        setTimeout(() => { loader.style.opacity = '0'; }, 400);
      }
      toggleQrisDisplay();
    });

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

    function scrollToSection(id) {
      const element = document.getElementById(id);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
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
            btn.className = "step-btn flex items-center gap-1.5 sm:gap-2 pb-1 border-b-2 border-cyan-600 text-cyan-600 font-bold transition-all shrink-0";
            if (numSpan) numSpan.className = "w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-cyan-600 text-white flex items-center justify-center text-[11px] sm:text-xs font-bold";
          } else {
            btn.className = "step-btn flex items-center gap-1.5 sm:gap-2 pb-1 border-b-2 border-transparent text-slate-400 font-medium transition-all shrink-0 hover:text-slate-700";
            if (numSpan) numSpan.className = "w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[11px] sm:text-xs font-bold";
          }
        }
      });
    });

    function selectPaymentOption(type) {
      currentPaymentType = type;
      const optTransfer = document.getElementById('opt-transfer');
      const optCod = document.getElementById('opt-cod');
      const transferContainer = document.getElementById('transfer-method-container');
      const codContainer = document.getElementById('cod-method-container');

      if (type === 'transfer') {
        optTransfer.className = "p-4 rounded-2xl border-2 border-cyan-500 bg-cyan-50/50 text-left transition-all active:scale-95 shadow-sm";
        optCod.className = "p-4 rounded-2xl border border-slate-200 bg-white text-left transition-all active:scale-95 hover:border-slate-300";
        transferContainer.classList.remove('hidden');
        codContainer.classList.add('hidden');
      } else {
        optCod.className = "p-4 rounded-2xl border-2 border-cyan-500 bg-cyan-50/50 text-left transition-all active:scale-95 shadow-sm";
        optTransfer.className = "p-4 rounded-2xl border border-slate-200 bg-white text-left transition-all active:scale-95 hover:border-slate-300";
        transferContainer.classList.add('hidden');
        codContainer.classList.remove('hidden');
      }
    }

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

    function closeFailedModal() {
      const modal = document.getElementById('flash-failed-modal');
      if (modal) modal.remove();
    }

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
