<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bukti Reservasi - #BOOK-{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }} - Sinar Citra Lestari</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="icon" href="{{ asset('scl.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
        radial-gradient(circle at 10% 10%, rgba(243,168,51,.12), transparent 25rem),
        radial-gradient(circle at 90% 40%, rgba(230,0,73,.07), transparent 28rem),
        radial-gradient(circle at 50% 90%, rgba(0,168,150,.06), transparent 26rem),
        var(--cream);
      font-family: inherit;
    }

    .ticket-perforation {
      background-image: radial-gradient(circle at 0 50%, transparent 12px, #ffffff 13px),
                        radial-gradient(circle at 100% 50%, transparent 12px, #ffffff 13px);
    }

    .shine {
      position: relative;
      overflow: hidden;
    }
    .shine::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -60%;
      width: 40%;
      height: 200%;
      background: linear-gradient(
        to right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.3) 50%,
        rgba(255, 255, 255, 0) 100%
      );
      transform: rotate(25deg);
      animation: shineAnimation 4s infinite;
    }
    @keyframes shineAnimation {
      0% { left: -60%; }
      20% { left: 140%; }
      100% { left: 140%; }
    }
  </style>
</head>

<body class="text-[#3B2314] antialiased min-h-screen flex flex-col justify-between">

  @include('frontend.navbar')

  <main class="flex-grow max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 w-full">

    <!-- BREADCRUMBS -->
    <nav class="flex items-center gap-2 text-xs text-[#8E7B6D] mb-6">
      <a href="{{ route('home') }}" class="hover:text-[#E60049] transition-colors">Beranda</a>
      <i class="fa-solid fa-chevron-right text-[10px] text-[#C4B2A5]"></i>
      <a href="{{ route('kosan.index') }}" class="hover:text-[#E60049] transition-colors">Katalog Kos</a>
      <i class="fa-solid fa-chevron-right text-[10px] text-[#C4B2A5]"></i>
      <span class="text-[#3B2314] font-bold">Bukti Booking</span>
    </nav>

    <!-- SUCCESS HERO HEADER -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-emerald-50 text-[#00A896] border-2 border-emerald-200/80 shadow-lg shadow-emerald-600/10 mb-4 animate-bounce">
        <i class="fa-solid fa-check text-2xl sm:text-3xl"></i>
      </div>

      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00A896]/10 text-[#00A896] text-xs font-black uppercase tracking-wider mb-2">
        <i class="fa-solid fa-shield-check"></i> Reservasi Berhasil Diajukan
      </div>

      <h1 class="text-2xl sm:text-4xl font-black text-[#3B2314] tracking-tight">
        Bukti Reservasi & e-Ticket
      </h1>
      <p class="text-xs sm:text-sm text-[#7B6759] mt-2 max-w-md mx-auto leading-relaxed">
        Terima kasih, <strong>{{ $tamu->name }}</strong>! Rincian pemesanan Anda telah tersimpan rapi dalam sistem kami.
      </p>
    </div>

    <!-- FLASH MESSAGE -->
    @if(session('success'))
      <div class="mb-6 p-4 rounded-2xl bg-[#00A896]/10 border border-[#00A896]/30 text-[#007063] text-xs sm:text-sm font-semibold flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-base shrink-0"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    <!-- MAIN E-TICKET CARD -->
    <div class="bg-white rounded-[2.5rem] border border-[#E9DDD2] shadow-xl overflow-hidden mb-8">
      
      <!-- TICKET TOP BAR -->
      <div class="bg-[#3B2314] p-6 sm:p-8 text-white relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#F3A833]/10 rounded-full blur-2xl"></div>
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-[#E60049]/10 rounded-full blur-2xl"></div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
          <div>
            <span class="text-[10px] uppercase font-black tracking-widest text-[#F3A833] block mb-1">Kode Booking Resmi</span>
            <div class="flex items-center gap-3">
              <span id="booking-code-text" class="text-2xl sm:text-3xl font-black tracking-wider text-white">
                #BOOK-{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }}
              </span>
              <button
                type="button"
                id="btn-copy-code"
                onclick="copyBookingCode('#BOOK-{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }}')"
                class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-xs text-[#FFF8F1] font-bold transition-all flex items-center gap-1.5 active:scale-95"
                title="Salin Kode Booking"
              >
                <i class="fa-regular fa-copy"></i>
                <span id="copy-btn-text">Salin</span>
              </button>
            </div>
          </div>

          <div class="sm:text-right">
            <span class="text-[10px] uppercase font-bold tracking-wider text-white/60 block mb-1">Status Reservasi</span>
            @if($tamu->status === 'approved')
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-black uppercase">
                <i class="fa-solid fa-circle-check"></i> Terkonfirmasi
              </span>
            @else
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-400/30 text-xs font-black uppercase">
                <i class="fa-solid fa-clock"></i> Menunggu Verifikasi
              </span>
            @endif
          </div>
        </div>
      </div>

      <!-- TICKET BODY: 2 COLUMNS -->
      <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">

        <!-- COLUMN 1: GUEST & SCHEDULE -->
        <div class="space-y-6">
          <!-- GUEST DETAILS -->
          <div class="bg-[#FFF8F1] p-5 rounded-2xl border border-[#E9DDD2]">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#8E7B6D] mb-3 flex items-center gap-2">
              <i class="fa-solid fa-user text-[#E60049]"></i> Data Penyewa
            </h3>
            <div class="space-y-2.5 text-xs">
              <div class="flex justify-between">
                <span class="text-[#7B6759]">Nama Lengkap</span>
                <span class="font-extrabold text-[#3B2314] text-right">{{ $tamu->name }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-[#7B6759]">No. WhatsApp</span>
                <span class="font-extrabold text-[#3B2314] text-right">{{ $tamu->telp }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-[#7B6759]">Email Konfirmasi</span>
                <span class="font-extrabold text-[#3B2314] text-right">{{ $tamu->email }}</span>
              </div>
            </div>
          </div>

          <!-- SCHEDULE DETAILS -->
          <div class="bg-[#FFF8F1] p-5 rounded-2xl border border-[#E9DDD2]">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#8E7B6D] mb-3 flex items-center gap-2">
              <i class="fa-solid fa-calendar-days text-[#F3A833]"></i> Jadwal Hunian
            </h3>
            <div class="space-y-2.5 text-xs">
              <div class="flex justify-between">
                <span class="text-[#7B6759]">Check-In</span>
                <span class="font-extrabold text-[#3B2314] text-right">
                  {{ \Carbon\Carbon::parse($tamu->start_date)->translatedFormat('d F Y') }}
                  <span class="text-[#E60049] font-black">({{ $tamu->start_time }})</span>
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-[#7B6759]">Check-Out</span>
                <span class="font-extrabold text-[#3B2314] text-right">
                  {{ \Carbon\Carbon::parse($tamu->end_date)->translatedFormat('d F Y') }}
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-[#7B6759]">Tanggal Reservasi</span>
                <span class="font-semibold text-[#7B6759] text-right">
                  {{ \Carbon\Carbon::parse($tamu->created_at ?? now())->translatedFormat('d F Y, H:i') }} WIB
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- COLUMN 2: ROOM & PAYMENT SUMMARY -->
        <div class="space-y-6">
          <!-- ROOM CARD -->
          @php
            $kamar = $tamu->productKamarKosan;
            $kosan = $kamar ? $kamar->productKosan : null;
            $kamarImg = $kamar && $kamar->productKamarImageKosan ? $kamar->productKamarImageKosan->first() : null;
          @endphp
          <div class="bg-[#FFF8F1] p-5 rounded-2xl border border-[#E9DDD2]">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#8E7B6D] mb-3 flex items-center gap-2">
              <i class="fa-solid fa-door-closed text-[#00A896]"></i> Informasi Kamar & Properti
            </h3>
            <div class="flex gap-4 items-center">
              <div class="w-16 h-16 rounded-xl bg-[#EDE4DC] overflow-hidden shrink-0 border border-[#E9DDD2]">
                @if($kamarImg)
                  <img src="{{ asset('storage/' . $kamarImg->image) }}" alt="{{ $kamar->room }}" class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full flex items-center justify-center text-[#9A887A]">
                    <i class="fa-solid fa-bed text-xl"></i>
                  </div>
                @endif
              </div>
              <div>
                <h4 class="font-black text-sm text-[#3B2314]">{{ $kamar->room ?? 'Unit Kamar' }}</h4>
                <p class="text-xs text-[#7B6759] mt-0.5">{{ $kosan->title ?? 'Sinar Citra Lestari' }}</p>
                <span class="inline-flex items-center gap-1 text-[11px] text-[#9A887A] mt-1">
                  <i class="fa-solid fa-location-dot text-[#E60049] text-[10px]"></i>
                  {{ $kosan->wilayah ?? 'Lokasi Terdaftar' }}
                </span>
              </div>
            </div>
          </div>

          <!-- PAYMENT SUMMARY -->
          <div class="bg-[#3B2314] p-5 rounded-2xl text-white">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#F3A833] mb-3 flex items-center gap-2">
              <i class="fa-solid fa-receipt"></i> Rincian Pembayaran
            </h3>
            <div class="space-y-2 text-xs">
              <div class="flex justify-between text-white/70">
                <span>Metode Pembayaran</span>
                <span class="font-bold text-white uppercase">{{ $tamu->payment_method }}</span>
              </div>
              <div class="flex justify-between text-white/70">
                <span>Status Verifikasi</span>
                <span class="font-bold text-[#F3A833]">{{ $tamu->status === 'approved' ? 'LUNAS / DISETUJUI' : 'MENUNGGU VERIFIKASI' }}</span>
              </div>
              <div class="h-px bg-white/15 my-2"></div>
              <div class="flex justify-between items-baseline pt-1">
                <span class="text-xs font-bold text-white/80">Total Tagihan:</span>
                <span class="text-xl sm:text-2xl font-black text-[#F3A833]">
                  Rp {{ number_format($tamu->total_price, 0, ',', '.') }}
                </span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- ACTION BUTTONS SECTION -->
      <div class="p-6 sm:p-8 bg-[#FFF8F1] border-t border-[#E9DDD2]">
        <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
          
          <!-- PDF INVOICE DOWNLOAD -->
          <a
            href="{{ route('booking.download.invoice', $tamu->access_token) }}"
            target="_blank"
            class="shine w-full sm:w-auto px-6 py-3.5 bg-[#E60049] hover:bg-[#C90040] text-white font-extrabold text-xs sm:text-sm rounded-2xl transition-all shadow-md active:scale-95 flex items-center justify-center gap-2.5"
          >
            <i class="fa-solid fa-file-pdf text-base"></i>
            <span>Unduh Bukti Invoice PDF</span>
          </a>

          @php
            $waMessage = "Halo Admin Sinar Citra Lestari,\nSaya ingin mengonfirmasi booking kamar kos:\n\n"
              . "• Kode Booking: #BOOK-" . str_pad($tamu->id, 5, '0', STR_PAD_LEFT) . "\n"
              . "• Nama Penyewa: " . $tamu->name . "\n"
              . "• Properti: " . ($kosan->title ?? '-') . " (" . ($kamar->room ?? '-') . ")\n"
              . "• Check-in: " . \Carbon\Carbon::parse($tamu->start_date)->translatedFormat('d M Y') . " (" . $tamu->start_time . ")\n"
              . "• Total: Rp " . number_format($tamu->total_price, 0, ',', '.') . "\n\n"
              . "Mohon bantuannya untuk verifikasi. Terima kasih!";
            $waUrl = "https://wa.me/6281234567890?text=" . rawurlencode($waMessage);
          @endphp

          <!-- WHATSAPP CONFIRMATION BUTTON -->
          <a
            href="{{ $waUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full sm:w-auto px-6 py-3.5 bg-[#00A896] hover:bg-[#008f80] text-white font-extrabold text-xs sm:text-sm rounded-2xl transition-all shadow-md active:scale-95 flex items-center justify-center gap-2.5"
          >
            <i class="fa-brands fa-whatsapp text-base"></i>
            <span>Konfirmasi ke WhatsApp Admin</span>
          </a>

          <!-- BACK TO HOME BUTTON -->
          <a
            href="{{ route('home') }}"
            class="w-full sm:w-auto px-5 py-3.5 bg-white hover:bg-[#EDE4DC] text-[#3B2314] border border-[#E9DDD2] font-extrabold text-xs sm:text-sm rounded-2xl transition-all active:scale-95 flex items-center justify-center gap-2"
          >
            <i class="fa-solid fa-house text-xs"></i>
            <span>Beranda</span>
          </a>

        </div>
      </div>

    </div>

    <!-- NEXT STEPS / INFORMATION CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div class="bg-white p-5 rounded-2xl border border-[#E9DDD2] shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-[#E60049]/10 text-[#E60049] flex items-center justify-center text-base mb-3">
          <i class="fa-solid fa-envelope-circle-check"></i>
        </div>
        <h4 class="font-extrabold text-xs text-[#3B2314]">Cek Kotak Masuk Email</h4>
        <p class="text-[11px] text-[#7B6759] mt-1 leading-relaxed">
          Kami telah mengirimkan salinan bukti booking & invoice PDF resmi ke email <strong>{{ $tamu->email }}</strong>.
        </p>
      </div>

      <div class="bg-white p-5 rounded-2xl border border-[#E9DDD2] shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-[#00A896]/10 text-[#00A896] flex items-center justify-center text-base mb-3">
          <i class="fa-solid fa-headset"></i>
        </div>
        <h4 class="font-extrabold text-xs text-[#3B2314]">Verifikasi Cepat</h4>
        <p class="text-[11px] text-[#7B6759] mt-1 leading-relaxed">
          Admin kami akan memeriksa kelengkapan pembayaran dan menghubungi Anda dalam 1x24 jam untuk serah terima kunci.
        </p>
      </div>

      <div class="bg-white p-5 rounded-2xl border border-[#E9DDD2] shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-[#F3A833]/15 text-[#3B2314] flex items-center justify-center text-base mb-3">
          <i class="fa-solid fa-key"></i>
        </div>
        <h4 class="font-extrabold text-xs text-[#3B2314]">Check-In Tanpa Ribet</h4>
        <p class="text-[11px] text-[#7B6759] mt-1 leading-relaxed">
          Cukup tunjukkan kode booking <strong>#BOOK-{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }}</strong> kepada petugas kos saat tiba di lokasi.
        </p>
      </div>
    </div>

  </main>

  @include('frontend.footer')

  <!-- JAVASCRIPT COPY FUNCTION -->
  <script>
    function copyBookingCode(code) {
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(showCopiedFeedback).catch(() => fallbackCopy(code));
      } else {
        fallbackCopy(code);
      }
    }

    function fallbackCopy(text) {
      const textArea = document.createElement("textarea");
      textArea.value = text;
      textArea.style.position = "fixed";
      textArea.style.left = "-999999px";
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      try {
        document.execCommand('copy');
        showCopiedFeedback();
      } catch (err) {
        alert("Gagal menyalin kode booking.");
      }
      document.body.removeChild(textArea);
    }

    function showCopiedFeedback() {
      const btn = document.getElementById('btn-copy-code');
      const textSpan = document.getElementById('copy-btn-text');
      if (btn && textSpan) {
        const originalText = textSpan.textContent;
        textSpan.textContent = 'Tersalin! ✅';
        btn.classList.add('bg-emerald-600', 'text-white');
        setTimeout(() => {
          textSpan.textContent = originalText;
          btn.classList.remove('bg-emerald-600', 'text-white');
        }, 2000);
      }
    }
  </script>

</body>
</html>
