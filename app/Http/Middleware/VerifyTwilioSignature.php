<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Security\RequestValidator;

/**
 * Accepts only webhooks signed by Twilio with our auth token
 * (https://www.twilio.com/docs/usage/webhooks/webhooks-security).
 */
class VerifyTwilioSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = (string) config('services.twilio.auth_token');
        abort_if($token === '', 403, 'Twilio auth token is not configured.');

        // Our routes take no query string: the signed URL is the URL Twilio called.
        $signature = (string) $request->header('X-Twilio-Signature');
        abort_unless($signature !== '' && (new RequestValidator($token))->validate($signature, $request->url(), $request->post()), 403);

        return $next($request);
    }

    /** Signature Twilio would send for these fields (tests and local tools). */
    public static function sign(string $token, string $url, array $fields): string
    {
        return (new RequestValidator($token))->computeSignature($url, $fields);
    }
}
