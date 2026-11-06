<?php

namespace App\Voice;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * The few Twilio REST calls the call log needs: a call's recordings, the recording audio and
 * Voice Intelligence transcripts. Credentials stay on the server; nothing here reaches the browser.
 */
class TwilioApi
{
    public static function configured(): bool
    {
        return config('services.twilio.account_sid') && config('services.twilio.auth_token');
    }

    /** Recordings of one call: [['sid' => 'RE…', 'status' => 'completed', 'duration' => '42'], …] */
    public function recordings(string $callSid): array
    {
        return $this->api()->get($this->account("Calls/{$callSid}/Recordings.json"))->throw()->json('recordings') ?? [];
    }

    /** The recording as MP3, streamed; a Range header from the browser is passed on (Safari needs it to play). */
    public function recordingAudio(string $recordingSid, ?string $range = null): Response
    {
        return $this->api()->timeout(120)->withOptions(['stream' => true])
            ->withHeaders(array_filter(['Range' => $range]))
            ->get($this->account("Recordings/{$recordingSid}.mp3"));
    }

    /** Starts a transcript of the recording; Twilio calls our transcription-status webhook when it is ready. */
    public function createTranscript(string $recordingSid, string $customerKey): array
    {
        return $this->api()->asForm()->post('https://intelligence.twilio.com/v2/Transcripts', [
            'ServiceSid' => config('services.twilio.intelligence_service_sid'),
            'Channel' => json_encode(['media_properties' => ['source_sid' => $recordingSid]]),
            'CustomerKey' => $customerKey,
        ])->throw()->json();
    }

    /** Status of a transcript: queued, in-progress, completed, failed, canceled, error. */
    public function transcriptStatus(string $transcriptSid): ?string
    {
        return $this->api()->get("https://intelligence.twilio.com/v2/Transcripts/{$transcriptSid}")->throw()->json('status');
    }

    /** All sentences of a transcript in order. */
    public function sentences(string $transcriptSid): array
    {
        $sentences = [];
        $url = "https://intelligence.twilio.com/v2/Transcripts/{$transcriptSid}/Sentences?PageSize=1000";
        while ($url) {
            $page = $this->api()->get($url)->throw();
            $sentences = [...$sentences, ...($page->json('sentences') ?? [])];
            $url = $page->json('meta.next_page_url');
        }

        return $sentences;
    }

    private function api(): PendingRequest
    {
        return Http::withBasicAuth((string) config('services.twilio.account_sid'), (string) config('services.twilio.auth_token'))
            ->connectTimeout(10)->timeout(30);
    }

    private function account(string $path): string
    {
        return 'https://api.twilio.com/2010-04-01/Accounts/'.config('services.twilio.account_sid').'/'.$path;
    }
}
