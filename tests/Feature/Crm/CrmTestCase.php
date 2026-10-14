<?php

namespace Tests\Feature\Crm;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class CrmTestCase extends TestCase
{
    use RefreshDatabase;

    protected function crmUser(): User
    {
        return User::factory()->create([
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code-one'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    protected function signIn(): User
    {
        $this->actingAs($user = $this->crmUser());

        return $user;
    }
}
