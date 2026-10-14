<?php

namespace App\Models;

use App\Crm\DocumentTotals;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = ['draft' => 'Draft', 'issued' => 'Issued', 'approved' => 'Approved', 'void' => 'Void'];

    protected $fillable = [
        'type', 'status', 'revision_of_id', 'estimate_id', 'issued_on', 'valid_until', 'service_date',
        'discount_cents', 'charge_tax', 'tax_rate', 'notes', 'work_performed', 'completed_by', 'performed_by',
    ];

    protected $attributes = [
        'status' => 'draft',
        'discount_cents' => 0,
        'charge_tax' => false,
        'labor_cents' => 0,
        'parts_cents' => 0,
        'tax_cents' => 0,
        'total_cents' => 0,
    ];

    protected function casts(): array
    {
        return [
            'discount_cents' => 'integer',
            'labor_cents' => 'integer',
            'parts_cents' => 'integer',
            'tax_cents' => 'integer',
            'total_cents' => 'integer',
            'issued_on' => 'date',
            'valid_until' => 'date',
            'service_date' => 'date',
            'approved_at' => 'datetime',
            'emailed_at' => 'datetime',
            'charge_tax' => 'boolean',
            'tax_rate' => 'float',
            'snapshot' => 'array',
        ];
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class)->withTrashed();
    }

    public function items(): HasMany
    {
        return $this->hasMany(DocumentItem::class)->orderBy('position')->orderBy('id');
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(Signature::class);
    }

    public function consent(): HasOne
    {
        return $this->hasOne(Consent::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paidCents(): int
    {
        return (int) $this->payments()->sum('amount_cents');
    }

    public function revisionOf(): BelongsTo
    {
        return $this->belongsTo(self::class, 'revision_of_id');
    }

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(self::class, 'estimate_id');
    }

    public function scopeEstimates(Builder $query): Builder
    {
        return $query->where('type', 'estimate');
    }

    public function scopeInvoices(Builder $query): Builder
    {
        return $query->where('type', 'invoice');
    }

    public function isEstimate(): bool
    {
        return $this->type === 'estimate';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function calculateTotals(): DocumentTotals
    {
        return DocumentTotals::calculate(
            $this->items->map(fn (DocumentItem $item) => $item->only('kind', 'qty', 'unit_cents', 'is_warranty')),
            $this->discount_cents,
            $this->charge_tax ? $this->tax_rate : null,
        );
    }

    /** Store the computed totals on the row (they are what the PDF and the estimate limit use). */
    public function refreshTotals(): void
    {
        $totals = $this->load('items')->calculateTotals();

        $this->forceFill([
            'labor_cents' => $totals->labor,
            'parts_cents' => $totals->parts,
            'tax_cents' => $totals->tax,
            'discount_cents' => $totals->discount,
            'total_cents' => $totals->total,
        ])->save();
    }

    public function title(): string
    {
        $label = $this->isEstimate() ? ($this->revision_of_id ? 'Revised estimate' : 'Estimate') : 'Invoice';

        return $this->number ? "{$label} {$this->number}" : "{$label} (draft)";
    }
}
