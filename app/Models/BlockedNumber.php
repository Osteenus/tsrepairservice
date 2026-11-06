<?php

namespace App\Models;

use App\Support\Normalize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/** A number whose calls the business line rejects. Unblocking soft-deletes it. */
class BlockedNumber extends Model
{
    use SoftDeletes;

    public const UPDATED_AT = null;

    protected $fillable = ['phone_e164', 'reason'];

    public static function isBlocked(?string $phone): bool
    {
        $phone = Normalize::e164($phone);

        return $phone !== null && ! static::isProtected($phone) && static::where('phone_e164', $phone)->exists();
    }

    /** The business line, the owner's mobile and the test phones are never blocked. */
    public static function isProtected(?string $phone): bool
    {
        $phone = Normalize::e164($phone);
        $own = [config('services.twilio.number'), config('services.twilio.owner_phone'), ...(config('services.twilio.test_numbers') ?? [])];

        return $phone !== null && in_array($phone, array_map(fn ($number) => Normalize::e164($number), $own), true);
    }

    /**
     * Blocks the number, or blocks it again after an unblock. Null when it cannot be read as a phone
     * number or is one of our own (isProtected).
     */
    public static function block(?string $phone, ?string $reason = null): ?self
    {
        $phone = Normalize::e164($phone);
        if ($phone === null || static::isProtected($phone)) {
            return null;
        }

        $blocked = static::withTrashed()->firstOrNew(['phone_e164' => $phone]);
        $blocked->reason = $reason ?? $blocked->reason;
        $blocked->deleted_at = null;
        $blocked->created_at = now();
        $blocked->save();

        return $blocked;
    }
}
