<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('service-requests', function (Request $request) {
            return Limit::perMinutes(10, 5)->by($request->ip())->response(function (Request $request, array $headers) {
                return back()->withErrors([
                    'submission' => 'Too many requests. Please wait a few minutes before trying again, or call us.',
                ])->withHeaders($headers);
            });
        });
    }
}
