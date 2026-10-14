<?php

namespace Tests\Feature\Crm;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Features;

class AccessTest extends CrmTestCase
{
    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/crm')->assertRedirect('/login');
        $this->get('/crm/customers')->assertRedirect('/login');
    }

    public function test_users_without_confirmed_two_factor_must_set_it_up_first(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/crm')->assertRedirect(route('profile.show'));
        $this->get('/crm/customers')->assertRedirect(route('profile.show'));
        $this->get(route('profile.show'))->assertOk();
    }

    public function test_enabled_but_unconfirmed_two_factor_is_not_enough(): void
    {
        $this->actingAs(User::factory()->create(['two_factor_secret' => encrypt('secret')]));

        $this->get('/crm')->assertRedirect(route('profile.show'));
    }

    public function test_users_with_two_factor_can_open_the_crm(): void
    {
        $this->signIn();

        $this->get('/crm')->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn ($page) => $page->component('Crm/Dashboard'));
    }

    public function test_login_with_two_factor_goes_to_the_challenge(): void
    {
        $user = $this->crmUser();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/two-factor-challenge');
        $this->assertGuest();
    }

    public function test_public_registration_is_disabled(): void
    {
        $this->assertFalse(Features::enabled(Features::registration()));
        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'Intruder', 'email' => 'x@example.com', 'password' => 'Password12345', 'password_confirmation' => 'Password12345',
        ])->assertNotFound();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_login_and_account_pages_are_noindex(): void
    {
        $this->get('/login')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_robots_txt_and_sitemap_exclude_the_crm(): void
    {
        $this->assertStringContainsString('Disallow: /crm', file_get_contents(public_path('robots.txt')));
        $this->get('/sitemap.xml')->assertDontSee('/crm');
    }

    public function test_login_is_rate_limited(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertStatus(429);
        $this->assertGuest();
    }

    public function test_crm_user_command_creates_a_user_with_a_strong_password(): void
    {
        $this->artisan('crm:user', ['email' => 'owner@example.com', '--name' => 'Owner'])
            ->expectsQuestion('Password', 'short')
            ->expectsQuestion('Repeat password', 'short')
            ->assertFailed();
        $this->assertDatabaseCount('users', 0);

        $this->artisan('crm:user', ['email' => 'owner@example.com', '--name' => 'Owner'])
            ->expectsQuestion('Password', 'correct horse 42 battery')
            ->expectsQuestion('Repeat password', 'correct horse 42 battery')
            ->assertSuccessful();

        $this->assertTrue(Hash::check('correct horse 42 battery', User::firstWhere('email', 'owner@example.com')->password));
    }
}
