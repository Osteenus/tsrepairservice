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

        $signature = (string) $request->header('X-Twilio-Signature');
        $validator = new RequestValidator($token);
        // JSON webhooks are signed over the URL as called (query untouched), whose bodySHA256
        // parameter covers the body. Form webhooks: our routes take no query string, so the
        // signed URL is the URL Twilio called.
        $valid = $request->isJson()
            ? $request->query->has('bodySHA256')
                && $validator->validate($signature, $request->getSchemeAndHttpHost().$request->getRequestUri(), $request->getContent())
            : $validator->validate($signature, $request->url(), $request->post());
        abort_unless($signature !== '' && $valid, 403);

        return $next($request);
    }

    /** Signature Twilio would send for these fields (tests and local tools). */
    public static function sign(string $token, string $url, array $fields): string
    {
        return (new RequestValidator($token))->computeSignature($url, $fields);
    }
}
