<?php

namespace App\Mail;

use App\Crm\LocalTime;
use App\Models\VoiceLead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Owner notification for a voice-agent lead: "new" when the call ends, "overdue" when nobody called back. */
class VoiceLeadMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public function __construct(public VoiceLead $lead, public string $kind) {}

    public function envelope(): Envelope
    {
        $who = $this->lead->caller_name ?: $this->lead->caller_phone;

        return new Envelope(subject: $this->kind === 'overdue'
            ? "OVERDUE call-back: {$who}"
            : "Phone lead: {$who}".($this->lead->appliance_type ? " ({$this->lead->appliance_type})" : ''));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.voice-lead', with: [
            'lead' => $this->lead,
            'kind' => $this->kind,
            'due' => $this->lead->callback_due_at ? LocalTime::display($this->lead->callback_due_at) : null,
            'url' => $this->lead->repair_request_id ? route('crm.requests.show', $this->lead->repair_request_id) : null,
        ]);
    }
}
