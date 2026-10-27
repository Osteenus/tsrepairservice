<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One incoming call on the business line (A/B statistics). Filled in pieces by voice-service and Twilio. */
class VoiceCall extends Model
{
    protected $fillable = [
        'call_sid', 'caller_phone', 'variant', 'menu_choice', 'rate_limited', 'reached_ai', 'transfer_status', 'lead_created',
        'timed_out', 'ai_turns', 'input_tokens', 'output_tokens', 'call_status', 'duration_seconds',
    ];

    protected function casts(): array
    {
        return [
            'rate_limited' => 'boolean',
            'reached_ai' => 'boolean',
            'lead_created' => 'boolean',
            'timed_out' => 'boolean',
        ];
    }

    /** Creates or updates the call with only the fields given (the pieces arrive in any order). */
    public static function record(string $callSid, array $fields): void
    {
        $call = static::firstOrCreate(['call_sid' => $callSid]);
        $call->fill(array_filter($fields, fn ($value) => $value !== null))->save();
    }
}
