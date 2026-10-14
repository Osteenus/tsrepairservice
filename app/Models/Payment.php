<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    public const METHODS = ['cash' => 'Cash', 'check' => 'Check', 'zelle' => 'Zelle', 'card' => 'Card', 'other' => 'Other'];

    protected $fillable = ['work_order_id', 'document_id', 'amount_cents', 'method', 'paid_on', 'note', 'recorded_by'];

    protected function casts(): array
    {
        return ['paid_on' => 'date', 'amount_cents' => 'integer'];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
