<?php

namespace App\Observers;

use App\Models\Testimoni;
use Illuminate\Support\Facades\Cache;

class TestimoniObserver
{
    /**
     * Handle the Testimoni "saved" event.
     */
    public function saved(Testimoni $testimoni): void
    {
        Cache::forget('home_testimonis');
    }

    /**
     * Handle the Testimoni "deleted" event.
     */
    public function deleted(Testimoni $testimoni): void
    {
        Cache::forget('home_testimonis');
    }
}
