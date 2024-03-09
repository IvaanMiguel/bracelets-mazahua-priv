<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'main_street' => ['bail', 'required', 'string', 'between:3,255'],
            'cross_streets' => ['bail', 'nullable', 'string', 'between:3,255'],
            'neighborhood' => ['bail', 'required', 'string', 'between:3,255'],
            'postal_code' => ['bail', 'required', 'string', 'numeric', 'gte:0', 'digits:5'],
            'street_number' => ['bail', 'nullable', 'string', 'numeric', 'integer', 'gte:0'],
            'suite_number' => ['bail', 'nullable', 'string', 'numeric', 'integer', 'gte:0']
        ];
    }
}
