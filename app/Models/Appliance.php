<?php

namespace App\Models;

use App\Support\Normalize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appliance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['type', 'brand', 'model', 'serial', 'notes'];

    protected static function booted(): void
    {
        static::saving(function (Appliance $appliance) {
            $appliance->type = Normalize::text($appliance->type) ?? '';
            $appliance->brand = Normalize::text($appliance->brand);
            $appliance->model = Normalize::upper($appliance->model);
            $appliance->serial = Normalize::upper($appliance->serial);
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function label(): string
    {
        return trim(implode(' ', array_filter([$this->brand, $this->type, $this->model])));
    }
}
