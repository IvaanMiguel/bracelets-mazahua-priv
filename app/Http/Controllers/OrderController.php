<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
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
                'completed',
                'total',
                'customer_id',
                'delivery_id',
                'payment_type_id',
            ])->with([
                'customer:id,name,last_name',
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
            'filters' => [
                'search' => $search,
                'results' => $results
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
