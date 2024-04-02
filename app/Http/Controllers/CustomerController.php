<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $results = $request->input('results', 10);
        $search = $request->input('search', '');

        return Inertia::render('Customers/Index', [
            'customers' => fn () => Customer::where(DB::raw('CONCAT(name, " ", last_name)'), 'like', "{$search}%")
                ->orWhere('name', 'like', "{$search}%")
                ->orWhere('last_name', 'like', "{$search}%")
                ->orWhere('phone_number', 'like', "{$search}%")
                ->orderBy('name', 'asc')
                ->orderBy('last_name', 'asc')
                ->paginate($results, ['id', 'name', 'last_name', 'phone_number'])
                ->withQueryString(),
            'filters' => [
                'results' => intval($results),
                'search' => $search
            ],
            'destroyed' => Session::get('destroyed')
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        $request['customer_id'] = $customer->id;

        if ($request->filled(['main_street', 'neighborhood', 'postal_code'])) {
            Address::create($request->only([
                'main_street',
                'cross_streets',
                'neighborhood',
                'postal_code',
                'street_number',
                'suite_number',
                'customer_id'
            ]));
        }

        if (count($request->addresses)) {
            $addresses = array_map(function ($item) use ($customer) {
                $item['customer_id'] = $customer->id;
                $item['id'] = 0;

                return $item;
            }, $request->addresses);

            Address::insert($addresses);
        }

        return back();
    }

    public function show(string $id)
    {
        try {
            $customer = Customer::with('addresses')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => 'No se ha podido encontrar el cliente solicitado.'
            ]);
        }

        return Inertia::render('Customers/Show', [
            'customer' => $customer,
            'current_date' => now()
        ]);
    }

    public function update(UpdateCustomerRequest $request, string $id)
    {
        try {
            $customer = Customer::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => 'Ha ocurrido un error el editar el cliente seleccionado.'
            ]);
        }

        $customer->update($request->all());

        return back();
    }

    public function destroy(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => 'Ha ocurrido un error al eliminar el cliente seleccionado.'
            ]);
        }

        $customer->delete();

        return to_route('customers')->with('destroyed', true);
    }

    public function addresses(string $id)
    {
        $addresses = Address::where('customer_id', $id)
            ->orderBy('neighborhood')
            ->get();

        return $addresses;
    }
}
