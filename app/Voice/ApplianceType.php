<?php

namespace App\Voice;

/** Maps spoken appliance names to the website service ids (config repair.services). */
class ApplianceType
{
    /** Checked in this order ("dishwasher" before "washer", "range hood" before "range"). */
    public const WORDS = [
        5 => ['dishwasher', 'dish washer'],
        9 => ['compactor'],
        8 => ['hood'],
        7 => ['microwave'],
        1 => ['refrigerator', 'fridge', 'freezer', 'ice maker'],
        3 => ['dryer'],
        2 => ['washer', 'washing machine'],
        4 => ['oven', 'stove', 'range', 'cooktop', 'burner'],
        10 => ['electronic'],
    ];

    /** 0 ("Appliance") when no known word was heard. */
    public static function serviceId(string $speech): int
    {
        $speech = mb_strtolower($speech);
        foreach (self::WORDS as $id => $words) {
            foreach ($words as $word) {
                if (preg_match('/\b'.preg_quote($word, '/').'/u', $speech)) {
                    return $id;
                }
            }
        }

        return 0;
    }
}
