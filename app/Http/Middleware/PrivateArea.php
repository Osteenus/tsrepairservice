<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrivateArea
{
    /**
     * Keep CRM and account pages out of search engines, shared caches and the SSR server.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Private page props must not travel through the Node SSR process.
        config(['inertia.ssr.enabled' => false]);

        $response = $next($request);
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        $response->headers->set('Cache-Control', 'no-store, private');

        return $response;
    }
}
