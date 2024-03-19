<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $results = $request->input('results', 10);
        $search = $request->input('search');

        return Inertia::render('Products/Index', [
            'products' => Product::with('category')
                ->where('name', 'like', "{$search}%")
                ->orderBy('name', 'asc')
                ->paginate($results)
                ->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters' => [
                'results' => intval($results),
                'search' => $search
            ]
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->all());

        return back();
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
