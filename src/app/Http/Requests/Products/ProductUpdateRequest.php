<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'market_id'     => ['sometimes', 'exists:markets,id'],
            'reference'     => ['sometimes', 'string', 'unique:products,reference,' . $this->product->id],
            'stock'         => ['nullable', 'numeric'],
            'name'          => ['sometimes', 'string', 'max:255'],
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
