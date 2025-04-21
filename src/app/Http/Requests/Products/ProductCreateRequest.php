<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'market_id'     => ['required', 'exists:markets,id'],
            'reference'     => ['required', 'string', 'unique:products,reference'],
            'stock'         => ['nullable', 'numeric'],
            'name'          => ['required', 'string', 'max:255'],
            'brand'         => ['nullable', 'string', 'max:255'],
            'cost_price'    => ['nullable', 'numeric'],
            'sale_price'    => ['nullable', 'numeric'],
            'is_active'     => ['boolean'],
            'description'   => ['nullable', 'string'],
            'category_ids'   => ['sometimes', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ];
    }
}
