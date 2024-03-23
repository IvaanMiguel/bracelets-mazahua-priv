<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Order::get() as $order) {
            $n = fake()->numberBetween(1, 3);
            $products = Product::inRandomOrder()
                ->limit($n)
                ->get();

            $productsTotal = 0;
            $total = 0;

            foreach ($products as $product) {
                $amount = fake()->boolean() ? 1 : 2;
                $productsTotal += $amount;
                $subtotal = $product->price * $amount;
                $total += $subtotal;
                
                DB::table('orders_products')->insert([
                    'id' => 0,
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'amount' => $amount,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null
                ]);
            }

            $order->update([
                'total' => $total,
                'advance' => $total / 2,
                'products_total' => $productsTotal
            ]);
        }
    }
}
