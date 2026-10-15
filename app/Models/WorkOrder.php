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

    /**
     * Status changes the owner makes by hand, with button labels. The others follow documents:
     * issuing an estimate → estimate_sent, signing it → approved, issuing an invoice → completed,
     * full payment → paid. A paid job is closed; removing a payment reopens it automatically.
     */
    public const MANUAL_TRANSITIONS = [
        'new' => ['cancelled' => 'Cancel job'],
        'estimate_sent' => ['cancelled' => 'Cancel job'],
        'approved' => ['in_progress' => 'Start repair', 'completed' => 'Mark completed', 'cancelled' => 'Cancel job'],
        'in_progress' => ['completed' => 'Mark completed', 'cancelled' => 'Cancel job'],
        'completed' => ['in_progress' => 'Back to in progress', 'cancelled' => 'Cancel job'],
        'paid' => [],
        'cancelled' => ['new' => 'Reopen job'],
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
