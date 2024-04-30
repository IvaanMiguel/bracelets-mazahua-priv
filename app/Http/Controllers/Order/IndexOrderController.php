<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DeliveryApp;
use App\Models\DeliveryType;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class IndexOrderController extends Controller
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
                DB::raw('CASE WHEN completed = 1 THEN "Completado" ELSE "Pendiente" END AS completed'),
                'total',
                'customer_id',
                'delivery_id',
                'payment_type_id',
            ])->with([
                'customer' => fn ($q) => $q->select(['id', 'name', 'last_name']),
                'delivery:id,date,delivery_type_id',
                'delivery.deliveryType',
                'paymentType'
            ])->join('deliveries', 'orders.delivery_id', '=', 'deliveries.id')
                ->orWhere(DB::raw('CASE WHEN completed = 1 THEN "Completado" ELSE "Pendiente" END'), 'like', "%{$search}%")
                ->orWhereRelation('customer', DB::raw('CONCAT(name, " ", last_name)'), 'like', "%{$search}%")
                ->orWhereRelation('delivery.deliveryType', 'name', 'like', "%{$search}%")
                ->orWhereRelation('paymentType', 'name', 'like', "%{$search}%")
                ->orWhereRelation('delivery', DB::raw('DATE_FORMAT(date, "%d/%b/%Y")'), 'like', "%{$search}%")
                ->orWhereRelation('delivery', DB::raw('DATE_FORMAT(date, "%e/%b/%Y")'), 'like', "%{$search}%")
                ->orWhereRelation('delivery', DB::raw('DAY(date)'), 'like', "%{$search}%")
                ->orWhereRelation('delivery', DB::raw('MONTHNAME(date)'), 'like', "%{$search}%")
                ->orWhereRelation('delivery', DB::raw('YEAR(date)'), 'like', "%{$search}%")
                ->orderBy('completed', 'desc')
                ->orderBy('deliveries.date', 'desc')
                ->paginate($results)
                ->withQueryString(),
            'filters' => [
                'results' => intval($results),
                'search' => $search
            ],
            'destroyed' => Session::get('destroyed')
        ]);
    }

    public function show(string $id)
    {
        try {
            $order  = Order::with([
                'customer' => fn (BelongsTo $q) => $q->select(['id', 'name', 'last_name', 'phone_number', 'email', 'deleted_at'])
                    ->with(['addresses' => fn (HasMany $q) => $q->withTrashed()])
                    ->withTrashed(),
                'paymentType',
                'delivery.deliveryType',
                'delivery.deliveryApp',
                'delivery.address',
                'products' => fn ($q) => $q->select(['name', 'price', 'products.deleted_at'])->withTrashed()
            ])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => $e->getMessage()
            ]);
        }

        return Inertia::render('Orders/Show', [
            'order' => $order,
            'deliveryTypes' => fn () => DeliveryType::orderBy('name')->get(),
            'deliveryApps' => fn () => DeliveryApp::orderBy('name')->get()
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return to_route('orders')->with('destroyed', true);
    }

    public function complete(Order $order)
    {
        $order->update(['completed' => true]);

        return back();
    }
}
