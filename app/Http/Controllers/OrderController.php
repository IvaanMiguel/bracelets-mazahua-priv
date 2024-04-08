<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\DeliveryApp;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $results = $request->input('results', 10);
        $search = $request->input('search', '');

        // This is horrible.
        return Inertia::render('Orders/Index', [
            'orders' => Order::select([
                'orders.id',
                'completed',
                'total',
                'customer_id',
                'delivery_id',
                'payment_type_id',
            ])->with([
                'customer' => fn ($q) => $q->select(['id', 'name', 'last_name'])->withTrashed(),
                'delivery:id,date,delivery_type_id',
                'delivery.deliveryType',
                'paymentType'
            ])->join('deliveries', 'orders.delivery_id', '=', 'deliveries.id')
                ->orWhereRelation('customer', DB::raw('CONCAT(name, " ", last_name)'), 'like', "{$search}%")
                ->orWhereRelation('delivery.deliveryType', 'name', 'like', "{$search}%")
                ->orWhereRelation('paymentType', 'name', 'like', "{$search}%")
                ->orWhereRelation('delivery', DB::raw('DATE_FORMAT(date, "%d/%b/%Y")'), 'like', "{$search}%")
                ->orWhereRelation('delivery', DB::raw('DATE_FORMAT(date, "%e/%b/%Y")'), 'like', "{$search}%")
                ->orWhereRelation('delivery', DB::raw('DAY(date)'), 'like', "{$search}%")
                ->orWhereRelation('delivery', DB::raw('MONTHNAME(date)'), 'like', "{$search}%")
                ->orWhereRelation('delivery', DB::raw('YEAR(date)'), 'like', "{$search}%")
                ->orderBy('completed', 'asc')
                ->orderBy('deliveries.date', 'desc')
                ->paginate($results)
                ->withQueryString(),
            'filters' => [
                'results' => intval($results),
                'search' => $search
            ],
        ]);
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
            'customers' => Customer::where('name', 'like', "{$search['customer']}%")
                ->orderBy('name')
                ->paginate($results['customer'], ['id', 'name', 'last_name'], 'customers')
                ->withQueryString(),
            'products' => Product::where('name', 'like', "{$search['product']}%")
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


    public function show(string $id)
    {
        try {
            $order  = Order::with([
                'customer' => fn ($q) => $q->select(['id', 'name', 'last_name', 'phone_number', 'email'])->withTrashed(),
                'paymentType',
                'delivery.deliveryType',
                'delivery.deliveryApp',
                'delivery.address',
                'products' => fn ($q) => $q->select(['name', 'price'])->withTrashed()
            ])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => $e->getMessage()
            ]);
        }

        return Inertia::render('Orders/Show', [
            'order' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
