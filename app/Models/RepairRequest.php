<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RepairRequest extends Model
{
    protected $fillable = [
        'contact_id', 'service_id', 'location', 'brand', 'model', 'description', 'source', 'call_sid',
    ];

    protected function casts(): array
    {
        return ['archived_at' => 'datetime'];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function workOrder(): HasOne
    {
        return $this->hasOne(WorkOrder::class);
    }

    /** Appliance type from the public form's service list, e.g. "Refrigerator". */
    public function applianceType(): string
    {
        $service = collect(config('repair.services'))->firstWhere('id', $this->service_id);

        return $service ? trim(str_replace('Repair', '', $service['name'])) : 'Appliance';
    }
}
