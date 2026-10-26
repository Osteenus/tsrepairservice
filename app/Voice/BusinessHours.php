<?php

namespace App\Voice;

use Carbon\CarbonImmutable;
use DateTimeInterface;

/** Technician hours from config/voice.php. */
class BusinessHours
{
    public static function isOpen(?DateTimeInterface $at = null): bool
    {
        $local = CarbonImmutable::instance($at ?? now())->setTimezone(config('voice.timezone'));
        [$open, $close] = self::window($local) ?? [null, null];

        return $open && $local->gte($open) && $local->lt($close);
    }

    /** $at itself when open, otherwise the start of the next working window (UTC). */
    public static function nextOpening(?DateTimeInterface $at = null): CarbonImmutable
    {
        $at = CarbonImmutable::instance($at ?? now());
        if (self::isOpen($at)) {
            return $at;
        }
        $local = $at->setTimezone(config('voice.timezone'));
        for ($day = 0; $day <= 7; $day++) {
            $window = self::window($local->addDays($day));
            if ($window && $window[0]->gt($local)) {
                return $window[0]->utc();
            }
        }

        return $at; // No hours configured at all.
    }

    /** @return array{CarbonImmutable, CarbonImmutable}|null */
    private static function window(CarbonImmutable $localDay): ?array
    {
        $hours = config('voice.hours.'.strtolower($localDay->format('D')));
        if (! $hours) {
            return null;
        }

        return [$localDay->setTimeFromTimeString($hours[0]), $localDay->setTimeFromTimeString($hours[1])];
    }
}
