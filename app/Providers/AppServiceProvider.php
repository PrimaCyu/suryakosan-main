<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('sosial_media')) {
                $sosmedList = \App\Models\SosialMedia::all();
                \Illuminate\Support\Facades\View::share('globalSosmed', $sosmedList);
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('tamus')) {
                \Illuminate\Support\Facades\View::composer('backend.*', function ($view) {
                    $pendingBookingCount = \App\Models\Tamu::where('status', 'pending')->count();
                    $view->with('pendingBookingCount', $pendingBookingCount);
                });
            }
        } catch (\Exception $e) {
            // Ignore during initial migrations
        }
    }
}
