<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private $categories = [
        'Pulseras',
        'Collares',
        'Anillos',
        'Pendientes',
        'Broches',
        'Gargantillas',
        'Tobilleras',
        'Brazaletes'
    ];

    public function run(): void
    {
        Category::factory(count($this->categories))
            ->sequence(fn (Sequence $sequence) => ['name' => $this->categories[$sequence->index]])
            ->create();
    }
}
