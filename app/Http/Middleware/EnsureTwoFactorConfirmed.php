<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorConfirmed
{
    /**
     * Send users without a confirmed authenticator app to the 2FA setup page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->two_factor_confirmed_at) {
            return redirect()->route('profile.show')
                ->with('flash.banner', 'Set up two-factor authentication to use the CRM.')
                ->with('flash.bannerStyle', 'danger');
        }

        return $next($request);
    }
}
