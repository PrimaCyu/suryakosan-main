@php
    if (empty($images)) {
        $images = [];
        if ($kamar && $kamar->productKamarImageKosan && $kamar->productKamarImageKosan->count() > 0) {
            foreach ($kamar->productKamarImageKosan as $imgObj) {
                $images[] = asset('storage/' . $imgObj->image);
            }
        }
    }

    if (empty($images)) {
        if ($kamar && $kamar->productKosan && $kamar->productKosan->productImageKosan && $kamar->productKosan->productImageKosan->count() > 0) {
            foreach ($kamar->productKosan->productImageKosan as $kosImg) {
                $images[] = asset('storage/' . $kosImg->image);
            }
        } else {
            $images = [
                'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=1200&q=80'
            ];
        }
    }

    $namaKamar = $kamar->room ?? 'Kamar Exclusive';
    $totalCount = count($images);
@endphp

@if($totalCount > 0)
<div class="relative">
    <!-- GALLERY BENTO GRID -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 rounded-3xl overflow-hidden">
        
        <!-- Foto Utama Besar (Kiri) -->
        <div class="{{ $totalCount > 1 ? 'md:col-span-7' : 'md:col-span-12' }}">
            <div
                class="relative h-[260px] sm:h-[380px] lg:h-[440px] rounded-2xl sm:rounded-3xl overflow-hidden cursor-pointer group shadow-sm bg-[#EDE4DC]"
                onclick="openGallery(0)"
                title="Klik untuk melihat foto layar penuh"
            >
                <img
                    src="{{ $images[0] }}"
                    alt="{{ $namaKamar }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-[#3B2314]/75 via-[#3B2314]/15 to-transparent pointer-events-none"></div>

                <div class="absolute left-4 bottom-4 right-4 flex items-end justify-between gap-3 text-white pointer-events-none">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#E60049] rounded-full text-[10px] font-black uppercase tracking-wider shadow-md">
                            <i class="fa-solid fa-camera"></i>
                            Foto Utama Kamar
                        </span>
                        <p class="mt-1.5 text-xs font-bold text-white/90 drop-shadow-sm">Klik untuk perbesar layar penuh</p>
                    </div>

                    <span class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0 group-hover:bg-[#E60049] transition-colors shadow-md">
                        <i class="fa-solid fa-expand text-sm"></i>
                    </span>
                </div>
            </div>
        </div>

        @if($totalCount > 1)
        <!-- Grid Foto Samping (Kanan) -->
        <div class="md:col-span-5 grid grid-cols-2 gap-3 sm:gap-4 h-[260px] sm:h-[380px] lg:h-[440px]">
            @for ($i = 1; $i <= 3; $i++)
                @if (isset($images[$i]))
                    <div
                        class="relative h-full overflow-hidden rounded-2xl sm:rounded-3xl bg-[#EDE4DC] cursor-pointer group shadow-sm"
                        onclick="openGallery({{ $i }})"
                        title="Klik untuk melihat foto {{ $i + 1 }}"
                    >
                        <img
                            src="{{ $images[$i] }}"
                            alt="Foto {{ $i + 1 }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out"
                        >
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="w-7 h-7 rounded-xl bg-[#3B2314]/80 text-white flex items-center justify-center text-[10px] shadow">
                                <i class="fa-solid fa-expand"></i>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="h-full rounded-2xl sm:rounded-3xl bg-[#F2E9E1] flex flex-col items-center justify-center text-[#B6A393] gap-1">
                        <i class="fa-regular fa-image text-xl"></i>
                        <span class="text-[9px] font-bold">Foto Tambahan</span>
                    </div>
                @endif
            @endfor

            <!-- Slot ke-4 (dengan Badge "+X Foto Lain" jika foto > 5) -->
            @if(isset($images[4]))
                <div
                    class="relative h-full overflow-hidden rounded-2xl sm:rounded-3xl bg-[#EDE4DC] cursor-pointer group shadow-sm"
                    onclick="openGallery(4)"
                    title="Klik untuk melihat semua foto"
                >
                    <img
                        src="{{ $images[4] }}"
                        alt="Foto 5"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out"
                    >

                    @if($totalCount > 5)
                        <!-- Overlay Badge "+X Foto Lain" -->
                        <div class="absolute inset-0 bg-[#3B2314]/75 group-hover:bg-[#3B2314]/85 backdrop-blur-[2px] transition-all flex flex-col items-center justify-center text-white gap-1.5 p-2 text-center">
                            <span class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                                <i class="fa-regular fa-images text-sm"></i>
                            </span>
                            <span class="text-xs font-black tracking-wide">+{{ $totalCount - 5 }} Foto Lain</span>
                            <span class="text-[9px] font-bold text-white/75">Lihat Semua</span>
                        </div>
                    @else
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="w-7 h-7 rounded-xl bg-[#3B2314]/80 text-white flex items-center justify-center text-[10px] shadow">
                                <i class="fa-solid fa-expand"></i>
                            </span>
                        </div>
                    @endif
                </div>
            @else
                <div class="h-full rounded-2xl sm:rounded-3xl bg-[#F2E9E1] flex flex-col items-center justify-center text-[#B6A393] gap-1">
                    <i class="fa-regular fa-image text-xl"></i>
                    <span class="text-[9px] font-bold">Foto Tambahan</span>
                </div>
            @endif
        </div>
        @endif

    </div>

    <!-- Tombol Mengambang "Lihat Semua Foto" -->
    <div class="absolute bottom-3 right-3 sm:bottom-4 sm:right-4 z-10">
        <button
            type="button"
            onclick="openGallery(0)"
            class="px-3.5 sm:px-4 py-2 bg-[#3B2314]/90 hover:bg-[#E60049] text-white text-xs font-black rounded-xl shadow-lg backdrop-blur-md flex items-center gap-2 transition-all active:scale-95"
        >
            <i class="fa-regular fa-images text-[#F3A833]"></i>
            <span>Semua Foto ({{ $totalCount }})</span>
        </button>
    </div>
</div>
@else
<div class="w-full h-[220px] sm:h-[320px] bg-[#EDE4DC] rounded-3xl flex flex-col items-center justify-center text-[#9A8675] space-y-2">
    <i class="fa-regular fa-image text-4xl text-[#F3A833]"></i>
    <span class="text-xs sm:text-sm font-semibold">Belum ada foto kamar yang diunggah</span>
</div>
@endif
