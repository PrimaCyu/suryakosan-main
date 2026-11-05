@php
    $pricesByKategori = [];
    if ($kamar && $kamar->priceKamar) {
        foreach ($kamar->priceKamar as $p) {
            $pricesByKategori[strtolower($p->kategori)] = $p;
        }
    }

    $kategoriOrdered = [
        'bulan' => ['title' => 'Tarif Bulanan', 'unit' => 'bulan', 'icon' => 'fa-calendar'],
        'tahun' => ['title' => 'Tarif Tahunan', 'unit' => 'tahun', 'icon' => 'fa-calendar-days'],
    ];

    $monthlyPriceObj = $pricesByKategori['bulan'] ?? null;
    $yearlyPriceObj = $pricesByKategori['tahun'] ?? null;

    $yearlySavings = 0;
    if ($monthlyPriceObj && $yearlyPriceObj) {
        $totalMonthlyForYear = $monthlyPriceObj->price * 12;
        $yearlySavings = max(0, $totalMonthlyForYear - $yearlyPriceObj->price);
    }

    $roomStatus = $kamar->room_status ?? 'kosong';
    $isOccupied = ($roomStatus === 'terisi');
    $activeTenant = $kamar->active_tenant;
    $endDateFormatted = null;
    if ($activeTenant && !empty($activeTenant->end_date)) {
        try {
            $endDateFormatted = \Carbon\Carbon::parse($activeTenant->end_date)->translatedFormat('d F Y');
        } catch (\Exception $e) {
            $endDateFormatted = date('d-m-Y', strtotime($activeTenant->end_date));
        }
    }

    // Resolusi nomor WhatsApp
    $waNumber = '6281234567890';
    if (isset($globalSosmed)) {
        foreach ($globalSosmed as $sm) {
            if (str_contains(strtolower($sm->title), 'whatsapp') || str_contains($sm->url, 'wa.me')) {
                preg_match('/[0-9]{9,15}/', $sm->url, $waMatch);
                if (!empty($waMatch[0])) {
                    $waNumber = $waMatch[0];
                    break;
                }
            }
        }
    }

    $kamarWaMsg = "Halo Admin Sinar Citra Lestari, saya tertarik dengan unit " . ($kamar->room ?? 'Kamar') . " di " . ($kamar->productKosan->title ?? 'Kos') . " yang saat ini sedang terisi. Apakah saya bisa masuk daftar antrean (waiting list) untuk periode sewa berikutnya?";
    $kamarWaUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($kamarWaMsg);
@endphp

<div class="space-y-4">
    <!-- Status Ketersediaan Real-Time -->
    @if($isOccupied)
        <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200/90 shadow-sm">
            <div class="flex items-center gap-2 text-rose-700 font-black text-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                <span>Status Unit: Terisi (Penuh)</span>
            </div>
            <p class="text-[11px] text-rose-900/80 mt-1.5 leading-relaxed">
                @if($endDateFormatted)
                    Unit ini sedang dihuni penyewa aktif hingga <strong>{{ $endDateFormatted }}</strong>. Anda dapat mendaftar antrean reservasi atau memilih kamar lain yang tersedia di kos ini.
                @else
                    Unit ini sedang terisi aktif. Hubungi pengelola untuk informasi jadwal ketersediaan berikutnya.
                @endif
            </p>
        </div>
    @elseif($roomStatus === 'pending')
        <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 shadow-sm flex items-center justify-between text-xs font-bold">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-clock text-amber-600"></i>
                <span>Menunggu Verifikasi</span>
            </span>
            <span class="text-[10px] bg-white px-2 py-0.5 rounded-md border border-amber-200">Dalam Proses</span>
        </div>
    @else
        <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm flex items-center justify-between text-xs font-bold">
            <span class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Unit Siap Huni</span>
            </span>
            <span class="text-[10px] bg-white px-2 py-0.5 rounded-md border border-emerald-200 font-black text-emerald-700">Kamar Kosong</span>
        </div>
    @endif

    <!-- Kotak Harga -->
    <div class="space-y-3">
        @foreach($kategoriOrdered as $katKey => $meta)
            @if(isset($pricesByKategori[$katKey]))
                @php 
                    $priceObj = $pricesByKategori[$katKey]; 
                    $hasDiscount = ($priceObj->discount > 0);
                    $originalPrice = $hasDiscount ? round($priceObj->price / (1 - ($priceObj->discount / 100))) : null;
                @endphp
                <div class="bg-[#FFF8F1] hover:bg-[#F3A833]/15 p-4 rounded-2xl border border-[#E9DDD2] hover:border-[#F3A833] transition-all duration-200 relative overflow-hidden group shadow-sm">
                    <div class="flex justify-between items-start gap-3">
                        <div class="space-y-1">
                            <span class="text-xs text-[#7B6759] font-bold flex items-center gap-2">
                                <i class="fa-regular {{ $meta['icon'] }} text-[#00A896]"></i>
                                {{ $meta['title'] }}
                            </span>

                            @if($hasDiscount && $originalPrice)
                                <div class="text-[11px] text-[#A69383] line-through font-semibold">
                                    Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                </div>
                            @endif

                            <div class="text-xl sm:text-2xl font-black text-[#3B2314]">
                                Rp {{ number_format($priceObj->price, 0, ',', '.') }} 
                                <span class="text-xs text-[#9A887A] font-bold">/{{ $meta['unit'] }}</span>
                            </div>

                            @if($katKey === 'tahun' && $yearlySavings > 0)
                                <div class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md inline-block mt-1">
                                    💰 Hemat Rp {{ number_format($yearlySavings, 0, ',', '.') }}/thn
                                </div>
                            @endif
                        </div>

                        @if($hasDiscount)
                            <div class="text-[11px] bg-[#E60049] text-white font-black px-2.5 py-1 rounded-full shadow-md shrink-0">
                                Diskon {{ $priceObj->discount }}%
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach

        @if(empty($pricesByKategori))
            <div class="bg-[#FFF8F1] p-5 rounded-2xl border border-[#E9DDD2] text-center text-[#9A887A] text-xs">
                <i class="fa-solid fa-circle-exclamation text-2xl text-[#F3A833] mb-2 block"></i>
                Tarif resmi belum ditentukan untuk unit ini. Silakan tanyakan ke pengelola kos.
            </div>
        @endif
    </div>

    <!-- Tombol Aksi Cerdas (Smart CTA) -->
    <div class="pt-2 space-y-2">
        @if($isOccupied)
            <a 
                href="{{ $kamarWaUrl }}" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl transition-all shadow-md hover:shadow-emerald-600/30 flex items-center justify-center gap-2 active:scale-95 text-xs sm:text-sm text-center"
            >
                <i class="fa-brands fa-whatsapp text-lg"></i>
                <span>Masuk Waiting List via WA</span>
            </a>

            @if($kamar->productKosan && $kamar->productKosan->productKamarKosan->count() > 0)
                <button 
                    type="button" 
                    onclick="scrollToSiblingRooms()" 
                    class="w-full py-3 px-4 bg-white hover:bg-[#F3A833]/15 text-[#3B2314] border border-[#E9DDD2] font-black rounded-2xl transition-all shadow-sm flex items-center justify-center gap-2 text-xs"
                >
                    <i class="fa-solid fa-door-open text-[#00A896]"></i>
                    <span>Cek Kamar Lain di Kos Ini</span>
                </button>
            @endif
        @else
            <a 
                href="{{ route('form.booking.kamar', $kamar->id) }}" 
                class="shine w-full py-4 bg-[#E60049] hover:bg-[#C90040] text-white font-black rounded-2xl transition-all shadow-lg hover:shadow-[#E60049]/30 flex items-center justify-center gap-2 active:scale-95 text-xs sm:text-sm"
            >
                <i class="fa-solid fa-bolt text-[#F3A833]"></i>
                <span>Pesan Unit Ini Sekarang</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        @endif
    </div>

    <p class="text-[10px] text-[#8E7B6D] flex items-start gap-2 leading-relaxed pt-1">
        <i class="fa-solid fa-shield-halved text-[#00A896] text-xs mt-0.5 shrink-0"></i>
        <span>Harga resmi tertera transparan. Booking diverifikasi langsung oleh pengelola resmi Sinar Citra Lestari.</span>
    </p>
</div>
