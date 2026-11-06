<?php

namespace App\Jobs;

use App\Models\Call;
use App\Voice\TwilioApi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * After an incoming call ends, asks Twilio for its recording. voice-service starts the recordings
 * (RECORD_CALLS=true) without telling Laravel, so the call log looks them up itself.
 */
class FetchCallRecording implements ShouldQueue
{
    use Queueable;

    /** Twilio needs a minute or two to finish a recording; each attempt waits for it. */
    public int $tries = 5;

    public function __construct(public int $callId) {}

    public function backoff(): array
    {
        return [60, 120, 300, 600];
    }

    public function handle(TwilioApi $twilio): void
    {
        $call = Call::find($this->callId);
        if (! $call || $call->recording_sid) {
            return;
        }

        $recordings = collect($twilio->recordings($call->call_sid));
        foreach ($recordings->where('status', 'completed') as $recording) {
            $call->attachRecording($recording['sid'], (int) $recording['duration']);
        }

        // Still being processed: look again later. No recordings at all: the call was not recorded.
        if (! $call->recording_sid && $recordings->whereIn('status', ['in-progress', 'processing'])->isNotEmpty()) {
            $this->release($this->backoff()[$this->attempts() - 1] ?? 600);
        }
    }
}
