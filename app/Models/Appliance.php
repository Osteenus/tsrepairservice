<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appliance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['type', 'brand', 'model', 'serial', 'notes'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function label(): string
    {
        return trim(implode(' ', array_filter([$this->brand, $this->type, $this->model])));
    }
}
