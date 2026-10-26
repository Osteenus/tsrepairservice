<?php

namespace App\Console\Commands;

use App\Mail\VoiceLeadMail;
use App\Models\VoiceLead;
use App\Voice\BusinessHours;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Runs every minute. A lead still waiting for a call-back when the promised time passes gets one
 * follow-up call from the voice agent; when it is still waiting overdue_minutes later, it is marked
 * overdue and the owner is emailed.
 */
class VoiceFollowUp extends Command
{
    protected $signature = 'voice:follow-up';

    protected $description = 'Follow up voice leads nobody called back and flag overdue ones';

    public function handle(): int
    {
        $this->followUp();
        $this->markOverdue();

        return self::SUCCESS;
    }

    private function followUp(): void
    {
        // The agent only calls during technician hours; consent_to_callback is implied by callback_due.
        if (! BusinessHours::isOpen()) {
            return;
        }

        VoiceLead::where('status', 'callback_due')->where('followup_count', 0)
            ->where('callback_due_at', '<=', now())->where('consent_to_callback', true)
            ->each(function (VoiceLead $lead) {
                // Counted before the request: at most one follow-up, even if the request fails halfway.
                $claimed = VoiceLead::whereKey($lead->id)->where('followup_count', 0)
                    ->update(['followup_count' => 1, 'followed_up_at' => now()]);
                if (! $claimed) {
                    return;
                }

                try {
                    Http::withToken(config('voice.api_key'))->timeout(10)
                        ->post(config('voice.service_url').'/followup/'.$lead->id, [
                            'phone' => $lead->caller_phone,
                            'name' => $lead->caller_name,
                            'appliance_type' => $lead->appliance_type,
                        ])->throw();
                    $this->info("Follow-up call started for lead {$lead->id}.");
                } catch (Throwable $exception) {
                    report($exception);
                    $this->warn("Follow-up call failed for lead {$lead->id}.");
                }
            });
    }

    private function markOverdue(): void
    {
        VoiceLead::where('status', 'callback_due')
            ->where('callback_due_at', '<=', now()->subMinutes(config('voice.overdue_minutes')))
            ->each(function (VoiceLead $lead) {
                $updated = VoiceLead::whereKey($lead->id)->where('status', 'callback_due')->update(['status' => 'overdue']);
                if ($updated) {
                    Mail::to(config('repair.request_recipient'))->queue(new VoiceLeadMail($lead->fresh(), 'overdue'));
                }
            });
    }
}
