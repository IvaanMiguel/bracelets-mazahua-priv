<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class UpdateOrderProductsController extends Controller
{
    public function edit(Order $order)
    {
        $results = Request::input('results', 10);
        $search = Request::input('search', '');

        return Inertia::render('Orders/EditProducts', [
            'order' => $order,
            'orderProducts' => $order->products,
            'customer' => $order->customer()->select('name', 'last_name')->first(),
            'products' => Product::select([
                'products.id',
                'products.name',
                'products.category_id',
                'products.price',
                DB::raw('COALESCE(products.stock + order_product.amount, products.stock) AS stock')
            ])
                ->leftJoin('order_product', function (JoinClause $join) use ($order) {
                    $join->on('order_product.product_id', '=', 'products.id')
                        ->where('order_product.order_id', '=', $order->id)
                        ->whereRaw('products.price * order_product.amount = order_product.subtotal');
                })
                ->where('products.name', 'like', "{$search}%")
                ->where('products.stock', '>', 0)
                ->orWhere('order_product.order_id', '=', $order->id)
                ->orderBy('products.name')
                ->paginate($results, ['products.id', 'products.name', 'products.price', 'products.stock'])
                ->withQueryString()
                ->appends(['order' => null]),
            'filters' => [
                'search' => $search,
                'results' => $results
            ]
        ]);
    }

    public function update(HttpRequest $request, Order $order)
    {
        $attach = [];
        $requestProductsAmount = [];
        $total = 0;
        $productsTotal = 0;

        foreach ($request->products as $product) {
            $subtotal = $product['amount'] * $product['price'];

            $attach[] = [
                'product_id' => $product['id'],
                'amount' => $product['amount'],
                'subtotal' => $subtotal,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $total += $subtotal;
            $productsTotal += $product['amount'];

            if (!isset($requestProductsAmount[$product['id']])) {
                $requestProductsAmount[$product['id']] = 0;
            }

            $requestProductsAmount[$product['id']] += $product['amount'];
        }

        $orderProductsAmount = [];

        foreach ($order->products as $product) {
            if (!isset($orderProductsAmount[$product->id])) {
                $orderProductsAmount[$product->id] = 0;
            }

            $orderProductsAmount[$product->id] += ($requestProductsAmount[$product->id] ?? 0) - $product->pivot->amount;
        }

        dd($orderProductsAmount);

        foreach ($request->products as $product) {
            if (!isset($orderProductsAmount[$product['id']])) {
                $orderProductsAmount[$product['id']] = $product['amount'];
            }
        }

        DB::transaction(function () use ($requestProductsAmount, $orderProductsAmount, $order, $total, $productsTotal, $attach) {
            foreach ($requestProductsAmount as $id => $_) {
                $decrement = ($orderProductsAmount[$id] ?? 0) - $requestProductsAmount[$id];

                DB::table('products')
                    ->where('id', $id)
                    ->decrement('stock', $decrement);
            }

            $order->update([
                'total' => $total,
                'advance' => $total / 2,
                'products_total' => $productsTotal
            ]);

            $order->products()->detach();
            $order->products()->attach($attach);
        });
    }
}
