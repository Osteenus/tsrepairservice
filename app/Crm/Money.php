<?php

namespace App\Crm;

final class Money
{
    public static function format(int $cents): string
    {
        $sign = $cents < 0 ? '-' : '';

        return $sign.'$'.number_format(abs($cents) / 100, 2);
    }

    /** Parse user input such as "95", "95.5" or "$1,234.50" into cents. */
    public static function parse(string|int|float|null $value): int
    {
        $clean = preg_replace('/[^\d.]/', '', (string) $value);

        return $clean === '' ? 0 : (int) round(((float) $clean) * 100);
    }
}
