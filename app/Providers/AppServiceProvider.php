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
        \App\Models\Auth::observe(\App\Observers\ActivityObserver::class);
        \App\Models\Barang::observe(\App\Observers\ActivityObserver::class);
        \App\Models\Kategori::observe(\App\Observers\ActivityObserver::class);
        \App\Models\Ruangan::observe(\App\Observers\ActivityObserver::class);
        \App\Models\Kelas::observe(\App\Observers\ActivityObserver::class);
    }
}
