<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        abort_unless($signature !== '' && hash_equals(self::sign($token, $request->url(), $request->post()), $signature), 403);

        return $next($request);
    }

    /** URL Twilio called (our routes take no query string) plus the POST fields sorted by name. */
    public static function sign(string $token, string $url, array $fields): string
    {
        ksort($fields, SORT_STRING);
        foreach ($fields as $name => $value) {
            $url .= $name.$value;
        }

        return base64_encode(hash_hmac('sha1', $url, $token, true));
    }
}
