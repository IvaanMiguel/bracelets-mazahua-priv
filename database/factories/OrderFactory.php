<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\DeliveryApp;
use App\Models\DeliveryType;
use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $deliveryType = DeliveryType::inRandomOrder()->first();
        $paymentType = PaymentType::inRandomOrder()->first();
        $customer = Customer::inRandomOrder()->first();

        return [
            'customer_id' => $customer->id,
            'payment_type_id' => $paymentType->id,
            'details' => $this->getDetails($paymentType),

            // These are filled when order_product is populated.
            'total' => 0,
            'advance' => 0,
            'products_total' => 0,

            'delivery_id' => Delivery::factory(1)
                ->create([
                    'delivery_type_id' => $deliveryType->id,
                    'delivery_app_id' => $deliveryType->name === "Aplicación"
                        ? DeliveryApp::inRandomOrder()->first()->id
                        : null,
                    'address_id' => $deliveryType->name !== 'Pick up'
                        ? Address::where('customer_id', $customer->id)->inRandomOrder()->first()->id
                        : null
                ])
                ->first()
                ->id,
            'completed' => fake()->boolean()
        ];
    }

    private function getDetails(PaymentType $paymentType): ?string
    {
        switch ($paymentType->name) {
            case 'Depósito':
                return fake()->numerify("Cuenta que termina en ####.");

            case 'Tarjeta':
                return fake()->numerify("Tarjeta que termina en ####.");

            default:
                return null;
        }
    }
}
