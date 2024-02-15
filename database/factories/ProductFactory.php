<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    static $products = [
        'de cerezas',
        'de flores',
        'con ojo turco',
        'de cadena',
        'de acero'
    ];

    private $numOfProducts = 0;

    public function configure(): static
    {

        return $this->afterMaking(function (Product $product) {
            $product->name = $product->category->name . ' ' . self::$products[$this->numOfProducts % count(self::$products)];
            $this->numOfProducts = $this->numOfProducts + 1;
        });
    }

    public function definition(): array
    {
        return [
            'name' => null,
            'category_id' => Category::factory(),
            'price' => fake()->numberBetween(5, 150),
            'stock' => fake()->numberBetween(0, 20)
        ];
    }
}
