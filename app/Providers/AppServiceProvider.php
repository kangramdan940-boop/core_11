<?php

namespace App\Providers;

use App\Models\TransPo;
use App\Models\TransReady;
use App\Models\TransKeranjang;
use App\Observers\TransPoObserver;
use App\Observers\TransReadyObserver;
use App\Observers\TransKeranjangObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

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
        TransKeranjang::observe(TransKeranjangObserver::class);

        RateLimiter::for('forgot-password', function (Request $request) {
            $email = strtolower((string) $request->input('email'));
            $key = $email !== '' ? 'forgot:'.$email : 'forgot:'.$request->ip();
            return Limit::perMinutes(10, 3)->by($key);
        });
    }
}
