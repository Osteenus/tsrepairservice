<?php

namespace Tests\Feature\Crm;

use App\Models\BlockedNumber;
use App\Models\Call;
use App\Models\Customer;
use Inertia\Testing\AssertableInertia as Assert;

class CallsScreenTest extends CrmTestCase
{
    private function logCall(string $sid, ?string $from, string $startedAt, array $fields = []): Call
    {
        return Call::create(array_replace([
            'call_sid' => $sid, 'direction' => 'inbound', 'from_number' => $from, 'to_number' => '+18053348375',
            'status' => 'completed', 'duration_seconds' => 42, 'started_at' => $startedAt,
            'customer_id' => Call::customerIdFor($from),
        ], $fields));
    }

    public function test_calls_list_filters_by_period_and_number(): void
    {
        $this->travelTo('2026-10-30 20:00:00'); // 1 PM Pacific
        $customer = Customer::create(['name' => 'Jane Doe', 'phone' => '(805) 555-0123']);
        $this->logCall('CA1', '+18055550123', '2026-10-30 18:00:00');
        $this->logCall('CA2', '+18055559999', '2026-10-27 18:00:00');
        $this->logCall('CA3', '+18055558888', '2026-10-10 18:00:00');
        $this->logCall('CA4', '+18055557777', '2026-10-30 19:00:00', ['direction' => 'outbound', 'from_number' => '+18053348375', 'to_number' => '+18055557777']);
        $this->signIn();

        $this->get('/crm/calls')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Crm/Calls')
            ->where('period', '7')
            ->has('calls', 3)
            ->where('calls.0.direction', 'outbound')
            ->where('calls.0.display_number', '(805) 555-7777')
            ->where('calls.1.customer.name', 'Jane Doe')
            ->where('calls.1.customer.id', $customer->id)
            ->where('calls.1.duration_seconds', 42)
            ->where('calls.1.time', 'Fri, Oct 30, 11:00 AM'));

        $this->get('/crm/calls?period=today')->assertInertia(fn (Assert $page) => $page->has('calls', 2));
        $this->get('/crm/calls?period=30')->assertInertia(fn (Assert $page) => $page->has('calls', 4));
        $this->get('/crm/calls?period=30&search=555-9999')->assertInertia(fn (Assert $page) => $page
            ->has('calls', 1)->where('calls.0.number', '+18055559999'));
    }

    public function test_frequent_callers_counts_incoming_calls_over_the_last_7_days(): void
    {
        $this->travelTo('2026-10-30 20:00:00');
        foreach (['2026-10-30 18:00:00', '2026-10-29 18:00:00', '2026-10-25 18:00:00', '2026-10-20 18:00:00'] as $i => $time) {
            $this->logCall("CAa{$i}", '+18055550123', $time); // three in the last 7 days, one older
        }
        $this->logCall('CAb1', '+18055559999', '2026-10-28 18:00:00');
        $this->logCall('CAb2', '+18055559999', '2026-10-28 19:00:00');
        $this->logCall('CAc1', '+18055558888', '2026-10-28 18:00:00'); // one call: not frequent
        // Outgoing calls do not count.
        $this->logCall('CAo1', '+18053348375', '2026-10-30 19:00:00', ['direction' => 'outbound', 'to_number' => '+18055559999']);
        // Test calls from the owner's second phone do not count.
        foreach (range(1, 3) as $i) {
            $this->logCall("CAt{$i}", '+18059912874', '2026-10-30 1'.$i.':00:00', ['is_internal' => true]);
        }
        $this->signIn();

        $this->get('/crm/calls')->assertInertia(fn (Assert $page) => $page
            ->where('repeatCalls', 3)
            ->has('frequent', 2)
            ->where('frequent.0.number', '+18055550123')
            ->where('frequent.0.calls', 3)
            ->where('frequent.0.repeat', true)
            ->where('frequent.0.last_call', 'Fri, Oct 30, 11:00 AM')
            ->where('frequent.1.number', '+18055559999')
            ->where('frequent.1.calls', 2)
            ->where('frequent.1.repeat', false));

        // Blocked numbers leave the list.
        BlockedNumber::block('+18055559999');
        $this->get('/crm/calls')->assertInertia(fn (Assert $page) => $page
            ->has('frequent', 1)->where('frequent.0.number', '+18055550123')->has('blocked', 1));
    }

    public function test_calls_of_blocked_numbers_are_hidden_until_unblocked(): void
    {
        $this->travelTo('2026-10-30 20:00:00');
        $this->logCall('CA1', '+18052659449', '2026-10-30 18:00:00');
        $this->logCall('CA2', '+18055550123', '2026-10-30 17:00:00');
        $this->logCall('CA3', '+18053348375', '2026-10-30 16:00:00', ['direction' => 'outbound', 'to_number' => '+18052659449']);
        $this->logCall('CA4', null, '2026-10-30 15:00:00');
        $blocked = BlockedNumber::block('+18052659449');
        $this->signIn();

        $this->get('/crm/calls')->assertInertia(fn (Assert $page) => $page
            ->has('calls', 2)->where('calls.0.number', '+18055550123')->where('calls.1.number', null)
            ->has('blocked', 1)->where('blocked.0.display_number', '(805) 265-9449'));
        $this->get('/crm/calls?search=2659449')->assertInertia(fn (Assert $page) => $page->has('calls', 0));

        $blocked->delete();
        $this->get('/crm/calls')->assertInertia(fn (Assert $page) => $page->has('calls', 4));
    }

    public function test_test_calls_are_marked_and_cannot_be_blocked(): void
    {
        $this->travelTo('2026-10-30 20:00:00');
        $this->logCall('CA1', '+18059912874', '2026-10-30 18:00:00', ['is_internal' => true]);
        $this->logCall('CA2', '+18055550123', '2026-10-30 17:00:00');
        $this->signIn();

        $this->get('/crm/calls')->assertInertia(fn (Assert $page) => $page
            ->where('calls.0.internal', true)->where('calls.0.blockable', false)->where('calls.0.callable', true)
            ->where('calls.1.internal', false)->where('calls.1.blockable', true)->where('calls.1.callable', true));

        $this->post('/crm/blocked-numbers', ['phone' => '(805) 991-2874'])
            ->assertSessionHas('flash.banner', 'The business line and your own test phones cannot be blocked.');
        $this->assertSame(0, BlockedNumber::withTrashed()->count());
    }

    public function test_business_number_is_never_blocked(): void
    {
        $this->signIn();

        foreach (['(805) 334-8375', '+18053348375', '8053348375'] as $number) {
            $this->post('/crm/blocked-numbers', ['phone' => $number])->assertSessionHas('flash.bannerStyle', 'danger');
        }
        $this->assertNull(BlockedNumber::block('+18053348375'));
        $this->assertSame(0, BlockedNumber::withTrashed()->count());

        // Even a row written by hand does not reject the business line.
        BlockedNumber::forceCreate(['phone_e164' => '+18053348375', 'created_at' => now()]);
        $this->assertFalse(BlockedNumber::isBlocked('+18053348375'));
    }

    public function test_block_and_unblock_a_number(): void
    {
        $this->signIn();

        $this->post('/crm/blocked-numbers', ['phone' => '(805) 555-0123', 'reason' => 'Robocalls'])
            ->assertRedirect()->assertSessionHas('flash.banner', 'Blocked. Calls from this number are rejected.');
        $this->assertTrue(BlockedNumber::isBlocked('+18055550123'));
        $this->post('/crm/blocked-numbers', ['phone' => 'ext 12'])->assertSessionHas('flash.bannerStyle', 'danger');

        $blocked = BlockedNumber::sole();
        $this->delete("/crm/blocked-numbers/{$blocked->id}")->assertRedirect();
        $this->assertFalse(BlockedNumber::isBlocked('+18055550123'));
        $this->assertSoftDeleted($blocked);

        // Blocking again restores the same row.
        $this->post('/crm/blocked-numbers', ['phone' => '8055550123']);
        $this->assertSame(1, BlockedNumber::withTrashed()->count());
        $this->assertTrue(BlockedNumber::isBlocked('(805) 555-0123'));
    }

    public function test_customer_page_shows_their_calls(): void
    {
        $customer = Customer::create(['name' => 'Jane Doe', 'phone' => '(805) 555-0123']);
        $this->logCall('CA1', '+18055550123', '2026-10-30 18:00:00');
        $this->logCall('CA2', '+18055559999', '2026-10-30 18:00:00');
        $this->signIn();

        $this->get("/crm/customers/{$customer->id}")->assertInertia(fn (Assert $page) => $page
            ->has('calls', 1)->where('calls.0.number', '+18055550123')->where('phoneBlocked', false)->where('phoneBlockable', true));

        BlockedNumber::block($customer->phone);
        $this->get("/crm/customers/{$customer->id}")->assertInertia(fn (Assert $page) => $page
            ->where('phoneBlocked', true)->where('calls.0.blocked', true));
    }

    public function test_calls_screen_needs_a_signed_in_user_with_two_factor(): void
    {
        $this->get('/crm/calls')->assertRedirect('/login');
        $this->post('/crm/blocked-numbers', ['phone' => '8055550123'])->assertRedirect('/login');

        $this->actingAs(\App\Models\User::factory()->create());
        $this->get('/crm/calls')->assertRedirect(route('profile.show'));
        $this->assertSame(0, BlockedNumber::count());
    }
}
