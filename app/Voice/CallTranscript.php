<?php

namespace App\Voice;

use App\Jobs\FetchCallTranscript;
use App\Models\Call;

/** On-demand transcripts of call recordings (Twilio Voice Intelligence). Never started automatically: each one costs money. */
class CallTranscript
{
    public const PENDING = 'pending';

    public const COMPLETED = 'completed';

    public const FAILED = 'failed';

    /** Recording channels: 1 is the caller, 2 is our side (the AI assistant or the technician after a transfer). */
    private const SPEAKERS = [1 => 'Caller', 2 => 'TS Repair'];

    public function __construct(private TwilioApi $twilio) {}

    public static function configured(): bool
    {
        return TwilioApi::configured() && (bool) config('services.twilio.intelligence_service_sid');
    }

    /** Asks Twilio to transcribe the call's recording. The text arrives through the transcription-status webhook. */
    public function start(Call $call): void
    {
        $transcript = $this->twilio->createTranscript($call->recording_sid, (string) $call->id);
        $call->fill(['transcript_sid' => $transcript['sid'], 'transcript_status' => self::PENDING, 'transcript' => null])->save();

        // In case the webhook never arrives (misconfigured URL), look the transcript up later.
        FetchCallTranscript::dispatch($call->id)->delay(now()->addMinutes(3));
    }

    /** Copies a finished transcript into the call. False while Twilio is still working on it. */
    public function sync(Call $call): bool
    {
        if ($call->transcript_status !== self::PENDING || ! $call->transcript_sid) {
            return true;
        }

        $status = $this->twilio->transcriptStatus($call->transcript_sid);
        if (in_array($status, ['failed', 'canceled', 'error'], true)) {
            $call->update(['transcript_status' => self::FAILED]);

            return true;
        }
        if ($status !== 'completed') {
            return false;
        }

        $call->update(['transcript' => $this->text($this->twilio->sentences($call->transcript_sid)), 'transcript_status' => self::COMPLETED]);

        return true;
    }

    /** "Caller: …" / "TS Repair: …" paragraphs, one per change of speaker (recordings are dual-channel). */
    private function text(array $sentences): string
    {
        $paragraphs = [];
        $channel = null;
        foreach (collect($sentences)->sortBy('sentence_index') as $sentence) {
            $words = trim((string) ($sentence['transcript'] ?? ''));
            if ($words === '') {
                continue;
            }
            if ($sentence['media_channel'] !== $channel || ! $paragraphs) {
                $channel = $sentence['media_channel'];
                $paragraphs[] = (self::SPEAKERS[$channel] ?? 'Speaker '.$channel).': '.$words;
            } else {
                $paragraphs[array_key_last($paragraphs)] .= ' '.$words;
            }
        }

        return $paragraphs ? implode("\n\n", $paragraphs) : '(No speech was recognized.)';
    }
}
