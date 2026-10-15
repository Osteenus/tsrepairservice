<?php

namespace App\Models;

use App\Crm\DocumentTotals;
use App\Support\Normalize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentItem extends Model
{
    public const KINDS = ['service_call' => 'Service call', 'labor' => 'Labor', 'part' => 'Part'];

    protected $fillable = [
        'position', 'kind', 'description', 'part_number', 'qty', 'unit_cents', 'is_warranty', 'is_used_or_exchanged',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'float',
            'unit_cents' => 'integer',
            'is_warranty' => 'boolean',
            'is_used_or_exchanged' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (DocumentItem $item) {
            $item->description = Normalize::text($item->description) ?? '';
            $item->part_number = Normalize::upper($item->part_number);
        });
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function amountCents(): int
    {
        return DocumentTotals::lineAmount($this->qty, $this->unit_cents, $this->is_warranty);
    }
}
