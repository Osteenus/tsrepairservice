<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        Password::defaults(fn () => Password::min(12)->letters()->numbers());

        RateLimiter::for('service-requests', function (Request $request) {
            return Limit::perMinutes(10, 5)->by($request->ip())->response(function (Request $request, array $headers) {
                return back()->withErrors([
                    'submission' => 'Too many requests. Please wait a few minutes before trying again, or call us.',
                ])->withHeaders($headers);
            });
        });
    }
}
