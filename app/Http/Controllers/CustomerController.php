<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $results = $request->input('results', 15);
        $search = $request->input('search');

        return Inertia::render('Customers/Index', [
            'customers' => Customer::where(DB::raw('CONCAT(name, " ", last_name)'), 'like', "{$search}%")
                ->orderBy('name', 'asc')
                ->orderBy('last_name', 'asc')
                ->paginate($results, ['id', 'name', 'last_name'])
                ->withQueryString(),
            'filters' => [
                'results' => intval($results),
                'search' => $search
            ]
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->all());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Customers/Show', [
            'customer' => Customer::find($id)
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
