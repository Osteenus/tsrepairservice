<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectWwwToApex
{
    /**
     * Permanently redirect www page requests to the canonical host.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if (str_starts_with($host, 'www.') && $request->isMethodSafe()) {
            return redirect()->to('https://'.substr($host, 4).$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
