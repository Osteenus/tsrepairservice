<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use SoftDeletes;

    public const KINDS = ['serial_label' => 'Serial label', 'before' => 'Before', 'after' => 'After', 'other' => 'Other'];

    protected $fillable = ['path', 'mime', 'kind', 'caption'];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
