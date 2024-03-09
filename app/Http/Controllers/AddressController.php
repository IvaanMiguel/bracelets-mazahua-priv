<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(StoreAddressRequest $request)
    {
        Address::create($request->all());

        return back();
    }

    public function update(UpdateAddressRequest $request, string $id)
    {
        try {
            $address = Address::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => 'Ha ocurrido un error el editar la dirección seleccionada.'
            ]);
        }

        $address->update($request->all());

        return back();
    }

    public function destroy(string $id)
    {
        try {
            $address = Address::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'internal_error' => 'Ha ocurrido un error al eliminar la dirección seleccionada.'
            ]);
        }

        if (Address::where('customer_id', '=', $address->customer_id)->count() <= 1) {
            return back()->withErrors([
                'internal_error' => 'No es posible eliminar la única dirección del cliente.'
            ]);
        }

        $address->delete();

        return back();
    }
}
