<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Voice\CallTranscript;
use Illuminate\Http\RedirectResponse;
use Throwable;

/** "Transcribe" on a recorded call. Manual only, to keep the cost under control. */
class CallTranscriptController extends Controller
{
    public function store(Call $call, CallTranscript $transcripts): RedirectResponse
    {
        if (! $call->recording_sid) {
            return $this->failed('This call has no recording.');
        }
        if (in_array($call->transcript_status, [CallTranscript::PENDING, CallTranscript::COMPLETED], true)) {
            return back();
        }
        if (! CallTranscript::configured()) {
            return $this->failed('Transcription is not set up (TWILIO_INTELLIGENCE_SERVICE_SID).');
        }

        try {
            $transcripts->start($call);
        } catch (Throwable $exception) {
            report($exception);

            return $this->failed('Twilio could not start the transcript. Try again later.');
        }

        return back()->with('flash.banner', 'Transcribing. The text usually appears within a few minutes.');
    }

    private function failed(string $message): RedirectResponse
    {
        return back()->with('flash.banner', $message)->with('flash.bannerStyle', 'danger');
    }
}
