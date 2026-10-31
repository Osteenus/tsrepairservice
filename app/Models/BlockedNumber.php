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

        return $phone !== null && static::where('phone_e164', $phone)->exists();
    }

    /** Blocks the number, or blocks it again after an unblock. Null when it cannot be read as a phone number. */
    public static function block(?string $phone, ?string $reason = null): ?self
    {
        $phone = Normalize::e164($phone);
        if ($phone === null) {
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
