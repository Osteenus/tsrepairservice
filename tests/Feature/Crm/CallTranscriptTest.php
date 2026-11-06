<?php

namespace Tests\Feature\Crm;

use App\Http\Middleware\VerifyTwilioSignature;
use App\Models\Call;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;

class CallTranscriptTest extends CrmTestCase
{
    private const INTELLIGENCE = 'intelligence.twilio.com/v2/Transcripts';

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.twilio.account_sid' => 'AC1',
            'services.twilio.auth_token' => 'token',
            'services.twilio.intelligence_service_sid' => 'GA1',
        ]);
    }

    private function logCall(array $fields = []): Call
    {
        return Call::create([
            'call_sid' => 'CA1', 'direction' => 'inbound', 'from_number' => '+18055550123', 'to_number' => '+18053348375',
            'status' => 'completed', 'duration_seconds' => 80, 'started_at' => now(), ...$fields,
        ]);
    }

    private function fakeFinishedTranscript(string $status = 'completed'): void
    {
        Http::fake([
            self::INTELLIGENCE.'/GT1/Sentences*' => Http::response(['sentences' => [
                ['sentence_index' => 1, 'media_channel' => 2, 'transcript' => 'My fridge is warm.'],
                ['sentence_index' => 0, 'media_channel' => 1, 'transcript' => 'Tech Solutions, how can I help?'],
                ['sentence_index' => 2, 'media_channel' => 2, 'transcript' => 'Since yesterday.'],
            ], 'meta' => ['next_page_url' => null]]),
            self::INTELLIGENCE.'/GT1' => Http::response(['sid' => 'GT1', 'status' => $status]),
        ]);
    }

    private function webhook(array $fields): TestResponse
    {
        $path = '/api/twilio/voice/transcription-status';

        return $this->post($path, $fields, ['X-Twilio-Signature' => VerifyTwilioSignature::sign('token', url($path), $fields)]);
    }

    public function test_transcribe_creates_a_transcript_only_for_recorded_calls(): void
    {
        Http::fake([
            self::INTELLIGENCE => Http::response(['sid' => 'GT1', 'status' => 'queued'], 201),
            self::INTELLIGENCE.'/GT1' => Http::response(['sid' => 'GT1', 'status' => 'queued']),
        ]);
        $recorded = $this->logCall(['recording_sid' => 'RE1', 'recording_duration_seconds' => 75]);
        $notRecorded = $this->logCall(['call_sid' => 'CA2']);
        $this->signIn();

        $this->post("/crm/calls/{$notRecorded->id}/transcribe")->assertSessionHas('flash.bannerStyle', 'danger');
        Http::assertNothingSent();

        $this->post("/crm/calls/{$recorded->id}/transcribe")
            ->assertSessionHas('flash.banner', 'Transcribing. The text usually appears within a few minutes.');

        Http::assertSent(fn (Request $request) => $request->url() === 'https://'.self::INTELLIGENCE
            && $request['ServiceSid'] === 'GA1'
            && json_decode($request['Channel'], true) === ['media_properties' => ['source_sid' => 'RE1']]
            && $request['CustomerKey'] === (string) $recorded->id);
        $this->assertSame(['GT1', 'pending'], [$recorded->fresh()->transcript_sid, $recorded->fresh()->transcript_status]);
        $this->assertNull($notRecorded->fresh()->transcript_status);

        // Pressing again while it is pending does not pay for a second transcript.
        $this->post("/crm/calls/{$recorded->id}/transcribe");
        Http::assertSentCount(2); // the create + the backup status lookup
    }

    public function test_transcription_webhook_saves_the_transcript(): void
    {
        $this->fakeFinishedTranscript();
        $call = $this->logCall(['recording_sid' => 'RE1', 'transcript_sid' => 'GT1', 'transcript_status' => 'pending']);

        $this->webhook(['transcript_sid' => 'GT1', 'event_type' => 'voice_intelligence_transcript_available'])->assertNoContent();

        $call->refresh();
        $this->assertSame('completed', $call->transcript_status);
        $this->assertSame("Speaker 1: Tech Solutions, how can I help?\n\nSpeaker 2: My fridge is warm. Since yesterday.", $call->transcript);
    }

    public function test_failed_transcript_is_marked_failed(): void
    {
        $this->fakeFinishedTranscript('failed');
        $call = $this->logCall(['recording_sid' => 'RE1', 'transcript_sid' => 'GT1', 'transcript_status' => 'pending']);

        $this->webhook(['transcript_sid' => 'GT1']);

        $this->assertSame(['failed', null], [$call->fresh()->transcript_status, $call->fresh()->transcript]);
    }

    public function test_json_webhook_is_checked_with_the_body_hash(): void
    {
        $this->fakeFinishedTranscript();
        $call = $this->logCall(['recording_sid' => 'RE1', 'transcript_sid' => 'GT1', 'transcript_status' => 'pending']);
        $data = ['transcript_sid' => 'GT1', 'event_type' => 'voice_intelligence_transcript_available'];
        $url = url('/api/twilio/voice/transcription-status').'?bodySHA256='.hash('sha256', json_encode($data));

        $this->postJson($url, ['transcript_sid' => 'GT1', 'event_type' => 'forged'], ['X-Twilio-Signature' => VerifyTwilioSignature::sign('token', $url, [])])
            ->assertForbidden();
        $this->postJson('/api/twilio/voice/transcription-status', $data, ['X-Twilio-Signature' => 'forged'])->assertForbidden();
        $this->assertSame('pending', $call->fresh()->transcript_status);

        $this->postJson($url, $data, ['X-Twilio-Signature' => VerifyTwilioSignature::sign('token', $url, [])])->assertNoContent();
        $this->assertSame('completed', $call->fresh()->transcript_status);
    }

    public function test_transcription_webhook_checks_the_signature(): void
    {
        Http::fake();
        $call = $this->logCall(['recording_sid' => 'RE1', 'transcript_sid' => 'GT1', 'transcript_status' => 'pending']);

        $this->post('/api/twilio/voice/transcription-status', ['transcript_sid' => 'GT1'], ['X-Twilio-Signature' => 'forged'])->assertForbidden();

        $this->assertSame('pending', $call->fresh()->transcript_status);
        Http::assertNothingSent();
    }

    public function test_transcribe_needs_a_signed_in_user(): void
    {
        Http::fake();
        $call = $this->logCall(['recording_sid' => 'RE1']);

        $this->post("/crm/calls/{$call->id}/transcribe")->assertRedirect('/login');

        $this->assertNull($call->fresh()->transcript_status);
        Http::assertNothingSent();
    }
}
