<?php

namespace App\Crm;

use Carbon\CarbonImmutable;
use DateTimeInterface;

/**
 * Timestamps are stored in UTC; the owner works in Pacific time.
 */
class LocalTime
{
    public static function zone(): string
    {
        return config('crm.timezone');
    }

    public static function now(): CarbonImmutable
    {
        return CarbonImmutable::now(self::zone());
    }

    public static function today(): CarbonImmutable
    {
        return self::now()->startOfDay();
    }

    /** Value for an <input type="datetime-local">. */
    public static function toInput(?DateTimeInterface $value): ?string
    {
        return $value ? CarbonImmutable::instance($value)->setTimezone(self::zone())->format('Y-m-d\TH:i') : null;
    }

    /** Parse a datetime-local value entered in Pacific time into UTC. */
    public static function fromInput(?string $value): ?CarbonImmutable
    {
        return $value ? CarbonImmutable::parse($value, self::zone())->utc() : null;
    }

    public static function display(?DateTimeInterface $value, string $format = 'D, M j, g:i A'): ?string
    {
        return $value ? CarbonImmutable::instance($value)->setTimezone(self::zone())->format($format) : null;
    }
}
