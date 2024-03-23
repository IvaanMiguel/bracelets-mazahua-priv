<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    private $paymentTypes = [
        'Efectivo',
        'Depósito',
        'Tarjeta'
    ];

    public function run(): void
    {
        PaymentType::factory(count($this->paymentTypes))
            ->sequence(fn (Sequence $sequence) => ['name' => $this->paymentTypes[$sequence->index]])
            ->create();
    }
}
