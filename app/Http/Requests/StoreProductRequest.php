<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'between:3,255', 'unique:products,name'],
            'category_id' => ['bail', 'required', 'numeric', 'exists:categories,id'],
            'price' => ['bail', 'required', 'decimal:0,2', 'between:1,9999.99'],
            'stock' => ['bail', 'nullable', 'numeric', 'integer', 'between:0,999']
        ];
    }
}
