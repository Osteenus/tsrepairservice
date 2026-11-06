<?php

namespace Tests\Feature\Crm;

use App\Http\Middleware\VerifyTwilioSignature;
use App\Jobs\FetchCallRecording;
use App\Models\Call;
use App\Models\User;
use App\Voice\TwilioApi;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;

class CallRecordingTest extends CrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.twilio.account_sid' => 'AC1',
            'services.twilio.auth_token' => 'token',
            'services.twilio.number' => '+18053348375',
            'voice.public_url' => 'https://voice.test',
        ]);
    }

    private function twilio(string $path, array $fields): TestResponse
    {
        return $this->post($path, $fields, ['X-Twilio-Signature' => VerifyTwilioSignature::sign('token', url($path), $fields)]);
    }

    private function logCall(array $fields = []): Call
    {
        return Call::create([
            'call_sid' => 'CA1', 'direction' => 'inbound', 'from_number' => '+18055550123', 'to_number' => '+18053348375',
            'status' => 'completed', 'duration_seconds' => 80, 'started_at' => now(), ...$fields,
        ]);
    }

    public function test_recording_status_webhook_saves_the_recording(): void
    {
        $call = $this->logCall();

        $this->twilio('/api/twilio/voice/recording-status', [
            'CallSid' => 'CA1', 'RecordingSid' => 'RE1', 'RecordingStatus' => 'completed', 'RecordingDuration' => '75',
        ])->assertNoContent();

        $this->assertSame(['RE1', 75], [$call->fresh()->recording_sid, $call->fresh()->recording_duration_seconds]);

        // A shorter second recording of the same call does not replace the first one.
        $this->twilio('/api/twilio/voice/recording-status', [
            'CallSid' => 'CA1', 'RecordingSid' => 'RE2', 'RecordingStatus' => 'completed', 'RecordingDuration' => '10',
        ]);
        $this->assertSame('RE1', $call->fresh()->recording_sid);
    }

    public function test_recording_status_webhook_checks_the_signature(): void
    {
        $this->logCall();

        $this->post('/api/twilio/voice/recording-status', ['CallSid' => 'CA1', 'RecordingSid' => 'RE1', 'RecordingStatus' => 'completed'],
            ['X-Twilio-Signature' => 'forged'])->assertForbidden();

        $this->assertNull(Call::sole()->recording_sid);
    }

    public function test_finished_incoming_call_queues_a_recording_lookup(): void
    {
        Queue::fake();
        $call = $this->logCall(['status' => 'ringing', 'duration_seconds' => null]);

        $this->twilio('/api/twilio/voice/status', ['CallSid' => 'CA1', 'CallStatus' => 'completed', 'CallDuration' => '80', 'Direction' => 'inbound']);

        Queue::assertPushed(FetchCallRecording::class, fn (FetchCallRecording $job) => $job->callId === $call->id && $job->delay !== null);
    }

    public function test_recording_lookup_saves_the_longest_finished_recording(): void
    {
        Http::fake(['api.twilio.com/2010-04-01/Accounts/AC1/Calls/CA1/Recordings.json' => Http::response(['recordings' => [
            ['sid' => 'RE1', 'status' => 'completed', 'duration' => '30'],
            ['sid' => 'RE2', 'status' => 'completed', 'duration' => '95'],
        ]])]);
        $call = $this->logCall();

        (new FetchCallRecording($call->id))->handle(app(TwilioApi::class));

        $this->assertSame(['RE2', 95], [$call->fresh()->recording_sid, $call->fresh()->recording_duration_seconds]);
    }

    public function test_recording_lookup_waits_while_twilio_processes_the_recording(): void
    {
        Http::fake(['api.twilio.com/*' => Http::response(['recordings' => [['sid' => 'RE1', 'status' => 'processing', 'duration' => '-1']]])]);
        $call = $this->logCall();

        $job = (new FetchCallRecording($call->id))->withFakeQueueInteractions();
        $job->handle(app(TwilioApi::class));

        $job->assertReleased();
        $this->assertNull($call->fresh()->recording_sid);
    }

    public function test_recording_is_streamed_through_laravel_for_signed_in_users(): void
    {
        Http::fake(['api.twilio.com/2010-04-01/Accounts/AC1/Recordings/RE1.mp3' => Http::response('mp3-bytes', 206, [
            'Content-Type' => 'audio/mpeg', 'Content-Length' => '9', 'Content-Range' => 'bytes 0-8/9000',
        ])]);
        $call = $this->logCall(['recording_sid' => 'RE1', 'recording_duration_seconds' => 75]);
        $this->signIn();

        $response = $this->get("/crm/calls/{$call->id}/recording", ['Range' => 'bytes=0-8']);

        $response->assertStatus(206)->assertHeader('Content-Type', 'audio/mpeg')->assertHeader('Content-Range', 'bytes 0-8/9000');
        $this->assertSame('mp3-bytes', $response->streamedContent());
        $this->assertStringNotContainsString('twilio.com', $response->streamedContent());
        Http::assertSent(fn (Request $request) => $request->hasHeader('Range', 'bytes=0-8') && $request->hasHeader('Authorization'));
    }

    public function test_recording_requires_login_and_two_factor(): void
    {
        Http::fake();
        $call = $this->logCall(['recording_sid' => 'RE1']);

        $this->get("/crm/calls/{$call->id}/recording")->assertUnauthorized();

        $this->actingAs(User::factory()->create()); // no 2FA yet
        $this->get("/crm/calls/{$call->id}/recording")->assertRedirect(route('profile.show'));

        Http::assertNothingSent();
    }

    public function test_call_without_recording_has_nothing_to_play(): void
    {
        Http::fake();
        $call = $this->logCall();
        $this->signIn();

        $this->get("/crm/calls/{$call->id}/recording")->assertNotFound();
        Http::assertNothingSent();
    }
}
