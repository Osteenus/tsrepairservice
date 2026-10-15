<?php

namespace Tests\Feature\Crm;

use App\Models\Appliance;
use App\Models\Customer;

class CustomerTest extends CrmTestCase
{
    public function test_customers_can_be_created_and_updated(): void
    {
        $this->signIn();

        $this->post(route('crm.customers.store'), [
            'name' => 'Jane Doe', 'phone' => '+1 (805) 555-0100', 'state' => 'CA', 'city' => 'Moorpark',
        ])->assertRedirect();

        $customer = Customer::firstWhere('name', 'Jane Doe');
        $this->assertSame('8055550100', $customer->phone_digits);

        $this->put(route('crm.customers.update', $customer), [
            'name' => 'Jane Smith', 'phone' => '805.555.0199', 'state' => 'CA',
        ])->assertRedirect(route('crm.customers.show', $customer));

        $this->assertSame('8055550199', $customer->fresh()->phone_digits);
    }

    public function test_search_finds_customers_by_name_or_any_phone_format(): void
    {
        $this->signIn();
        Customer::factory()->create(['name' => 'Jane Doe', 'phone' => '(805) 555-0100']);
        Customer::factory()->create(['name' => 'Bob Roe', 'phone' => '(818) 222-3333']);

        foreach (['jane', '8055550100', '805-555-0100', '555 0100', '0100'] as $term) {
            $this->get(route('crm.customers.index', ['search' => $term]))
                ->assertInertia(fn ($page) => $page->has('customers.data', 1)->where('customers.data.0.name', 'Jane Doe'));
        }
    }

    public function test_appliances_can_be_added_and_edited(): void
    {
        $this->signIn();
        $customer = Customer::factory()->create();

        $this->post(route('crm.appliances.store', $customer), [
            'type' => 'Refrigerator', 'brand' => 'LG', 'model' => 'LRFVS3006S', 'serial' => '123ABC',
        ])->assertRedirect();

        $appliance = $customer->appliances()->first();
        $this->put(route('crm.appliances.update', $appliance), ['type' => 'Refrigerator', 'serial' => '999'])->assertRedirect();
        $this->assertSame('999', $appliance->fresh()->serial);

        $this->get(route('crm.customers.show', $customer))
            ->assertInertia(fn ($page) => $page->component('Crm/Customers/Show')->has('appliances', 1));
    }

    public function test_appliance_form_offers_types_and_known_brands(): void
    {
        $this->signIn();
        $customer = Customer::factory()->create();
        Appliance::factory()->create(['brand' => 'Fisher & Paykel']);
        Appliance::factory()->create(['brand' => 'samsung']);

        $this->get(route('crm.customers.show', $customer))->assertInertia(fn ($page) => $page
            ->where('applianceTypes', config('crm.appliance_types'))
            ->where('applianceBrands', fn ($brands) => collect($brands)->contains('Fisher & Paykel')
                && collect($brands)->filter(fn ($brand) => strtolower($brand) === 'samsung')->count() === 1));

        // "Other…" types are free text.
        $this->post(route('crm.appliances.store', $customer), ['type' => 'Washer/dryer combo'])->assertSessionHasNoErrors();
    }

    public function test_website_request_types_are_in_the_appliance_list(): void
    {
        foreach (config('repair.services') as $service) {
            $this->assertContains(trim(str_replace('Repair', '', $service['name'])), config('crm.appliance_types'));
        }
    }

    public function test_contact_fields_are_normalized_on_save(): void
    {
        $this->signIn();

        $this->post(route('crm.customers.store'), [
            'name' => '  Jane   Doe ', 'phone' => '805.555.0100', 'email' => ' Jane@Example.COM ', 'state' => 'ca', 'city' => ' Moorpark ',
        ]);
        $customer = Customer::sole();
        $this->assertSame(['Jane Doe', '(805) 555-0100', 'jane@example.com', 'CA', 'Moorpark'],
            [$customer->name, $customer->phone, $customer->email, $customer->state, $customer->city]);

        $this->post(route('crm.appliances.store', $customer), ['type' => 'Washer', 'model' => ' wtw4950xw2 ', 'serial' => 'c12345']);
        $this->assertSame(['WTW4950XW2', 'C12345'], [$customer->appliances()->sole()->model, $customer->appliances()->sole()->serial]);
    }

    public function test_customers_are_soft_deleted(): void
    {
        $customer = Customer::factory()->has(Appliance::factory())->create();
        $customer->delete();

        $this->assertSoftDeleted($customer);
    }

    public function test_validation_rejects_bad_input(): void
    {
        $this->signIn();

        $this->post(route('crm.customers.store'), ['name' => '', 'phone' => 'call me', 'state' => 'California'])
            ->assertSessionHasErrors(['name', 'phone', 'state']);
    }
}
