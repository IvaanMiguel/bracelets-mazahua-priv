<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Home/Index', [
            'completedOrders' => Order::select([
                DB::raw('CASE WHEN completed = true THEN "Completados" ELSE "Sin completar" END AS "name"'),
                DB::raw('COUNT(completed) AS total'),
            ])
                ->orderBy('name')
                ->groupBy('completed')
                ->get()
        ]);
    }
}
