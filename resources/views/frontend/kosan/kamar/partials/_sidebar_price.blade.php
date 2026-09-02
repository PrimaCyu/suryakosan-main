@php
    $pricesByKategori = [];
    if ($kamar && $kamar->priceKamar) {
        foreach ($kamar->priceKamar as $p) {
            $pricesByKategori[strtolower($p->kategori)] = $p;
        }
    }

    $kategoriOrdered = [
        'jam' => ['title' => 'Per Jam (Transit)', 'unit' => 'jam', 'icon' => 'fa-clock'],
        'hari' => ['title' => 'Per Hari', 'unit' => 'hari', 'icon' => 'fa-calendar-day'],
        'minggu' => ['title' => 'Per Minggu', 'unit' => 'minggu', 'icon' => 'fa-calendar-week'],
        'bulan' => ['title' => 'Per Bulan', 'unit' => 'bulan', 'icon' => 'fa-calendar'],
        'tahun' => ['title' => 'Per Tahun', 'unit' => 'tahun', 'icon' => 'fa-calendar-days'],
    ];
@endphp

<div class="lg:col-span-5 reveal lg:sticky lg:top-24">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl space-y-5">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-slate-900 font-extrabold text-sm">
                <i class="fa-solid fa-tags text-cyan-600"></i>
                <span>Rincian Tarif Kamar</span>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-700 bg-cyan-50 px-2.5 py-1 rounded-full border border-cyan-100">
                Tarif Resmi
            </span>
        </div>

        <div class="space-y-3">
            @foreach($kategoriOrdered as $katKey => $meta)
                @if(isset($pricesByKategori[$katKey]))
                    @php $priceObj = $pricesByKategori[$katKey]; @endphp
                    <div class="bg-slate-50 hover:bg-cyan-50/50 p-4 rounded-2xl border border-slate-100 hover:border-cyan-200 transition-all duration-200 relative overflow-hidden group">
                        <div class="flex justify-between items-center">
                            <div class="space-y-0.5">
                                <span class="text-xs text-slate-500 font-semibold flex items-center gap-1.5">
                                    <i class="fa-regular {{ $meta['icon'] }} text-cyan-600 text-xs"></i>
                                    {{ $meta['title'] }}
                                </span>
                                <div class="text-lg font-extrabold text-slate-900">
                                    Rp {{ number_format($priceObj->price, 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">/{{ $meta['unit'] }}</span>
                                </div>
                            </div>
                            @if($priceObj->discount > 0)
                                <div class="text-[11px] bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full shadow-sm">
                                    Hemat {{ $priceObj->discount }}%
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            @if(empty($pricesByKategori))
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 text-center py-6 text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-exclamation text-2xl text-slate-300 mb-2 block"></i>
                    Harga resmi belum ditentukan untuk unit ini. Hubungi pemilik untuk informasi lebih lanjut.
                </div>
            @endif
        </div>

        <div class="pt-2 border-t border-slate-100">
            <a href="{{ route('form.booking.kamar', $kamar->id) }}" class="w-full py-3.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-extrabold rounded-2xl transition-all shadow-md hover:shadow-cyan-500/25 flex items-center justify-center gap-2 active:scale-95 text-xs sm:text-sm">
                <span>Pesan Sekarang</span> <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <p class="text-[10px] text-slate-400 flex items-start gap-1.5 leading-relaxed pt-1">
            <i class="fa-solid fa-shield-check text-cyan-600 text-xs mt-0.5 shrink-0"></i>
            <span>Harga di atas belum termasuk PPN 12% dan akan dihitung otomatis saat pengisian durasi sewa.</span>
        </p>

    </div>
</div>
