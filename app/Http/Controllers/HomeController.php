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
                ->orderBy('units', 'desc')
                ->first(),
            'topCustomer' => Order::selectRaw('orders.customer_id, CONCAT(customers.name, " ", customers.last_name) AS name, SUM(orders.total) AS total')
                ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
                ->where('orders.completed', '=', true)
                ->groupBy('customers.id')
                ->orderBy('total', 'desc')
                ->first(),
            'popularPaymentType' => Order::selectRaw('orders.payment_type_id, payment_types.name')
                ->leftJoin('payment_types', 'payment_types.id', '=', 'orders.payment_type_id')
                ->groupBy('payment_types.id')
                ->orderBy(DB::raw('COUNT(orders.payment_type_id)'), 'desc')
                ->first(),
            'totalSales' => Order::selectRaw('SUM(total) as total_sales')
                ->where('completed', '=', true)
                ->first(),
            'customersOrders' => Order::selectRaw('orders.customer_id,
                CONCAT(customers.name, " ", customers.last_name) AS name,
                COUNT(orders.customer_id) AS total_orders,
                COALESCE(SUM(CASE WHEN orders.completed = true THEN 1 ELSE 0 END), 0) AS completed_orders')
                ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
                ->groupBy('customers.id')
                ->orderBy('total_orders', 'desc')
                ->orderBy('completed_orders', 'desc')
                ->limit(5)
                ->get()
        ]);
    }
}
