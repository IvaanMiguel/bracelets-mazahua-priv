<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CustomerSeeder::class,
            CategorySeeder::class,
            DeliveryTypeSeeder::class,
            DeliveryAppSeeder::class,
            PaymentTypeSeeder::class,
            OrderSeeder::class,
            OrderProductSeeder::class
        ]);
    }
}
