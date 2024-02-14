<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => fake()->unique()->uuid(),
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'birth_date' => $this->createBirthDate(),
            'email' => $this->createEmail(),
            'phone_number' => fake()->unique()->numerify('612#######')
        ];
    }

    private function createBirthDate(): ?DateTime
    {
        return fake()->boolean() ? fake()->dateTime(now()->subYears(16)) : null;
    }

    private function createEmail(): ?string
    {
        return fake()->boolean() ? fake()->unique()->safeEmail() : null;
    }
}
