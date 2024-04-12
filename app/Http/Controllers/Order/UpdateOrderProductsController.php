<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Inertia\Inertia;

class UpdateOrderProductsController extends Controller
{
    public function edit(Order $order) {
        return Inertia::render('Orders/EditProducts', [
            'order' => $order,
            'orderProducts' => $order->products,
            'customer' => $order->customer()->select('name', 'last_name')->first()
        ]);
    }

    public function update() {

    }
}
