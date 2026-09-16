<?php

namespace App\Observers;

use App\Models\ProductKamarKosan;
use App\Models\ProductKosan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class KamarSubItemObserver
{
    /**
     * Handle the Model "saved" event.
     */
    public function saved(Model $model): void
    {
        $this->clearCache($model);
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->clearCache($model);
    }

    /**
     * Clear associated caches for related Kosan and Kamar.
     */
    protected function clearCache(Model $model): void
    {
        Cache::forget('home_kamar_list');

        // If the model belongs directly to product_kamar_kosan_id (e.g. PriceKamar, FasilitasKamar, Tamu)
        if (!empty($model->product_kamar_kosan_id)) {
            $kamar = ProductKamarKosan::find($model->product_kamar_kosan_id);
            if ($kamar && !empty($kamar->product_kosan_id)) {
                $kosan = ProductKosan::find($kamar->product_kosan_id);
                if ($kosan && !empty($kosan->slug)) {
                    Cache::forget("kosan_detail_{$kosan->slug}");
                }
            }
        }

        // If the model belongs directly to product_kosan_id (e.g. ProductImageKosan)
        if (!empty($model->product_kosan_id)) {
            $kosan = ProductKosan::find($model->product_kosan_id);
            if ($kosan && !empty($kosan->slug)) {
                Cache::forget("kosan_detail_{$kosan->slug}");
            }
        }
    }
}
