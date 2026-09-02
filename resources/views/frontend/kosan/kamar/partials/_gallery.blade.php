@php
    $images = [];
    if ($kamar && $kamar->productKamarImageKosan && $kamar->productKamarImageKosan->count() > 0) {
        foreach ($kamar->productKamarImageKosan as $imgObj) {
            $images[] = asset('storage/' . $imgObj->image);
        }
    }

    $namaKamar = $kamar->room ?? 'Kamar Exclusive';
@endphp

@if(count($images) > 0)
<!-- GALLERY GRID RESPONSIVE (UTAMA & FOTO SAMPING) -->
<section class="grid grid-cols-1 md:grid-cols-12 gap-3 rounded-3xl overflow-hidden reveal cursor-pointer">
    <!-- Foto Utama Besar (Kiri) -->
    <div class="md:col-span-7 h-[240px] sm:h-[380px] relative group overflow-hidden" onclick="openGallery(0)">
        <img src="{{ $images[0] }}" alt="{{ $namaKamar }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors"></div>
    </div>

    <!-- Grid Foto Samping (Kanan) -->
    <div class="md:col-span-5 grid grid-cols-2 gap-3 h-[240px] sm:h-[380px]">
        @for ($i = 1; $i <= 3; $i++)
            @if (isset($images[$i]))
                <div class="relative group overflow-hidden rounded-2xl" onclick="openGallery({{ $i }})">
                    <img src="{{ $images[$i] }}" alt="Foto {{ $i }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            @else
                <div class="relative group overflow-hidden rounded-2xl bg-slate-200 flex items-center justify-center text-slate-400">
                    <i class="fa-regular fa-image text-2xl"></i>
                </div>
            @endif
        @endfor

        <!-- Foto ke-4 dengan Badge Overlay -->
        <div class="relative group overflow-hidden rounded-2xl" onclick="openGallery(3)">
            <img src="{{ $images[3] ?? $images[0] }}" alt="Area Kamar" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-black/60 group-hover:bg-black/70 transition-colors flex items-center justify-center text-white gap-2 px-2 text-center">
                <i class="fa-regular fa-images text-sm sm:text-base"></i>
                <span id="gallery-badge-text" class="text-xs font-bold">+{{ max(0, count($images) - 4) }} Foto Lain</span>
            </div>
        </div>
    </div>
</section>
@else
<div class="w-full h-[200px] sm:h-[280px] bg-slate-100 border border-slate-200 rounded-3xl flex flex-col items-center justify-center text-slate-400 space-y-2 reveal">
    <i class="fa-regular fa-image text-4xl"></i>
    <span class="text-xs sm:text-sm font-semibold">Belum ada foto kamar yang diunggah</span>
</div>
@endif
