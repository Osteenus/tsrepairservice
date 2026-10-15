<?php

namespace App\Support;

/**
 * Same rules as resources/js/forms/format.js, applied again on save.
 */
class Normalize
{
    /** US numbers become "(805) 555-0100"; international numbers and extensions stay as typed. */
    public static function phone(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        if (preg_match('/^\+(?!1\b)/', $value) || preg_match('/[a-z#]/i', $value)) {
            return $value;
        }

        $digits = preg_replace('/\D/', '', $value);
        if (strlen($digits) === 11 && str_starts_with($digits, '1')) {
            $digits = substr($digits, 1);
        }

        return strlen($digits) === 10
            ? sprintf('(%s) %s-%s', substr($digits, 0, 3), substr($digits, 3, 3), substr($digits, 6))
            : $value;
    }

    public static function email(?string $value): ?string
    {
        $value = mb_strtolower(trim((string) $value));

        return $value === '' ? null : $value;
    }

    public static function upper(?string $value): ?string
    {
        $value = mb_strtoupper(trim((string) $value));

        return $value === '' ? null : $value;
    }

    public static function text(?string $value): ?string
    {
        $value = trim(preg_replace('/\s+/', ' ', (string) $value));

        return $value === '' ? null : $value;
    }
}
