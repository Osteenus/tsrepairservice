<?php

namespace Tests\Feature;

use App\Mail\WebFormRequestReceivedEmail;
use App\Models\Contact;
use App\Models\RepairRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ServiceRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Mail::fake();
    }

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'name' => 'Test Customer', 'phone' => '+1 (805) 555-0123', 'email' => '',
            'location' => 'Moorpark', 'serviceId' => 2, 'brand' => 'GE', 'model' => 'TEST-123',
            'description' => 'The washer will not drain.', 'website' => '',
        ], $overrides);
    }

    public function test_contact_and_allowed_preselections(): void
    {
        $this->get('/contact')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Contact')->where('selectedServiceId', null)->has('repair.services', 9));
        foreach (config('repair.services') as $service) {
            $this->get('/contact?service='.$service['slug'])->assertOk()->assertInertia(fn (Assert $page) => $page
                ->where('selectedServiceId', $service['id']));
        }
        foreach (['unknown', '<script>alert(1)</script>', ['washer']] as $value) {
            $this->get('/contact?'.http_build_query(['service' => $value]))->assertOk()
                ->assertInertia(fn (Assert $page) => $page->where('selectedServiceId', null));
        }
    }

    public function test_required_fields_and_inertia_error_sharing(): void
    {
        $this->from('/contact')->post('/contact', [])->assertRedirect('/contact')
            ->assertSessionHasErrors(['name', 'phone', 'location', 'serviceId', 'description']);
        $this->get('/contact')->assertInertia(fn (Assert $page) => $page
            ->has('errors.name')->has('errors.phone')->has('errors.location')->has('errors.serviceId')->has('errors.description'));
        Mail::assertNothingSent();
        $this->assertDatabaseCount('repair_requests', 0);
    }

    public function test_invalid_email_and_appliance_are_rejected(): void
    {
        $this->from('/contact')->post('/contact', $this->payload(['email' => 'invalid', 'serviceId' => 999]))
            ->assertSessionHasErrors(['email', 'serviceId']);
        $this->post('/contact', $this->payload(['serviceId' => 6]))->assertSessionHasErrors('serviceId');
        $this->post('/contact', $this->payload(['phone' => 'abc']))->assertSessionHasErrors('phone');
        $this->post('/contact', $this->payload(['name' => ['invalid'], 'location' => ['invalid']]))->assertSessionHasErrors(['name', 'location']);
        Mail::assertNothingSent();
    }

    public function test_city_and_zip_requests_are_stored_with_all_details_and_fake_mail(): void
    {
        foreach (['Moorpark', '93021', '93021-1234'] as $location) {
            $this->from('/contact')->post('/contact', $this->payload(['location' => $location, 'name' => '  Test Customer  ']))
                ->assertRedirect('/contact')->assertSessionHasNoErrors()->assertSessionHas('requestReceived', true);
            $this->assertDatabaseHas('repair_requests', ['location' => $location, 'brand' => 'GE', 'model' => 'TEST-123', 'service_id' => 2]);
        }
        $this->assertDatabaseHas('contacts', ['name' => 'Test Customer', 'email' => '']);
        Mail::assertSent(WebFormRequestReceivedEmail::class, 3);
        Mail::assertSent(WebFormRequestReceivedEmail::class, function ($mail) {
            $html = $mail->render();

            return $mail->hasTo(config('repair.request_recipient')) && str_contains($html, 'Washer Repair')
                && str_contains($html, 'TEST-123') && str_contains($html, '93021');
        });
        $this->get('/contact')->assertInertia(fn (Assert $page) => $page->where('flash.requestReceived', true));
        $this->get('/contact')->assertInertia(fn (Assert $page) => $page->where('flash.requestReceived', false));
        $this->assertDatabaseCount('repair_requests', 3);
    }

    public function test_optional_details_and_long_description(): void
    {
        $this->post('/contact', $this->payload(['brand' => null, 'model' => null, 'description' => str_repeat('Symptoms. ', 100)]))
            ->assertSessionHasNoErrors();
        $this->assertDatabaseCount('repair_requests', 1);
        $this->post('/contact', $this->payload(['description' => str_repeat('a', 5001)]))->assertSessionHasErrors('description');
    }

    public function test_honeypot_blocks_mail_and_storage(): void
    {
        $this->post('/contact', $this->payload(['website' => 'https://spam.example']))->assertSessionHasErrors('website');
        Mail::assertNothingSent();
        $this->assertDatabaseCount('contacts', 0);
        $this->assertDatabaseCount('repair_requests', 0);
    }

    public function test_rate_limit_returns_an_inertia_compatible_error(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->from('/contact')->post('/contact', [])->assertSessionHasErrors('name');
        }
        $this->from('/contact')->post('/contact', $this->payload())->assertRedirect('/contact')
            ->assertSessionHasErrors('submission')->assertHeader('Retry-After');
        Mail::assertNothingSent();
        $this->assertDatabaseCount('repair_requests', 0);
    }

    public function test_mail_failure_does_not_confirm_or_leave_partial_records(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('Simulated transport failure'));
        $this->from('/contact')->post('/contact', $this->payload())->assertRedirect('/contact')
            ->assertSessionHasErrors('submission')->assertSessionMissing('requestReceived');
        $this->assertDatabaseCount('contacts', 0);
        $this->assertDatabaseCount('repair_requests', 0);
    }

    public function test_email_template_escapes_user_content(): void
    {
        $mail = new WebFormRequestReceivedEmail(
            new RepairRequest(['service_id' => 2, 'description' => '<script>alert(1)</script>', 'location' => '<b>city</b>']),
            new Contact(['name' => '<img src=x onerror=alert(1)>', 'phone' => '8055550123', 'email' => ''])
        );
        $html = $mail->render();
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringNotContainsString('<img src=x', $html);
    }

    public function test_csrf_protection_is_active(): void
    {
        // Laravel bypasses CSRF in testing; switch only this check to its real middleware path.
        $this->app['env'] = 'local';
        $this->post('/contact', $this->payload())->assertStatus(419);
        Mail::assertNothingSent();
    }

    public function test_legacy_routes_and_service_ctas(): void
    {
        $this->get('/services/washer-repair')->assertRedirect('/washer-repair-moorpark');
        $this->get('/services/oven-repair')->assertRedirect('/oven-stove-repair-moorpark');
        $this->get('/services/trash-compactor-repair')->assertRedirect('/trash-compactor-repair-moorpark');
        foreach (config('repair.services') as $service) {
            $this->get($service['url'])->assertOk();
        }
        $this->get('/send-request-received-email')->assertNotFound();
        $this->assertStringNotContainsString('BOOK ONLINE', file_get_contents(resource_path('js/Components/Custom/MainSection.vue')));
        foreach (glob(resource_path('js/Pages/Services/*RepairService.vue')) as $file) {
            $this->assertStringNotContainsString('Book Service', file_get_contents($file));
            $this->assertStringContainsString('/contact?service=', file_get_contents($file));
        }
        Mail::assertNothingSent();
    }
}
