<?php

namespace Tests\Feature;

use App\Mail\VoiceLeadMail;
use App\Models\Customer;
use App\Models\RepairRequest;
use App\Models\User;
use App\Models\VoiceLead;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class VoiceLeadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['voice.api_key' => 'voice-key', 'voice.service_url' => 'http://voice.test']);
        Mail::fake();
        // Wednesday 10:00 Pacific: technicians are working.
        $this->travelTo(CarbonImmutable::parse('2026-10-21 10:00', 'America/Los_Angeles'));
    }

    private function lead(array $overrides = []): TestResponse
    {
        return $this->withToken('voice-key')->postJson('/api/voice/leads', array_replace([
            'call_sid' => 'CA100', 'caller_phone' => '+18055550123', 'caller_name' => 'Jane Doe',
            'service_address' => '123 Main St, Moorpark', 'appliance_type' => 'dishwasher', 'appliance_brand' => 'Bosch',
            'reported_problem' => 'Not draining', 'agent_summary' => 'Bosch dishwasher, water left at the bottom.',
            'consent_to_callback' => true, 'fee_accepted' => true, 'outcome' => 'visit', 'recording_notice_played' => true,
        ], $overrides));
    }

    public function test_api_requires_the_voice_key(): void
    {
        $this->postJson('/api/voice/leads', [])->assertUnauthorized();
        $this->withToken('wrong')->getJson('/api/voice/availability')->assertUnauthorized();
        config(['voice.api_key' => '']);
        $this->withToken('')->getJson('/api/voice/availability')->assertUnauthorized();
        $this->assertDatabaseCount('voice_leads', 0);
    }

    public function test_lead_is_idempotent_by_call_sid_and_notifies_the_owner_once(): void
    {
        $this->lead()->assertCreated()->assertJson(['status' => 'callback_due']);
        $this->lead(['caller_name' => 'Changed'])->assertOk();

        $lead = VoiceLead::sole();
        $this->assertSame('Jane Doe', $lead->caller_name);
        $this->assertSame('(805) 555-0123', $lead->caller_phone);
        $this->assertTrue($lead->recording_notice_played);
        $this->assertTrue($lead->fee_accepted);
        $this->assertStringContainsString('Agreed to the $95 service call fee: yes.', RepairRequest::sole()->description);
        $this->assertTrue($lead->callback_due_at->equalTo(now()->addMinutes(20)));

        // It is also in the CRM inbox as a phone request.
        $request = RepairRequest::sole();
        $this->assertSame('phone', $request->source);
        $this->assertSame(5, $request->service_id);
        $this->assertSame($request->id, $lead->repair_request_id);
        Mail::assertQueued(VoiceLeadMail::class, fn (VoiceLeadMail $mail) => $mail->kind === 'new');
        Mail::assertQueuedCount(1);
    }

    public function test_lead_without_consent_is_not_chased_and_known_customers_are_linked(): void
    {
        $customer = Customer::create(['name' => 'Jane Doe', 'phone' => '(805) 555-0123']);
        $this->lead(['consent_to_callback' => false, 'outcome' => 'hangup'])->assertCreated()->assertJson(['status' => 'new']);

        $lead = VoiceLead::sole();
        $this->assertNull($lead->callback_due_at);
        $this->assertSame($customer->id, $lead->customer_id);
        $this->withToken('voice-key')->getJson('/api/voice/customers?phone=%2B18055550123')
            ->assertOk()->assertJson(['id' => $customer->id, 'name' => 'Jane Doe']);
        $this->withToken('voice-key')->getJson('/api/voice/customers?phone=8055559999')->assertNotFound();
    }

    public function test_after_hours_the_call_back_is_due_after_the_next_opening(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-10-24 20:00', 'America/Los_Angeles')); // Saturday evening
        $this->withToken('voice-key')->getJson('/api/voice/availability')->assertJson(['technician_available' => false]);
        $this->lead()->assertCreated();

        // Sunday is closed, Monday opens at 8:00.
        $this->assertTrue(VoiceLead::sole()->callback_due_at->equalTo(CarbonImmutable::parse('2026-10-26 08:20', 'America/Los_Angeles')));
    }

    public function test_scheduler_makes_one_follow_up_then_marks_overdue(): void
    {
        Http::fake(['voice.test/*' => Http::response(['ok' => true])]);
        $this->lead()->assertCreated();
        $lead = VoiceLead::sole();

        $this->artisan('voice:follow-up');
        Http::assertNothingSent(); // Not due yet.

        $this->travel(21)->minutes();
        $this->artisan('voice:follow-up');
        $this->artisan('voice:follow-up');
        Http::assertSentCount(1);
        Http::assertSent(fn (Request $request) => $request->url() === "http://voice.test/followup/{$lead->id}"
            && $request->hasHeader('Authorization', 'Bearer voice-key') && $request['phone'] === '(805) 555-0123');
        $this->assertSame(1, $lead->fresh()->followup_count);
        $this->assertSame('callback_due', $lead->fresh()->status);

        $this->travel(30)->minutes();
        $this->artisan('voice:follow-up');
        $this->artisan('voice:follow-up');
        $this->assertSame('overdue', $lead->fresh()->status);
        Http::assertSentCount(1);
        Mail::assertQueued(VoiceLeadMail::class, fn (VoiceLeadMail $mail) => $mail->kind === 'overdue');
        Mail::assertQueuedCount(2);
    }

    public function test_called_back_or_answered_transfer_stops_the_follow_up(): void
    {
        Http::fake();
        $this->lead()->assertCreated();
        $this->lead(['call_sid' => 'CA200'])->assertCreated();

        $this->withToken('voice-key')->postJson('/api/voice/leads/CA200/transfer', ['answered' => true])->assertOk();
        $this->actingAs(User::factory()->create([
            'two_factor_secret' => encrypt('secret'), 'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt(json_encode(['code'])),
        ]));
        $this->post('/crm/requests/'.VoiceLead::firstWhere('call_sid', 'CA100')->repair_request_id.'/called-back')->assertRedirect();

        $this->travel(2)->hours();
        $this->artisan('voice:follow-up');
        Http::assertNothingSent();
        $this->assertSame(['callback_done'], VoiceLead::pluck('status')->unique()->values()->all());
    }

    public function test_timed_out_call_is_due_for_the_owner_but_gets_no_ai_follow_up(): void
    {
        Http::fake();
        $this->lead(['consent_to_callback' => false, 'outcome' => 'timeout'])->assertCreated()->assertJson(['status' => 'callback_due']);

        $this->travel(21)->minutes();
        $this->artisan('voice:follow-up');
        Http::assertNothingSent();
        $this->travel(30)->minutes();
        $this->artisan('voice:follow-up');
        $this->assertSame('overdue', VoiceLead::sole()->status);
    }

    public function test_follow_up_result_is_saved(): void
    {
        $this->lead()->assertCreated();
        $lead = VoiceLead::sole();

        $this->withToken('voice-key')->postJson("/api/voice/leads/{$lead->id}/followup", ['still_needs_help' => false, 'notes' => 'Fixed it herself.'])
            ->assertOk()->assertJson(['status' => 'callback_done']);
        $this->assertSame('No longer needs help. Fixed it herself.', $lead->fresh()->followup_notes);
    }
}
