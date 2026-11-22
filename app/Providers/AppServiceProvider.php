<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        RateLimiter::for('login', function (Request $request) {
            $identity = Str::lower(trim((string) $request->input('email')));
            $ip = $request->ip() ?? 'unknown';

            return [
                Limit::perMinute(5)->by('login:identity:'.hash('sha256', $identity.'|'.$ip)),
                Limit::perMinute(120)->by('login:ip:'.hash('sha256', $ip)),
            ];
        });
    }
}
