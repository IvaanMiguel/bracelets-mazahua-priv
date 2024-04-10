<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'date' => 'fecha de entrega',
            'time' => 'hora de entrega',
            'address_id' => 'dirección de entrega'
        ];
    }

    public function messages(): array
    {
        return [
            'delivery_app_id.exists' => 'La :attribute seleccionada es inválida.',
            'address_id.exists' => 'La :attribute seleccionada es inválida'
        ];
    }

    public function rules(): array
    {
        return [
            'delivery_type_id' => ['bail', 'required', 'numeric', 'exists:delivery_types,id'],
            'delivery_app_id' => ['bail', 'required', 'numeric', 'exists:delivery_apps,id'],
            'date' => ['bail', 'required', 'date'],
            'time' => ['bail', 'required', 'string'],
            'address_id' => ['bail', 'required', 'numeric', 'exists:addresses,id']
        ];
    }
}
