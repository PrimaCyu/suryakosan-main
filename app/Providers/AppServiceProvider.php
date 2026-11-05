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
        // Register Model Observers for Automated Real-Time Cache Invalidation
        \App\Models\ProductKosan::observe(\App\Observers\KosanObserver::class);
        \App\Models\ProductKamarKosan::observe(\App\Observers\KamarObserver::class);
        \App\Models\PriceKamar::observe(\App\Observers\KamarSubItemObserver::class);
        \App\Models\ProductKamarImageKosan::observe(\App\Observers\KamarSubItemObserver::class);
        \App\Models\FasilitasKamar::observe(\App\Observers\KamarSubItemObserver::class);
        \App\Models\ProductImageKosan::observe(\App\Observers\KamarSubItemObserver::class);
        \App\Models\Tamu::observe(\App\Observers\KamarSubItemObserver::class);
        \App\Models\Artikel::observe(\App\Observers\ArtikelObserver::class);
        \App\Models\Testimoni::observe(\App\Observers\TestimoniObserver::class);

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('sosial_media')) {
                    $view->with('globalSosmed', \App\Models\SosialMedia::all());
                }
            } catch (\Throwable $e) {
                // Ignore if DB not ready
            }
        });

        \Illuminate\Support\Facades\View::composer('backend.*', function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('tamus')) {
                    $user = auth()->user();
                    if ($user) {
                        $isSuper = $user->isSuperAdmin();
                        $assignedIds = $isSuper ? collect() : $user->kosans()->pluck('product_kosans.id');
                        
                        $pendingBookingCount = \App\Models\Tamu::where('status', 'pending')
                            ->when(!$isSuper, function ($q) use ($assignedIds) {
                                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
                            })->count();

                        $navbarPendingBookings = \App\Models\Tamu::with(['productKamarKosan.productKosan'])
                            ->where('status', 'pending')
                            ->when(!$isSuper, function ($q) use ($assignedIds) {
                                $q->whereHas('productKamarKosan', fn($sub) => $sub->whereIn('product_kosan_id', $assignedIds));
                            })
                            ->orderByDesc('created_at')
                            ->take(5)
                            ->get();
                    } else {
                        $pendingBookingCount = 0;
                        $navbarPendingBookings = collect();
                    }

                    $view->with('pendingBookingCount', $pendingBookingCount);
                    $view->with('navbarPendingBookings', $navbarPendingBookings);
                }
            } catch (\Throwable $e) {
                // Ignore during initial migrations
            }
        });
    }
}
