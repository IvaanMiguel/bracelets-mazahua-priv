<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $paymentType = PaymentType::inRandomOrder()->first();

        return [
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'payment_type_id' => $paymentType->id,
            'details' => $this->getDetails($paymentType),

            // These are filled when orders_products is populated.
            'total' => 0,
            'advance' => 0,
            'products_total' => 0,

            'delivery_id' => Delivery::factory(1)
                ->create()
                ->first()
                ->id,
            'paid' => fake()->boolean()
        ];
    }

    private function getDetails(PaymentType $paymentType): ?string
    {
        switch ($paymentType->name) {
            case 'Depósito':
                return fake()->numerify("Cuenta que termina en ##.");

            case 'Tarjeta':
                return fake()->numerify("Tarjeta que termina en ##.");
            
            default: return null;
        }
    }
}
