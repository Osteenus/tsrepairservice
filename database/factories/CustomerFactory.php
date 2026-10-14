<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => '(805) 555-'.fake()->unique()->numerify('####'),
            'email' => fake()->safeEmail(),
            'address_line1' => fake()->streetAddress(),
            'city' => 'Moorpark',
            'state' => 'CA',
            'zip' => '93021',
        ];
    }
}
