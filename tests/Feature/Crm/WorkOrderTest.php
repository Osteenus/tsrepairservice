<?php

namespace Tests\Feature\Crm;

use App\Models\Appliance;
use App\Models\Customer;
use App\Models\WorkOrder;
use Illuminate\Support\Carbon;

class WorkOrderTest extends CrmTestCase
{
    public function test_a_job_is_created_with_the_customer_address_and_pacific_schedule(): void
    {
        $this->signIn();
        $appliance = Appliance::factory()->create();
        $customer = $appliance->customer;

        $this->post(route('crm.jobs.store'), [
            'customer_id' => $customer->id,
            'appliance_id' => $appliance->id,
            'reported_problem' => 'Not draining.',
            'scheduled_at' => '2026-10-15T09:30',
        ])->assertRedirect();

        $job = WorkOrder::sole();
        $this->assertSame($customer->fullAddress(), $job->service_address);
        // 9:30 AM PDT is 16:30 UTC.
        $this->assertSame('2026-10-15 16:30:00', $job->scheduled_at->utc()->format('Y-m-d H:i:s'));
    }

    public function test_appliance_must_belong_to_the_customer(): void
    {
        $this->signIn();
        $customer = Customer::factory()->create();
        $foreign = Appliance::factory()->create();

        $this->post(route('crm.jobs.store'), [
            'customer_id' => $customer->id, 'appliance_id' => $foreign->id, 'reported_problem' => 'X',
        ])->assertSessionHasErrors('appliance_id');
    }

    public function test_only_manual_statuses_can_be_set_by_hand(): void
    {
        $this->signIn();
        $job = WorkOrder::factory()->create();

        $this->patch(route('crm.jobs.status', $job), ['status' => 'cancelled'])->assertRedirect();
        $this->assertSame('cancelled', $job->fresh()->status);

        foreach (['approved', 'paid', 'estimate_sent', 'bogus'] as $status) {
            $this->patch(route('crm.jobs.status', $job), ['status' => $status])->assertSessionHasErrors('status');
        }
    }

    public function test_today_filter_uses_pacific_time(): void
    {
        $this->signIn();
        Carbon::setTestNow('2026-10-15 06:00:00'); // 11 PM Oct 14 in Los Angeles
        WorkOrder::factory()->create(['scheduled_at' => '2026-10-15 05:00:00']); // 10 PM Oct 14 PDT
        WorkOrder::factory()->create(['scheduled_at' => '2026-10-15 16:00:00']); // 9 AM Oct 15 PDT

        $this->get(route('crm.jobs.index', ['filter' => 'today']))->assertInertia(fn ($page) => $page->has('jobs.data', 1));
        Carbon::setTestNow();
    }

    public function test_job_pages_render(): void
    {
        $this->signIn();
        $job = WorkOrder::factory()->create();

        $this->get(route('crm.jobs.index'))->assertOk();
        $this->get(route('crm.jobs.show', $job))->assertInertia(fn ($page) => $page->component('Crm/Jobs/Show'));
        $this->get(route('crm.jobs.edit', $job))->assertOk();
        $this->get(route('crm.jobs.create', ['customer' => $job->customer_id]))->assertOk();
        $this->get(route('crm.customers.show', $job->customer_id))->assertInertia(fn ($page) => $page->has('jobs', 1));
    }
}
