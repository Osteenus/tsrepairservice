<?php

namespace App\Voice;

use App\Models\Setting;

/** How the business line answers; changed on the CRM "Phone line" page. */
class PhoneLineSettings
{
    public const MODES = [
        'ai' => 'AI assistant',
        'transfer' => 'Simple transfer to me',
        'ab' => 'A/B test',
    ];

    public static function all(): array
    {
        return [
            'mode' => Setting::get('voice.mode', 'ai'),
            // A/B: share of calls answered by the AI assistant; the rest are transferred.
            'ai_percent' => (int) Setting::get('voice.ai_percent', 50),
            // Calls shorter than this count as "hung up right away" in the statistics.
            'short_call_seconds' => (int) Setting::get('voice.short_call_seconds', 10),
        ];
    }

    public static function save(array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::put("voice.{$key}", $value);
        }
    }
}
