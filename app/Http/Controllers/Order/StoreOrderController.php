<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\DeliveryApp;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StoreOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $search = [
            'customer' => $request->input('search-customer', ''),
            'product' => $request->input('search-product', '')
        ];
        $results = [
            'customer' => intval($request->input('results-customer', 10)),
            'product' => intval($request->input('results-product', 10)),
        ];

        return Inertia::render('Orders/Create', [
            'customers' => Customer::where(DB::raw('CONCAT(name, " ", last_name)'), 'like', "%{$search['customer']}%")
                ->orderBy('name')
                ->paginate($results['customer'], ['id', 'name', 'last_name'], 'customers')
                ->withQueryString(),
            'products' => Product::where('name', 'like', "%{$search['product']}%")
                ->where('stock', '>', 0)
                ->orderBy('name')
                ->paginate($results['product'], ['id', 'name', 'price', 'stock'], 'products')
                ->withQueryString(),
            'deliveryTypes' => fn () => DeliveryType::orderBy('name')->get(),
            'deliveryApps' => fn () => DeliveryApp::orderBy('name')->get(),
            'paymentTypes' => fn () => PaymentType::orderBy('name')->get(),
            'filters' => [
                'search' => $search,
                'results' => $results
            ]
        ]);
    }

    public function store(Request $request)
    {
        $productIds = array_map(fn ($product) => $product['id'], $request->products);
        $products = Product::select(['id', 'price'])->whereIn('id', $productIds)->get();

        $mergedProducts = [];

        foreach ($request->products as $product) {
            $mergedProducts[$product['id']]['amount'] = $product['amount'];
        }

        foreach ($products as $product) {
            $mergedProducts[$product['id']]['price'] = $product['price'];
        }

        $productsTotal = 0;
        $total = 0;

        foreach ($mergedProducts as $product) {
            $productsTotal += $product['amount'];
            $total += ($product['price'] * $product['amount']);
        }

        DB::transaction(function () use ($request, $total, $productsTotal, $mergedProducts) {
            foreach ($request->products as $product) {
                DB::table('products')
                    ->where('id', $product['id'])
                    ->decrement('stock', $product['amount']);
            }

            $delivery = Delivery::create([
                'delivery_type_id' => $request->delivery_type_id,
                'delivery_app_id' => $request->delivery_type_id === 3 ? $request->delivery_app_id : null,
                'date' => $request->date,
                'time' => $request->time,
                'address_id' => $request->delivery_type_id !== 1 ? $request->address_id : null
            ]);

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'payment_type_id' => $request->payment_type_id,
                'details' => $request->details,
                'total' => $total,
                'advance' => $total / 2,
                'products_total' => $productsTotal,
                'delivery_id' => $delivery->id,
                'completed' => false
            ]);

            foreach ($mergedProducts as $id => $product) {
                $order->products()->attach($id, [
                    'amount' => $product['amount'],
                    'subtotal' => $product['amount'] * $product['price'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });

        return to_route('orders.create');
    }
}
