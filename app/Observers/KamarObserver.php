<?php

namespace App\Observers;

use App\Models\ProductKamarKosan;
use App\Models\ProductKosan;
use Illuminate\Support\Facades\Cache;

class KamarObserver
{
    /**
     * Handle the ProductKamarKosan "saved" event.
     */
    public function saved(ProductKamarKosan $kamar): void
    {
        $this->clearCache($kamar);
    }

    /**
     * Handle the ProductKamarKosan "deleted" event.
     */
    public function deleted(ProductKamarKosan $kamar): void
    {
        $this->clearCache($kamar);
    }

    /**
     * Handle the ProductKamarKosan "restored" event.
     */
    public function restored(ProductKamarKosan $kamar): void
    {
        $this->clearCache($kamar);
    }

    /**
     * Clear associated caches for Kamar and its parent Kosan.
     */
    protected function clearCache(ProductKamarKosan $kamar): void
    {
        Cache::forget('home_kamar_list');

        $kosan = $kamar->productKosan ?? ProductKosan::find($kamar->product_kosan_id);
        if ($kosan && !empty($kosan->slug)) {
            Cache::forget("kosan_detail_{$kosan->slug}");
        }

        if ($kamar->wasChanged('product_kosan_id')) {
            $oldKosanId = $kamar->getOriginal('product_kosan_id');
            if (!empty($oldKosanId)) {
                $oldKosan = ProductKosan::find($oldKosanId);
                if ($oldKosan && !empty($oldKosan->slug)) {
                    Cache::forget("kosan_detail_{$oldKosan->slug}");
                }
            }
        }
    }
}
