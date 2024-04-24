<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Home/Index', [
            'completedOrders' => Order::select([
                DB::raw('CASE WHEN completed = true THEN "Completados" ELSE "Sin completar" END AS "name"'),
                DB::raw('COUNT(completed) AS total'),
            ])
                ->orderBy('name')
                ->groupBy('completed')
                ->get(),
            'salesPerCategory' => DB::table('order_product')
                ->selectRaw('categories.id, categories.name, SUM(order_product.subtotal) AS total_sales')
                ->leftJoin('orders', 'orders.id', '=', 'order_product.order_id')
                ->leftJoin('products', 'products.id', '=', 'order_product.product_id')
                ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                ->where('orders.completed', '=', true)
                ->groupBy('categories.id')
                ->orderBy('categories.name')
                ->get(),
            'bestSeller' => DB::table('order_product')
                ->selectRaw('order_product.product_id, products.name, SUM(order_product.subtotal) AS total_sales, SUM(order_product.amount) as units')
                ->leftJoin('products', 'products.id', '=', 'order_product.product_id')
                ->leftJoin('orders', 'orders.id', '=', 'order_product.order_id')
                ->where('orders.completed', '=', true)
                ->groupBy('order_product.product_id')
                ->orderBy('total_sales', 'desc')
                ->first()
        ]);
    }
}
