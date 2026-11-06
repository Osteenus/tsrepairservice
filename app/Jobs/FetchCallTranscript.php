<?php

namespace App\Jobs;

use App\Models\Call;
use App\Voice\CallTranscript;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/** Backup for the transcription-status webhook: picks up a finished transcript if the webhook did not. */
class FetchCallTranscript implements ShouldQueue
{
    use Queueable;

    public int $tries = 6;

    public function __construct(public int $callId) {}

    public function handle(CallTranscript $transcripts): void
    {
        $call = Call::find($this->callId);
        if ($call && ! $transcripts->sync($call)) {
            $this->release(300);
        }
    }
}
