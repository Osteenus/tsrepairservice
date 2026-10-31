<?php

namespace Tests\Feature\Crm;

use App\Http\Middleware\VerifyTwilioSignature;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;

class BusinessLineCallTest extends CrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['services.twilio' => [
            'account_sid' => 'AC1', 'auth_token' => 'token', 'number' => '+18053348375',
            'owner_phone' => '+18055550199', 'voice' => 'Polly.Joanna-Neural',
        ]]);
    }

    private function twilio(string $path, array $fields): TestResponse
    {
        return $this->post($path, $fields, ['X-Twilio-Signature' => VerifyTwilioSignature::sign('token', url($path), $fields)]);
    }

    public function test_call_rings_the_owner_then_press_1_dials_the_customer_from_the_business_line(): void
    {
        Http::fake(['api.twilio.com/*' => Http::response(['sid' => 'CAout'], 201)]);
        $this->signIn();

        $this->from('/crm/customers')->post('/crm/call', ['phone' => '(805) 555-0123', 'name' => 'Jane Doe'])
            ->assertRedirect('/crm/customers')->assertSessionHas('flash.banner', 'Calling your phone now. Answer and press 1 to connect.');

        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC1/Calls.json'
            && $request['To'] === '+18055550199' && $request['From'] === '+18053348375'
            && str_contains($request['Twiml'], 'Business line call to Jane Doe. Press 1 to connect.'));

        $this->twilio('/api/twilio/voice/connect', ['CallSid' => 'CAout', 'Digits' => '1'])
            ->assertOk()->assertSee('<Dial callerId="+18053348375" timeout="30" method="POST" action="'.url('/api/twilio/voice/dialed').'">+18055550123</Dial>', false);
        // Used once: a replayed key press does not dial again.
        $this->twilio('/api/twilio/voice/connect', ['CallSid' => 'CAout', 'Digits' => '1'])->assertSee('Call cancelled.');
    }

    public function test_other_keys_cancel(): void
    {
        Http::fake(['api.twilio.com/*' => Http::response(['sid' => 'CAout'], 201)]);
        $this->signIn();
        $this->post('/crm/call', ['phone' => '8055550123']);

        $this->twilio('/api/twilio/voice/connect', ['CallSid' => 'CAout', 'Digits' => '2'])
            ->assertSee('Call cancelled.')->assertDontSee('<Dial', false);
    }

    public function test_problems_are_reported_without_calling(): void
    {
        Http::fake(['api.twilio.com/*' => Http::response(['message' => 'bad'], 400)]);
        $this->signIn();

        $this->post('/crm/call', ['phone' => '555-0123 ext 4'])->assertSessionHas('flash.bannerStyle', 'danger');
        $this->post('/crm/call', ['phone' => '(805) 555-0123'])
            ->assertSessionHas('flash.banner', 'Twilio could not start the call. Try again or use the regular Call button.');

        config(['services.twilio.owner_phone' => null]);
        $this->post('/crm/call', ['phone' => '(805) 555-0123'])->assertSessionHas('flash.bannerStyle', 'danger');
        Http::assertSentCount(1);
    }

    public function test_guests_cannot_place_calls(): void
    {
        Http::fake();
        $this->post('/crm/call', ['phone' => '(805) 555-0123'])->assertRedirect();
        Http::assertNothingSent();
    }
}
