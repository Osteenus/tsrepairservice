<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyTwilioSignature;
use App\Mail\WebFormRequestReceivedEmail;
use App\Models\RepairRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class TwilioVoiceTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN = 'test-auth-token';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.twilio.auth_token' => self::TOKEN]);
        Mail::fake();
    }

    /** Posts like Twilio does: form fields plus X-Twilio-Signature over the full URL. */
    private function twilio(string $path, array $fields = []): TestResponse
    {
        $fields = ['CallSid' => 'CA123', 'From' => '+18055550123', ...$fields];
        $signature = VerifyTwilioSignature::sign(self::TOKEN, url($path), $fields);

        return $this->post($path, $fields, ['X-Twilio-Signature' => $signature]);
    }

    public function test_unsigned_or_badly_signed_requests_are_rejected(): void
    {
        $this->post('/api/twilio/voice', ['CallSid' => 'CA123'])->assertForbidden();
        $this->post('/api/twilio/voice', ['CallSid' => 'CA123'], ['X-Twilio-Signature' => 'bad'])->assertForbidden();

        config(['services.twilio.auth_token' => '']);
        $this->twilio('/api/twilio/voice')->assertForbidden();
    }

    public function test_full_call_creates_a_phone_request_and_notifies(): void
    {
        $this->twilio('/api/twilio/voice')->assertOk()
            ->assertHeader('Content-Type', 'text/xml; charset=UTF-8')
            ->assertSee('<Gather input="speech" action="/api/twilio/voice/answer/name"', false);

        $this->twilio('/api/twilio/voice/answer/name', ['SpeechResult' => 'John Smith'])
            ->assertSee('action="/api/twilio/voice/answer/problem"', false);
        $this->twilio('/api/twilio/voice/answer/problem', ['SpeechResult' => 'My dishwasher is not draining.'])
            ->assertSee('action="/api/twilio/voice/answer/location"', false);
        $this->twilio('/api/twilio/voice/answer/location', ['SpeechResult' => 'Moorpark'])
            ->assertSee('will call you back', false)->assertSee('<Hangup/>', false);

        $request = RepairRequest::with('contact')->sole();
        $this->assertSame('phone', $request->source);
        $this->assertSame('CA123', $request->call_sid);
        $this->assertSame(5, $request->service_id);
        $this->assertSame('Moorpark', $request->location);
        $this->assertSame('My dishwasher is not draining.', $request->description);
        $this->assertSame('John Smith', $request->contact->name);
        $this->assertSame('(805) 555-0123', $request->contact->phone);
        Mail::assertQueued(WebFormRequestReceivedEmail::class, 1);

        // The hang-up callback after a finished call does not add a second request.
        $this->twilio('/api/twilio/voice/status', ['CallStatus' => 'completed'])->assertNoContent();
        $this->assertDatabaseCount('repair_requests', 1);
    }

    public function test_silence_repeats_the_question_once_then_moves_on(): void
    {
        $this->twilio('/api/twilio/voice');
        $this->twilio('/api/twilio/voice/answer/name')->assertSee('did not catch that')
            ->assertSee('action="/api/twilio/voice/answer/name"', false);
        $this->twilio('/api/twilio/voice/answer/name')
            ->assertSee('action="/api/twilio/voice/answer/problem"', false);
    }

    public function test_hang_up_midway_keeps_what_the_caller_said(): void
    {
        $this->twilio('/api/twilio/voice');
        $this->twilio('/api/twilio/voice/answer/name', ['SpeechResult' => 'Jane']);
        $this->twilio('/api/twilio/voice/answer/problem', ['SpeechResult' => 'fridge is warm']);
        $this->twilio('/api/twilio/voice/status', ['CallStatus' => 'completed'])->assertNoContent();

        $request = RepairRequest::with('contact')->sole();
        $this->assertSame(1, $request->service_id);
        $this->assertNull($request->location);
        $this->assertSame('Jane', $request->contact->name);
        Mail::assertQueued(WebFormRequestReceivedEmail::class, 1);
    }

    public function test_silent_call_creates_nothing(): void
    {
        $this->twilio('/api/twilio/voice');
        $this->twilio('/api/twilio/voice/status', ['CallStatus' => 'completed']);

        $this->assertDatabaseCount('repair_requests', 0);
        Mail::assertNothingQueued();
    }
}
