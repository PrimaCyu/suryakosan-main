@php
    $pricesByKategori = [];
    if ($kamar && $kamar->priceKamar) {
        foreach ($kamar->priceKamar as $p) {
            $pricesByKategori[strtolower($p->kategori)] = $p;
        }
    }

    $kategoriOrdered = [
        'jam' => ['title' => 'Per Jam (Transit)', 'unit' => 'jam'],
        'hari' => ['title' => 'Per Hari', 'unit' => 'hari'],
        'minggu' => ['title' => 'Per Minggu', 'unit' => 'minggu'],
        'bulan' => ['title' => 'Per Bulan', 'unit' => 'bulan'],
        'tahun' => ['title' => 'Per Tahun', 'unit' => 'tahun'],
    ];
@endphp

<div class="lg:col-span-5 reveal">
    <div class="bg-slate-50/80 p-5 rounded-3xl border border-slate-200/80 space-y-4">

        <div class="flex items-center gap-2 text-slate-800 font-extrabold text-sm">
            <i class="fa-solid fa-tag text-cyan-600"></i>
            <span>Rincian Informasi Harga</span>
        </div>

        <div class="space-y-3">
            @foreach($kategoriOrdered as $katKey => $meta)
                @if(isset($pricesByKategori[$katKey]))
                    @php $priceObj = $pricesByKategori[$katKey]; @endphp
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $meta['title'] }}</span>
                                <div class="text-xl font-extrabold text-slate-900 mt-0.5">
                                    Rp {{ number_format($priceObj->price, 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">/{{ $meta['unit'] }}</span>
                                </div>
                                @if($priceObj->discount > 0)
                                    <div class="text-[11px] text-emerald-600 font-semibold mt-1">
                                        Diskon {{ $priceObj->discount }}%
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            @if(empty($pricesByKategori))
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm text-center py-6 text-slate-400 text-xs">
                    Harga belum ditentukan untuk unit ini.
                </div>
            @endif
        </div>

        <p class="text-[10px] text-slate-400 flex items-start gap-1 pt-1 leading-normal">
            <i class="fa-solid fa-circle-info text-[10px] mt-0.5"></i>
            <span>Harga di atas merupakan tarif resmi kamar. Pemilihan metode pembayaran final dilakukan pada saat pemesanan.</span>
        </p>

    </div>
</div>
