<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/** A phone call taken by the AI voice agent (voice-service); see docs in voice-service/README.md. */
class VoiceLead extends Model
{
    use SoftDeletes;

    public const STATUSES = ['new', 'callback_due', 'callback_done', 'overdue'];

    protected $fillable = [
        'call_sid', 'caller_phone', 'caller_name', 'service_address', 'appliance_type', 'appliance_brand',
        'reported_problem', 'agent_summary', 'consent_to_callback', 'fee_accepted', 'preferred_callback_time',
        'recording_notice_played', 'outcome', 'transferred', 'status', 'callback_due_at',
        'repair_request_id', 'customer_id', 'started_at', 'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'consent_to_callback' => 'boolean',
            'fee_accepted' => 'boolean',
            'recording_notice_played' => 'boolean',
            'transferred' => 'boolean',
            'callback_due_at' => 'datetime',
            'followed_up_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function repairRequest(): BelongsTo
    {
        return $this->belongsTo(RepairRequest::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
