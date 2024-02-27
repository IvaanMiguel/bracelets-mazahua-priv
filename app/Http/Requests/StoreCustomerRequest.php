<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'not_regex:/\d/', 'between:3,100'],
            'last_name' => ['bail', 'required', 'string', 'not_regex:/\d/', 'between:3,100'],
            'birth_date' => ['bail', 'date', 'nullable'],
            'email' => ['bail', 'email', 'max:255', 'unique:customers,email', 'nullable'],
            'phone_number' => ['bail', 'required', 'string', 'numeric', 'digits:10', 'unique:customers,phone_number']
        ];
    }
}
