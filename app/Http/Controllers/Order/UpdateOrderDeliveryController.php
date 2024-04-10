<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderDeliveryRequest;
use App\Models\Order;

class UpdateOrderDeliveryController extends Controller
{
    public function update(UpdateOrderDeliveryRequest $request, Order $order)
    {
        $attributes = [
            'delivery_type_id' => $request->delivery_type_id,
            'date' => $request->date,
            'time' => $request->time,
            'address_id' => null,
            'delivery_app_id' => null
        ];

        if ($request->delivery_type_id !== 1) {
            $attributes['address_id'] = $request->address_id;
        }
        if ($request->delivery_type_id === 3) {
            $attributes['delivery_app_id'] = $request->delivery_app_id;
        }

        $order->delivery()->update($attributes);

        return back();
    }
}
