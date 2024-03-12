<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'birth_date.before_or_equal' => 'La fecha de nacimiento debe ser anterior o igual a hoy.'
        ];
    }

    public function rules(): array
    {
        $addressRules = [
            'main_street' => [
                Rule::requiredIf(count($this->addresses) === 0),
                'required_with:cross_streets,neighborhood,postal_code,street_number,suite_number',
                'bail',
                'nullable',
                'string',
                'between:3,255'
            ],
            'cross_streets' => ['bail', 'nullable', 'string', 'between:3,255'],
            'neighborhood' => [
                Rule::requiredIf(count($this->addresses) === 0),
                'required_with:main_street,cross_streets,postal_code,street_number,suite_number',
                'bail',
                'nullable',
                'string',
                'between:3,255'
            ],
            'postal_code' => [
                Rule::requiredIf(count($this->addresses) === 0),
                'required_with:main_street,cross_streets,neighborhood,street_number,suite_number',
                'bail',
                'nullable',
                'string',
                'numeric',
                'integer',
                'gte:0',
                'digits:5'
            ],
            'street_number' => ['bail', 'nullable', 'string', 'numeric', 'integer', 'gte:0'],
            'suite_number' => ['bail', 'nullable', 'string', 'numeric', 'integer', 'gte:0'],
        ];

        return [
            'name' => ['bail', 'required', 'string', 'not_regex:/\d/', 'between:3,100'],
            'last_name' => ['bail', 'required', 'string', 'not_regex:/\d/', 'between:3,100'],
            'birth_date' => ['bail', 'nullable', 'date', 'before_or_equal:today'],
            'email' => ['bail', 'email', 'max:255', 'unique:customers,email', 'nullable'],
            'phone_number' => ['bail', 'required', 'string', 'numeric', 'digits:10', 'unique:customers,phone_number'],
            ...$addressRules,
            'addresses.*.main_street' => $addressRules['main_street'],
            'addresses.*.cross_streets' => $addressRules['cross_streets'],
            'addresses.*.neighborhood' => $addressRules['neighborhood'],
            'addresses.*.postal_code' => $addressRules['postal_code'],
            'addresses.*.street_number' => $addressRules['street_number'],
            'addresses.*.suite_number' => $addressRules['suite_number']
        ];
    }
}
