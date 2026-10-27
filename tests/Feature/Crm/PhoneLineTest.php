<?php

namespace Tests\Feature\Crm;

use App\Http\Middleware\VerifyTwilioSignature;
use App\Models\VoiceCall;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

class PhoneLineTest extends CrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['voice.api_key' => 'voice-key', 'services.twilio.auth_token' => 'token']);
        Mail::fake();
    }

    public function test_owner_switches_the_mode_and_voice_service_reads_it(): void
    {
        $this->withToken('voice-key')->getJson('/api/voice/settings')->assertExactJson(['mode' => 'ai', 'ai_percent' => 50]);

        $this->signIn();
        $this->put('/crm/phone', ['mode' => 'menu', 'ai_percent' => 50, 'short_call_seconds' => 10])->assertRedirect();
        $this->withToken('voice-key')->getJson('/api/voice/settings')->assertJson(['mode' => 'menu']);
        $this->put('/crm/phone', ['mode' => 'ab', 'ai_percent' => 70, 'short_call_seconds' => 15])->assertRedirect();
        $this->put('/crm/phone', ['mode' => 'robot', 'ai_percent' => 170, 'short_call_seconds' => 0])
            ->assertSessionHasErrors(['mode', 'ai_percent', 'short_call_seconds']);

        $this->withToken('voice-key')->getJson('/api/voice/settings')->assertExactJson(['mode' => 'ab', 'ai_percent' => 70]);
        $this->withToken('wrong')->getJson('/api/voice/settings')->assertUnauthorized();
    }

    public function test_call_pieces_from_voice_service_twilio_and_leads_add_up_to_statistics(): void
    {
        $this->signIn();
        $this->put('/crm/phone', ['mode' => 'ab', 'ai_percent' => 50, 'short_call_seconds' => 10]);
        $report = fn (array $fields) => $this->withToken('voice-key')->postJson('/api/voice/calls', $fields)->assertOk();
        $duration = function (string $sid, int $seconds) {
            $fields = ['CallSid' => $sid, 'CallStatus' => 'completed', 'CallDuration' => (string) $seconds];
            $url = url('/api/twilio/voice/status');
            $this->post($url, $fields, ['X-Twilio-Signature' => VerifyTwilioSignature::sign('token', $url, $fields)])->assertNoContent();
        };

        // A: one caller hangs up during the greeting, one talks to the AI and leaves a lead.
        $report(['call_sid' => 'CA1', 'variant' => 'ai']);
        $duration('CA1', 4);
        $duration('CA2', 95); // Twilio's status can arrive before voice-service's report.
        $report(['call_sid' => 'CA2', 'variant' => 'ai']);
        $report(['call_sid' => 'CA2', 'reached_ai' => true, 'ai_turns' => 6, 'input_tokens' => 1_000_000, 'output_tokens' => 200_000]);
        $this->withToken('voice-key')->postJson('/api/voice/leads', [
            'call_sid' => 'CA2', 'caller_phone' => '+18055550123', 'consent_to_callback' => true, 'outcome' => 'visit',
        ])->assertCreated();

        // B: one transfer answered, one missed and then handled by the AI.
        $report(['call_sid' => 'CA3', 'variant' => 'transfer', 'transfer_status' => 'answered']);
        $duration('CA3', 120);
        $report(['call_sid' => 'CA4', 'variant' => 'transfer', 'transfer_status' => 'missed', 'reached_ai' => true]);
        $report(['call_sid' => 'CA5', 'rate_limited' => true]);

        // Menu: one caller pressed 2.
        $report(['call_sid' => 'CA6', 'variant' => 'menu']);
        $report(['call_sid' => 'CA6', 'menu_choice' => 'technician', 'transfer_status' => 'answered']);

        $this->assertSame(6, VoiceCall::count());
        $this->assertTrue(VoiceCall::firstWhere('call_sid', 'CA2')->lead_created);

        $this->get('/crm/phone?days=7')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Crm/PhoneLine')
            ->where('settings.short_call_seconds', 10)
            ->where('rateLimited', 1)
            ->has('variants', 3)
            ->where('variants.0.label', 'Caller chooses (menu)')
            ->where('variants.0.menu.technician', 1)
            ->where('variants.0.transfer_answered', 1)
            ->where('variants.1.calls', 2)
            ->where('variants.1.short', 1)
            ->where('variants.1.short_percent', 50)
            ->where('variants.1.avg_seconds', 50)
            ->where('variants.1.leads', 1)
            ->where('variants.1.ai_cost', 0.2)
            ->where('variants.2.calls', 2)
            ->where('variants.2.transfer_answered', 1)
            ->where('variants.2.transfer_missed', 1)
            ->where('variants.2.reached_ai', 1)
            ->where('variants.2.with_duration', 1));
    }
}
