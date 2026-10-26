<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** voice-service calls the /api/voice endpoints with "Authorization: Bearer VOICE_API_KEY". */
class VerifyVoiceApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = (string) config('voice.api_key');
        abort_if($key === '' || ! hash_equals($key, (string) $request->bearerToken()), 401);

        return $next($request);
    }
}
