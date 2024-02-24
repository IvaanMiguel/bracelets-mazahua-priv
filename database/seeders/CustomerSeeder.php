<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::factory(75)
            ->hasAddresses(3)
            ->create();

        Customer::factory(15)
            ->has(Address::factory(3)->trashed())
            ->trashed()
            ->create();
    }
}
