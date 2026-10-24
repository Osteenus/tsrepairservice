<?php

use App\Http\Middleware\EnsureTwoFactorConfirmed;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\PrivateArea;
use App\Http\Middleware\RedirectWwwToApex;
use App\Http\Middleware\VerifyTwilioSignature;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(prepend: [
            RedirectWwwToApex::class,
        ]);
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->alias([
            'private' => PrivateArea::class,
            'two-factor' => EnsureTwoFactorConfirmed::class,
            'twilio' => VerifyTwilioSignature::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
