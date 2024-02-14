<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'main_street' => fake()->streetName(),
            'cross_streets' => $this->createCrossStreets(),
            'neighborhood' => fake()->streetName(),
            'street_number' => fake()->optional()->buildingNumber(),
            'suite_number' => fake()->optional()->buildingNumber(),
            'postal_code' => fake()->postcode(),
            'customer_id' => Customer::factory()
        ];
    }

    private function createCrossStreets(): ?string
    {
        $street1 = fake()->streetName();
        $street2 = fake()->streetName();

        return fake()->boolean() ? "{$street1} y {$street2}" : null;
    }
}
