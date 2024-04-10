<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'delivery_type_id' => null,
            'delivery_app_id' => null,
            'date' => fake()->dateTimeBetween('-1 year'),
            'time' => fake()->time(),
            'address_id' => null
        ];
    }
}
