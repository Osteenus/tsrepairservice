<?php

namespace App\Crm;

use Carbon\CarbonImmutable;

class BusinessDays
{
    /** Add Monday–Friday days (holidays are not excluded), like the reference generator. */
    public static function add(CarbonImmutable $start, int $days): CarbonImmutable
    {
        $date = $start;
        while ($days > 0) {
            $date = $date->addDay();
            if (! $date->isWeekend()) {
                $days--;
            }
        }

        return $date;
    }
}
