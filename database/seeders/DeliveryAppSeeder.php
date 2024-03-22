<?php

namespace Database\Seeders;

use App\Models\DeliveryApp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DeliveryAppSeeder extends Seeder
{
    private $deliveryApps = [
        'Uber',
        'Didi'
    ];

    public function run(): void
    {
        DeliveryApp::factory(count($this->deliveryApps))
            ->sequence(fn (Sequence $sequence) => ['name' => $this->deliveryApps[$sequence->index]])
            ->create();
    }
}
