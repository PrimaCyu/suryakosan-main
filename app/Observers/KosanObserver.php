<?php

namespace App\Observers;

use App\Models\ProductKosan;
use Illuminate\Support\Facades\Cache;

class KosanObserver
{
    /**
     * Handle the ProductKosan "saved" event.
     */
    public function saved(ProductKosan $kosan): void
    {
        $this->clearCache($kosan);
    }

    /**
     * Handle the ProductKosan "deleted" event.
     */
    public function deleted(ProductKosan $kosan): void
    {
        $this->clearCache($kosan);
    }

    /**
     * Handle the ProductKosan "restored" event.
     */
    public function restored(ProductKosan $kosan): void
    {
        $this->clearCache($kosan);
    }

    /**
     * Clear associated caches for Kosan and Frontend.
     */
    protected function clearCache(ProductKosan $kosan): void
    {
        Cache::forget('home_kamar_list');

        if (!empty($kosan->slug)) {
            Cache::forget("kosan_detail_{$kosan->slug}");
        }

        if ($kosan->wasChanged('slug')) {
            $oldSlug = $kosan->getOriginal('slug');
            if (!empty($oldSlug)) {
                Cache::forget("kosan_detail_{$oldSlug}");
            }
        }
    }
}
