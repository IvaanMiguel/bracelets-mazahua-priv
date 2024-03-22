<?php

namespace Database\Seeders;

use App\Models\DeliveryType;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DeliveryTypeSeeder extends Seeder
{
    private $deliveryTypes = [
        'Pick up',
        'Domicilio',
        'Aplicación'
    ];

    public function run(): void
    {
        DeliveryType::factory(count($this->deliveryTypes))
            ->sequence(fn (Sequence $sequence) => ['name' => $this->deliveryTypes[$sequence->index]])
            ->create();
    }
}
