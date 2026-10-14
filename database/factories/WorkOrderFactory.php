<?php

namespace Database\Factories;

use App\Models\Appliance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'appliance_id' => Appliance::factory(),
            'customer_id' => fn (array $attributes) => Appliance::find($attributes['appliance_id'])->customer_id,
            'reported_problem' => 'Tub shakes and bounces during spin cycle.',
            'status' => 'new',
        ];
    }
}
