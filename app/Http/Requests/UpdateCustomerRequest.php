<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'name' => ['bail', 'required', 'string', 'not_regex:/\d/', 'between:3,100'],
            'last_name' => ['bail', 'required', 'string', 'not_regex:/\d/', 'between:3,100'],
            'birth_date' => ['bail', 'nullable', 'date', 'before_or_equal:today'],
            'email' => ['bail', 'email', 'max:255', "unique:customers,email,{$id}", 'nullable'],
            'phone_number' => ['bail', 'required', 'string', 'numeric', 'digits:10', "unique:customers,phone_number,{$id}"],
        ];
    }
}
