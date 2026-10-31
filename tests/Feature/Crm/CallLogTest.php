<?php

namespace Tests\Feature\Crm;

use App\Http\Middleware\VerifyTwilioSignature;
use App\Models\BlockedNumber;
use App\Models\Call;
use App\Models\Customer;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use LogicException;

class CallLogTest extends CrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.twilio' => [
                'account_sid' => 'AC1', 'auth_token' => 'token', 'number' => '+18053348375',
                'owner_phone' => '+18055550199', 'voice' => 'Polly.Joanna-Neural',
            ],
            'voice.public_url' => 'https://voice.test',
        ]);
    }

    private function twilio(string $path, array $fields): TestResponse
    {
        return $this->post($path, $fields, ['X-Twilio-Signature' => VerifyTwilioSignature::sign('token', url($path), $fields)]);
    }

    private function incoming(string $sid, string $from = '+18055550123'): TestResponse
    {
        return $this->twilio('/api/twilio/voice/incoming', ['CallSid' => $sid, 'From' => $from, 'To' => '+18053348375']);
    }

    public function test_incoming_call_is_logged_and_passed_on_to_the_voice_service(): void
    {
        $this->incoming('CA1')->assertOk()->assertSee('<Redirect method="POST">https://voice.test/voice</Redirect>', false);
        // Twilio retries are not logged twice.
        $this->incoming('CA1')->assertOk();

        $call = Call::sole();
        $this->assertSame(['inbound', '+18055550123', '+18053348375', 'ringing'], [$call->direction, $call->from_number, $call->to_number, $call->status]);
        $this->assertNotNull($call->started_at);
        $this->assertNull($call->customer_id);
    }

    public function test_status_callback_sets_status_and_duration(): void
    {
        $this->incoming('CA1');
        $this->twilio('/api/twilio/voice/status', ['CallSid' => 'CA1', 'CallStatus' => 'completed', 'CallDuration' => '74', 'Direction' => 'inbound'])
            ->assertNoContent();

        $call = Call::sole();
        $this->assertSame(['completed', 74], [$call->status, $call->duration_seconds]);
        $this->assertNotNull($call->ended_at);
    }

    public function test_calls_that_skipped_the_incoming_webhook_are_logged_from_the_status_callback(): void
    {
        $this->twilio('/api/twilio/voice/status', [
            'CallSid' => 'CA2', 'CallStatus' => 'completed', 'CallDuration' => '30', 'Direction' => 'inbound',
            'From' => '+18055550123', 'To' => '+18053348375',
        ]);

        $this->assertSame(30, Call::sole()->duration_seconds);
    }

    public function test_blocked_number_is_rejected_and_not_logged(): void
    {
        BlockedNumber::block('(805) 555-0123', 'Spam');

        $this->incoming('CA1')->assertOk()->assertSee('<Reject/>', false)->assertDontSee('Redirect');
        $this->twilio('/api/twilio/voice/status', ['CallSid' => 'CA1', 'CallStatus' => 'busy', 'Direction' => 'inbound', 'From' => '+18055550123']);
        $this->assertSame(0, Call::count());

        // Unblocked numbers get through again.
        BlockedNumber::sole()->delete();
        $this->incoming('CA2')->assertSee('Redirect');
        $this->assertSoftDeleted('blocked_numbers', ['phone_e164' => '+18055550123']);
    }

    public function test_invalid_signature_is_refused(): void
    {
        $fields = ['CallSid' => 'CA1', 'From' => '+18055550123'];
        $this->post('/api/twilio/voice/incoming', $fields, ['X-Twilio-Signature' => 'forged'])->assertForbidden();
        $this->post('/api/twilio/voice/incoming', $fields)->assertForbidden();
        $this->post('/api/twilio/voice/status', $fields, ['X-Twilio-Signature' => VerifyTwilioSignature::sign('other', url('/api/twilio/voice/status'), $fields)])
            ->assertForbidden();
        $this->assertSame(0, Call::count());
    }

    public function test_call_is_linked_to_the_customer_by_phone(): void
    {
        $customer = Customer::create(['name' => 'Jane Doe', 'phone' => '805-555-0123']);

        $this->incoming('CA1', '+18055550123');
        $this->incoming('CA2', '+18055559999');

        $this->assertSame($customer->id, Call::firstWhere('call_sid', 'CA1')->customer_id);
        $this->assertNull(Call::firstWhere('call_sid', 'CA2')->customer_id);
    }

    public function test_click_to_call_rings_the_owner_and_logs_an_outbound_call(): void
    {
        Http::fake(['api.twilio.com/*' => Http::response(['sid' => 'CAout'], 201)]);
        $customer = Customer::create(['name' => 'Jane Doe', 'phone' => '(805) 555-0123']);
        $this->signIn();

        $this->from("/crm/customers/{$customer->id}")->post("/crm/customers/{$customer->id}/call")
            ->assertRedirect("/crm/customers/{$customer->id}")->assertSessionHas('flash.banner', 'Calling your phone now. Answer and press 1 to connect.');

        Http::assertSent(fn (Request $request) => $request['To'] === '+18055550199' && $request['From'] === '+18053348375'
            && $request['StatusCallback'] === url('/api/twilio/voice/status')
            && str_contains($request['Twiml'], 'Business line call to Jane Doe.'));

        $call = Call::sole();
        $this->assertSame(['CAout', 'outbound', '+18053348375', '+18055550123', $customer->id],
            [$call->call_sid, $call->direction, $call->from_number, $call->to_number, $call->customer_id]);

        // The customer sees the business number; the call's result comes from the Dial.
        $this->twilio('/api/twilio/voice/connect', ['CallSid' => 'CAout', 'Digits' => '1'])->assertSee('callerId="+18053348375"', false);
        $this->twilio('/api/twilio/voice/dialed', ['CallSid' => 'CAout', 'DialCallStatus' => 'completed', 'DialCallDuration' => '125'])
            ->assertSee('<Hangup/>', false);
        $this->twilio('/api/twilio/voice/status', ['CallSid' => 'CAout', 'CallStatus' => 'completed', 'CallDuration' => '140', 'Direction' => 'outbound-api']);

        $this->assertSame(['completed', 125], [$call->fresh()->status, $call->fresh()->duration_seconds]);
    }

    public function test_outbound_call_the_owner_did_not_connect_is_marked_not_connected(): void
    {
        Http::fake(['api.twilio.com/*' => Http::response(['sid' => 'CAout'], 201)]);
        $this->signIn();
        $this->post('/crm/call', ['phone' => '(805) 555-0123']);

        $this->twilio('/api/twilio/voice/status', ['CallSid' => 'CAout', 'CallStatus' => 'no-answer', 'Direction' => 'outbound-api']);

        $this->assertSame('not-connected', Call::sole()->status);
    }

    public function test_outbound_calls_are_limited_to_20_per_hour_per_user(): void
    {
        $sids = 0;
        Http::fake(['api.twilio.com/*' => function () use (&$sids) {
            return Http::response(['sid' => 'CA'.++$sids], 201);
        }]);
        $this->signIn();
        for ($i = 0; $i < 20; $i++) {
            $this->post('/crm/call', ['phone' => '(805) 555-0123'])->assertSessionHas('flash.banner', 'Calling your phone now. Answer and press 1 to connect.');
        }

        $this->post('/crm/call', ['phone' => '(805) 555-0123'])
            ->assertSessionHas('flash.banner', 'Too many calls in the last hour. Use the regular Call button.');
        $this->assertSame(20, Call::count());

        // Another CRM user has their own limit.
        $this->signIn();
        $this->post('/crm/call', ['phone' => '(805) 555-0123'])->assertSessionHas('flash.banner', 'Calling your phone now. Answer and press 1 to connect.');
    }

    public function test_calls_are_never_deleted(): void
    {
        $this->incoming('CA1');

        $this->expectException(LogicException::class);
        Call::sole()->delete();
    }
}
