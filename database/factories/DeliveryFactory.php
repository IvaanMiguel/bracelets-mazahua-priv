<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\DeliveryApp;
use App\Models\DeliveryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryFactory extends Factory
{
    public function definition(): array
    {
        $deliveryType = DeliveryType::inRandomOrder()->first();

        return [
            'delivery_type_id' => $deliveryType->id,
            'delivery_app_id' => $deliveryType->name === "Aplicación"
                ? DeliveryApp::inRandomOrder()->first()->id
                : null,
            'date' => fake()->dateTimeBetween('-1 year'),
            'time' => fake()->time(),
            'address_id' => $deliveryType->name !== 'Pick up'
                ? Address::inRandomOrder()->first()->id
                : null
        ];
    }
}
