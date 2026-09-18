@php
    $pricesByKategori = [];
    if ($kamar && $kamar->priceKamar) {
        foreach ($kamar->priceKamar as $p) {
            $pricesByKategori[strtolower($p->kategori)] = $p;
        }
    }

    $kategoriOrdered = [
        'bulan' => ['title' => 'Per Bulan (Bulanan)', 'unit' => 'bulan', 'icon' => 'fa-calendar'],
        'tahun' => ['title' => 'Per Tahun (Tahunan)', 'unit' => 'tahun', 'icon' => 'fa-calendar-days'],
    ];
@endphp

@php
    $pricesByKategori = [];
    if ($kamar && $kamar->priceKamar) {
        foreach ($kamar->priceKamar as $p) {
            $pricesByKategori[strtolower($p->kategori)] = $p;
        }
    }

    $kategoriOrdered = [
        'bulan' => ['title' => 'Per Bulan (Bulanan)', 'unit' => 'bulan', 'icon' => 'fa-calendar'],
        'tahun' => ['title' => 'Per Tahun (Tahunan)', 'unit' => 'tahun', 'icon' => 'fa-calendar-days'],
    ];
@endphp

<div class="space-y-4">
    <div class="space-y-3">
        @foreach($kategoriOrdered as $katKey => $meta)
            @if(isset($pricesByKategori[$katKey]))
                @php $priceObj = $pricesByKategori[$katKey]; @endphp
                <div class="bg-[#FFF8F1] hover:bg-[#F3A833]/15 p-4 sm:p-5 rounded-2xl border border-[#E9DDD2] hover:border-[#F3A833] transition-all duration-200 relative overflow-hidden group shadow-sm">
                    <div class="flex justify-between items-center gap-3">
                        <div class="space-y-1">
                            <span class="text-xs text-[#7B6759] font-bold flex items-center gap-2">
                                <i class="fa-regular {{ $meta['icon'] }} text-[#00A896]"></i>
                                {{ $meta['title'] }}
                            </span>
                            <div class="text-xl sm:text-2xl font-black text-[#3B2314]">
                                Rp {{ number_format($priceObj->price, 0, ',', '.') }} <span class="text-xs text-[#9A887A] font-bold">/{{ $meta['unit'] }}</span>
                            </div>
                        </div>
                        @if($priceObj->discount > 0)
                            <div class="text-[11px] bg-[#E60049] text-white font-black px-3 py-1.5 rounded-full shadow-md shrink-0">
                                Diskon {{ $priceObj->discount }}%
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach

        @if(empty($pricesByKategori))
            <div class="bg-[#FFF8F1] p-6 rounded-2xl border border-[#E9DDD2] text-center py-6 text-[#9A887A] text-xs">
                <i class="fa-solid fa-circle-exclamation text-2xl text-[#F3A833] mb-2 block"></i>
                Harga resmi belum ditentukan untuk unit ini. Hubungi pemilik untuk informasi lebih lanjut.
            </div>
        @endif
    </div>

    <div class="pt-3 border-t border-[#E9DDD2]">
        <a href="{{ route('form.booking.kamar', $kamar->id) }}" class="shine w-full py-4 bg-[#E60049] hover:bg-[#C90040] text-white font-black rounded-2xl transition-all shadow-lg hover:shadow-[#E60049]/30 flex items-center justify-center gap-2 active:scale-95 text-xs sm:text-sm">
            <span>Pesan Unit Ini Sekarang</span> <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>

    <p class="text-[10px] text-[#8E7B6D] flex items-start gap-2 leading-relaxed pt-1">
        <i class="fa-solid fa-shield-halved text-[#00A896] text-xs mt-0.5 shrink-0"></i>
        <span>Harga belum termasuk PPN 12% dan akan dihitung otomatis saat konfirmasi durasi sewa di halaman booking.</span>
    </p>
</div>
