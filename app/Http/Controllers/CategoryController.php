<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $results = $request->input('results', 15);
        $search = $request->input('search');

        return Inertia::render('Categories/Index', [
            'categories' => DB::table('view_categories_stock')
                ->where('name', 'like', "{$search}%")
                ->orderBy('name', 'asc')
                ->paginate($results)
                ->appends(['search' => $search, 'results' => $results]),
            'filters' => [
                'results' => intval($results),
                'search' => $search
            ],
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        //
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => 'Ha ocurrido un error al editar la categoría seleccionada.'
            ]);
        }

        $category->update($request->all());

        return back();
    }

    public function destroy(string $id)
    {
        //
    }
}
