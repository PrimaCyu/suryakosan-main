@php
    $allFasilitas = array_filter(array_map('trim', explode(',', $kamar->fasilitas ?? '')));
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

<div class="lg:col-span-7 space-y-8 reveal">
    <!-- Deskripsi Kamar -->
    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-extrabold text-slate-900">Deskripsi Kamar</h2>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-cyan-50 border border-cyan-200 text-cyan-700 text-xs font-semibold rounded-full shadow-sm">
                <i class="fa-solid fa-eye text-cyan-600"></i> {{ number_format($kamar->views ?? 0, 0, ',', '.') }} Dilihat
            </span>
        </div>
        <div class="text-xs sm:text-sm text-slate-600 leading-relaxed space-y-2">
            @if($kamar && $kamar->description)
                {!! $kamar->description !!}
            @elseif($kamar && $kamar->productKosan && $kamar->productKosan->description)
                {!! $kamar->productKosan->description !!}
            @else
                <p>Kamar kos nyaman dan bersih berada di lokasi strategis wilayah {{ $wilayahNama }}. Dilengkapi dengan fasilitas pendukung penuh untuk kenyamanan Anda.</p>
            @endif
        </div>
    </section>

    <!-- Fasilitas Kamar -->
    <section class="space-y-4">
        <h2 class="text-lg font-extrabold text-slate-900">Fasilitas Kamar</h2>

        @if(count($allFasilitas) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($allFasilitas as $fasName)
                    @php
                        $iconClass = $iconMap[$fasName] ?? 'fa-check';
                    @endphp
                    <div class="bg-white py-4 px-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center gap-2 hover:shadow-md transition-shadow">
                        <i class="fa-solid {{ $iconClass }} text-2xl text-cyan-600"></i>
                        <span class="text-xs font-bold text-slate-800">{{ $fasName }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-400">Belum ada rincian fasilitas khusus.</p>
        @endif
    </section>
</div>
