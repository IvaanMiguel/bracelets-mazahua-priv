<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => 'Ha ocurrido un error al editar el producto seleccionado.'
            ]);
        }

        $product->update($request->all());

        return back();
    }

    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => 'Ha ocurrido un error al eliminar el producto seleccionado.'
            ]);
        }

        $product->delete();

        return back();
    }
}
