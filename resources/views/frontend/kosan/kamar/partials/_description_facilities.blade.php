@php
    $allFasilitas = array_filter(array_map('trim', explode(',', $kamar->fasilitas ?? '')));
    $buildingFasilitas = array_filter(array_map('trim', explode(',', $kamar->productKosan->fasilitas ?? '')));
    $wilayahNama = $kamar->productKosan->wilayah ?? 'Bali';

    $iconMap = [
        'AC' => 'fa-snowflake',
        'Kamar Mandi Dalam' => 'fa-bath',
        'Water Heater / Air Hangat' => 'fa-temperature-arrow-up',
        'Water Heater' => 'fa-temperature-arrow-up',
        'Kasur Springbed' => 'fa-bed',
        'Kasur' => 'fa-bed',
        'Lemari Pakaian' => 'fa-door-closed',
        'Lemari' => 'fa-door-closed',
        'Meja & Kursi Belajar' => 'fa-chair',
        'Meja' => 'fa-table',
        'TV / Smart TV' => 'fa-tv',
        'Wastafel' => 'fa-sink',
        'Balkon Kamar' => 'fa-person-through-window',
        'Jendela / Ventilasi Bagus' => 'fa-wind',
        'Kipas Angin' => 'fa-fan',
        'Kulkas Mini' => 'fa-box',

        'Wi-Fi / Internet' => 'fa-wifi',
        'Parkir Mobil' => 'fa-car',
        'Parkir Motor' => 'fa-motorcycle',
        'Dapur Bersama' => 'fa-kitchen-set',
        'CCTV 24 Jam' => 'fa-video',
        'Keamanan / Satpam' => 'fa-user-shield',
        'Ruang Tamu Bersama' => 'fa-couch',
        'Ruang Jemur' => 'fa-shirt',
        'Mesin Cuci Bersama' => 'fa-soap',
        'Kulkas Bersama' => 'fa-box',
        'Air Minum / Dispenser' => 'fa-glass-water',
        'Penjaga Kos' => 'fa-user-clock',
        'Listrik Gratis / Included' => 'fa-bolt',
        'Bebas Jam Malam' => 'fa-key',
        'Akses Kartu / Smart Lock' => 'fa-id-card',
        'Balkon / Rooftop' => 'fa-building',
        'Musholla' => 'fa-mosque',
        'Gazebo / Area Santai' => 'fa-umbrella-beach'
    ];
@endphp

<div class="space-y-7">
    <!-- 1. DESKRIPSI LENGKAP KAMAR -->
    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#3B2314] flex items-center gap-2">
                <i class="fa-solid fa-align-left text-[#00A896]"></i>
                Deskripsi Kamar
            </h3>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-[#EADFD4] text-[#6E594A] text-[11px] font-bold rounded-full shadow-sm">
                <i class="fa-solid fa-eye text-[#00A896]"></i> {{ number_format($kamar->views ?? 0, 0, ',', '.') }} Dilihat
            </span>
        </div>
        <div class="text-xs sm:text-sm text-[#5D4A3D] leading-[1.8] bg-white p-4 sm:p-5 rounded-2xl border border-[#EADFD4] shadow-sm space-y-2">
            @if($kamar && !empty(trim(strip_tags($kamar->description ?? ''))))
                {!! \App\Models\Artikel::sanitizeHtml($kamar->description) !!}
            @elseif($kamar && $kamar->productKosan && !empty(trim(strip_tags($kamar->productKosan->description ?? ''))))
                {!! \App\Models\Artikel::sanitizeHtml($kamar->productKosan->description) !!}
            @else
                <p>Unit kamar kos nyaman, bersih, dan terang berlokasi di area strategis kawasan {{ $wilayahNama }}. Menawarkan privasi maksimal dengan sirkulasi udara yang baik dan siap huni kapan saja.</p>
            @endif
        </div>
    </section>

    <!-- 2. FASILITAS PRIBADI KAMAR -->
    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#3B2314] flex items-center gap-2">
                <i class="fa-solid fa-couch text-[#E60049]"></i>
                Fasilitas Kamar Pribadi
            </h3>
            <span class="text-[11px] font-bold text-[#8F7765]">{{ count($allFasilitas) }} Fasilitas</span>
        </div>

        @if(count($allFasilitas) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                @foreach($allFasilitas as $fasName)
                    @php $iconClass = $iconMap[$fasName] ?? 'fa-check'; @endphp
                    <div class="bg-white p-3.5 rounded-2xl border border-[#EADFD4] shadow-sm flex items-center gap-3 hover:border-[#E60049] transition-colors group">
                        <div class="w-8 h-8 rounded-xl bg-[#E60049]/10 text-[#E60049] group-hover:bg-[#E60049] group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                            <i class="fa-solid {{ $iconClass }} text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-[#3B2314] leading-snug">{{ $fasName }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white p-4 rounded-2xl border border-[#EADFD4] text-[#9A8675] text-xs">
                Fasilitas kamar standar lengkap. Silakan tanyakan ke pengelola untuk perlengkapan tambahan.
            </div>
        @endif
    </section>

    <!-- 3. FASILITAS BERSAMA GEDUNG KOS -->
    @if(count($buildingFasilitas) > 0)
        <section class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-wider text-[#3B2314] flex items-center gap-2">
                    <i class="fa-solid fa-building text-[#00A896]"></i>
                    Fasilitas Bersama Gedung Kos
                </h3>
                <span class="text-[11px] font-bold text-[#8F7765]">{{ count($buildingFasilitas) }} Fasilitas</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                @foreach($buildingFasilitas as $bFas)
                    @php $bIcon = $iconMap[$bFas] ?? 'fa-circle-check'; @endphp
                    <div class="bg-white p-3.5 rounded-2xl border border-[#EADFD4] shadow-sm flex items-center gap-3 hover:border-[#00A896] transition-colors group">
                        <div class="w-8 h-8 rounded-xl bg-[#00A896]/10 text-[#00A896] group-hover:bg-[#00A896] group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                            <i class="fa-solid {{ $bIcon }} text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-[#3B2314] leading-snug">{{ $bFas }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
