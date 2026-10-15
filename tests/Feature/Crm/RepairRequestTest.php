<?php

namespace Tests\Feature\Crm;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\RepairRequest;
use App\Models\WorkOrder;

class RepairRequestTest extends CrmTestCase
{
    private function websiteRequest(array $contact = [], array $request = []): RepairRequest
    {
        $contact = Contact::create(['name' => 'Jane Doe', 'phone' => '(805) 555-0100', 'email' => 'jane@example.com', ...$contact]);

        return RepairRequest::create([
            'contact_id' => $contact->id, 'service_id' => 2, 'location' => 'Moorpark',
            'brand' => 'Whirlpool', 'model' => 'WTW4950XW2', 'description' => 'Shakes during spin.', ...$request,
        ]);
    }

    public function test_new_requests_are_listed_and_can_be_opened(): void
    {
        $this->signIn();
        $request = $this->websiteRequest();

        $this->get(route('crm.requests.index'))
            ->assertInertia(fn ($page) => $page->has('requests.data', 1)->where('requests.data.0.appliance', 'Washer'));
        $this->get(route('crm.requests.show', $request))
            ->assertInertia(fn ($page) => $page->where('request.phone', '(805) 555-0100'));
    }

    public function test_a_request_becomes_a_customer_appliance_and_job_once(): void
    {
        $this->signIn();
        $request = $this->websiteRequest(request: ['location' => '93021']);

        $this->post(route('crm.requests.convert', $request))->assertRedirect();
        $this->post(route('crm.requests.convert', $request))->assertRedirect();

        $job = WorkOrder::sole();
        $this->assertSame($request->id, $job->repair_request_id);
        $this->assertSame('Shakes during spin.', $job->reported_problem);
        $this->assertSame('new', $job->status);
        $this->assertSame('93021', $job->customer->zip);
        $this->assertSame('Washer', $job->appliance->type);
        $this->assertSame('WTW4950XW2', $job->appliance->model);
        $this->assertNotNull($request->fresh()->archived_at);
    }

    public function test_conversion_reuses_a_customer_with_the_same_phone(): void
    {
        $this->signIn();
        $customer = Customer::factory()->create(['phone' => '805-555-0100', 'email' => null]);

        $this->post(route('crm.requests.convert', $this->websiteRequest(['phone' => '+1 805 555 0100'])));

        $this->assertSame(1, Customer::count());
        $this->assertSame($customer->id, WorkOrder::sole()->customer_id);
        $this->assertSame('jane@example.com', $customer->fresh()->email);
    }

    public function test_requests_can_be_archived_without_a_job(): void
    {
        $this->signIn();
        $request = $this->websiteRequest();

        $this->post(route('crm.requests.archive', $request));

        $this->assertNotNull($request->fresh()->archived_at);
        $this->get(route('crm.requests.index'))->assertInertia(fn ($page) => $page->has('requests.data', 0));
    }

    public function test_the_public_form_still_creates_requests(): void
    {
        $this->post('/contact', [
            'name' => 'Jane Doe', 'phone' => '805.555.0100', 'email' => ' Jane@Example.COM', 'location' => 'Moorpark',
            'serviceId' => 1, 'model' => 'lrfvs3006s', 'description' => 'Not cooling.',
        ])->assertRedirect('/contact');

        $request = RepairRequest::sole();
        $this->assertNull($request->archived_at);
        $this->assertSame('LRFVS3006S', $request->model);
        $this->assertSame('(805) 555-0100', $request->contact->phone);
        $this->assertSame('jane@example.com', $request->contact->email);
    }
}
