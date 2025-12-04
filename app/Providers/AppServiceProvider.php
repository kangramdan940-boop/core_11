<?php

namespace App\Providers;

use App\Models\TransPo;
use App\Models\TransReady;
use App\Observers\TransPoObserver;
use App\Observers\TransReadyObserver;
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
        TransPo::observe(TransPoObserver::class);
        TransReady::observe(TransReadyObserver::class);
    }
}
