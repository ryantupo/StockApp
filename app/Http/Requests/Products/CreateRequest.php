<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')],
            'quantity' => ['required', 'integer', 'min:0'],
            'reorder_threshold' => ['required', 'integer', 'min:0'],
        ];
    }
}
