<?php

namespace App\Observers;

use App\Models\Artikel;
use Illuminate\Support\Facades\Cache;

class ArtikelObserver
{
    /**
     * Handle the Artikel "saved" event.
     */
    public function saved(Artikel $artikel): void
    {
        $this->clearCache($artikel);
    }

    /**
     * Handle the Artikel "deleted" event.
     */
    public function deleted(Artikel $artikel): void
    {
        $this->clearCache($artikel);
    }

    /**
     * Clear associated caches for Artikel.
     */
    protected function clearCache(Artikel $artikel): void
    {
        Cache::forget('home_latest_artikels');

        if (!empty($artikel->slug)) {
            Cache::forget("artikel_detail_{$artikel->slug}");
        }

        if (!empty($artikel->id)) {
            Cache::forget("artikel_berita_lainnya_{$artikel->id}");
        }

        if ($artikel->wasChanged('slug')) {
            $oldSlug = $artikel->getOriginal('slug');
            if (!empty($oldSlug)) {
                Cache::forget("artikel_detail_{$oldSlug}");
            }
        }
    }
}
