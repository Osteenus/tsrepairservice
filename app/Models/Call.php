<?php

namespace App\Models;

use App\Crm\LocalTime;
use App\Support\Normalize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/** One call on the business line, incoming or outgoing (CRM → Phone → Calls). */
class Call extends Model
{
    public const INBOUND = 'inbound';

    public const OUTBOUND = 'outbound';

    protected $fillable = [
        'call_sid', 'direction', 'from_number', 'to_number', 'status', 'duration_seconds', 'started_at', 'ended_at', 'customer_id',
        'is_internal', 'recording_sid', 'recording_duration_seconds', 'transcript_sid', 'transcript_status', 'transcript',
    ];

    protected $attributes = ['is_internal' => false];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'is_internal' => 'boolean',
        ];
    }

    /** The call log is a record of what happened: rows are never deleted. */
    public function delete(): never
    {
        throw new LogicException('Calls are never deleted.');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    /** The other party: the caller of an incoming call, the person called on an outgoing one. */
    public function otherNumber(): ?string
    {
        return $this->direction === self::INBOUND ? $this->from_number : $this->to_number;
    }

    /** Twilio statuses after which nothing more happens on the call. */
    public const FINAL = ['completed', 'busy', 'no-answer', 'failed', 'canceled'];

    public static function logIncoming(string $callSid, ?string $from, ?string $to): self
    {
        $from = Normalize::e164($from) ?? $from;
        $to = Normalize::e164($to) ?? $to;
        // A call placed from the business line is ours, not a caller's.
        $outbound = $from !== null && $from === Normalize::e164(config('services.twilio.number'));

        return static::firstOrCreate(['call_sid' => $callSid], [
            'direction' => $outbound ? self::OUTBOUND : self::INBOUND,
            'from_number' => $from,
            'to_number' => $to,
            'status' => 'ringing',
            'started_at' => now(),
            'customer_id' => static::customerIdFor($outbound ? $to : $from),
            'is_internal' => static::isTestNumber($from) || static::isTestNumber($to),
        ]);
    }

    public static function logOutgoing(string $callSid, string $to, ?int $customerId): self
    {
        return static::create([
            'call_sid' => $callSid,
            'direction' => self::OUTBOUND,
            'from_number' => config('services.twilio.number'),
            'to_number' => $to,
            'status' => 'initiated',
            'started_at' => now(),
            'customer_id' => $customerId ?? static::customerIdFor($to),
            'is_internal' => static::isTestNumber($to),
        ]);
    }

    /**
     * Twilio status callback. An outgoing CRM call is the call to the owner's phone; once it
     * reached the customer, the Dial result (logDialResult) already describes the call.
     */
    public static function logStatus(array $fields): void
    {
        $status = (string) ($fields['CallStatus'] ?? '');
        $call = static::firstWhere('call_sid', (string) ($fields['CallSid'] ?? ''));
        if (! $call) {
            // Incoming calls that did not pass through the incoming webhook (Twilio used the fallback URL).
            if (($fields['Direction'] ?? '') !== self::INBOUND || BlockedNumber::isBlocked($fields['From'] ?? null)) {
                return;
            }
            $call = static::logIncoming((string) $fields['CallSid'], $fields['From'] ?? null, $fields['To'] ?? null);
        }
        if ($call->ended_at) {
            return;
        }

        $final = in_array($status, self::FINAL, true);
        if ($call->direction === self::OUTBOUND) {
            // Owner did not answer or did not press 1: the customer was never dialed.
            $call->fill($final ? ['status' => 'not-connected', 'ended_at' => now()] : ['status' => $status ?: $call->status]);
        } else {
            $call->fill([
                'status' => $status ?: $call->status,
                'duration_seconds' => isset($fields['CallDuration']) ? (int) $fields['CallDuration'] : $call->duration_seconds,
                'ended_at' => $final ? now() : null,
            ]);
        }
        $call->save();
    }

    /** Result of dialing the customer on an outgoing CRM call: completed (talked), busy, no-answer, ... */
    public static function logDialResult(string $callSid, ?string $status, ?string $duration): void
    {
        static::where('call_sid', $callSid)->first()?->fill([
            'status' => $status ?: 'completed',
            'duration_seconds' => $duration !== null && $duration !== '' ? (int) $duration : null,
            'ended_at' => now(),
        ])->save();
    }

    /** "(805) 555-0123" for US numbers, other numbers as Twilio sent them. */
    public static function displayNumber(?string $phone): ?string
    {
        return Normalize::phone(preg_replace('/^\+1(?=\d{10}$)/', '', (string) $phone));
    }

    /** Fields for the CRM call lists. */
    public function toListItem(array $blocked = []): array
    {
        $number = $this->otherNumber();

        return [
            'id' => $this->id,
            'time' => LocalTime::display($this->started_at, 'D, M j, g:i A'),
            'direction' => $this->direction,
            'number' => $number,
            'display_number' => static::displayNumber($number) ?? 'Unknown',
            'customer' => $this->customer ? ['id' => $this->customer->id, 'name' => $this->customer->name] : null,
            'duration_seconds' => $this->duration_seconds,
            'status' => $this->status,
            'internal' => $this->is_internal,
            'blocked' => in_array($number, $blocked, true),
            'blockable' => ! $this->is_internal && $number !== null && ! BlockedNumber::isProtected($number),
        ];
    }

    /** One of the owner's phones used for test calls (services.twilio.test_numbers). */
    public static function isTestNumber(?string $phone): bool
    {
        $phone = Normalize::e164($phone);

        return $phone !== null && in_array($phone, array_map(fn ($number) => Normalize::e164($number), config('services.twilio.test_numbers') ?? []), true);
    }

    /** Customer whose phone matches the number (E.164 or as typed), compared by digits. */
    public static function customerIdFor(?string $phone): ?int
    {
        $digits = Customer::digits(Normalize::e164($phone) ?? $phone);

        return $digits ? Customer::where('phone_digits', $digits)->orderBy('id')->value('id') : null;
    }
}
