<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Appliance>
 */
class ApplianceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'type' => 'Washer (top load)',
            'brand' => 'Whirlpool',
            'model' => 'WTW4950XW2',
            'serial' => strtoupper(fake()->bothify('??########')),
        ];
    }
}
