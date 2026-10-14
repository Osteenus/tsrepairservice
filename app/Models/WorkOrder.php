<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = [
        'new' => 'New',
        'estimate_sent' => 'Estimate sent',
        'approved' => 'Approved',
        'in_progress' => 'In progress',
        'completed' => 'Completed',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
    ];

    /** Statuses still needing work or money; everything else is closed. */
    public const OPEN_STATUSES = ['new', 'estimate_sent', 'approved', 'in_progress', 'completed'];

    protected $fillable = [
        'customer_id', 'appliance_id', 'repair_request_id', 'reported_problem', 'findings',
        'status', 'scheduled_at', 'service_address', 'notes',
    ];

    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime'];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function appliance(): BelongsTo
    {
        return $this->belongsTo(Appliance::class)->withTrashed();
    }

    public function repairRequest(): BelongsTo
    {
        return $this->belongsTo(RepairRequest::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /** The estimate the customer authorized most recently (a revision replaces the original). */
    public function approvedEstimate(): ?Document
    {
        return $this->documents()->estimates()->where('status', 'approved')->latest('approved_at')->latest('id')->first();
    }
}
