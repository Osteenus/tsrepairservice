<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            : $validator->validate($signature, $request->url(), self::fields($request));
        if ($signature === '' || ! $valid) {
            // Field names only: values can hold callers' numbers.
            Log::warning('Twilio signature rejected', ['url' => $request->url(), 'fields' => array_keys(self::fields($request))]);
            abort(403);
        }

        return $next($request);
    }

    /**
     * Form fields exactly as Twilio sent them. $request->post() is already changed by TrimStrings
     * and ConvertEmptyStringsToNull, and a single trimmed value breaks the signature.
     */
    private static function fields(Request $request): array
    {
        $body = $request->getContent();
        if ($body === '') {
            return $request->post(); // requests built in tests carry no raw body
        }
        $fields = [];
        foreach (explode('&', $body) as $pair) {
            if ($pair === '') {
                continue;
            }
            [$name, $value] = array_pad(explode('=', $pair, 2), 2, '');
            $fields[urldecode($name)] = urldecode($value);
        }

        return $fields;
    }

    /** Signature Twilio would send for these fields (tests and local tools). */
    public static function sign(string $token, string $url, array $fields): string
    {
        return (new RequestValidator($token))->computeSignature($url, $fields);
    }
}
