<?php

namespace App\Models;

use App\Support\Normalize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'phone', 'email', 'address_line1', 'city', 'state', 'zip', 'notes',
    ];

    protected static function booted(): void
    {
        static::saving(function (Customer $customer) {
            $customer->name = Normalize::text($customer->name) ?? '';
            $customer->phone = Normalize::phone($customer->phone);
            $customer->email = Normalize::email($customer->email);
            $customer->address_line1 = Normalize::text($customer->address_line1);
            $customer->city = Normalize::text($customer->city);
            $customer->state = Normalize::upper($customer->state) ?? 'CA';
            $customer->zip = Normalize::text($customer->zip);
            $customer->phone_digits = self::digits($customer->phone);
        });
    }

    /**
     * Digits of a US phone number without the leading country code.
     */
    public static function digits(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if (strlen($digits) === 11 && str_starts_with($digits, '1')) {
            $digits = substr($digits, 1);
        }

        return $digits === '' ? null : $digits;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term) {
            $query->whereRaw('lower(name) like ?', ['%'.mb_strtolower($term).'%']);

            $digits = preg_replace('/\D+/', '', $term);
            if (strlen($digits) >= 3) {
                $query->orWhere('phone_digits', 'like', '%'.$digits.'%');
            }
        });
    }

    public function appliances(): HasMany
    {
        return $this->hasMany(Appliance::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function fullAddress(): string
    {
        return implode(', ', array_filter([$this->address_line1, $this->cityStateZip()]));
    }

    public function cityStateZip(): string
    {
        return trim(implode(', ', array_filter([$this->city, trim($this->state.' '.$this->zip)])));
    }
}
