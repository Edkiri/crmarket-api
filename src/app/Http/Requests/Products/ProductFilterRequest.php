<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'market_id'        => 'nullable|integer|exists:markets,id',
            'reference'        => 'nullable|string|max:255',
            'name'             => 'nullable|string|max:255',
            'brand'            => 'nullable|string|max:255',
            'is_active'        => 'nullable|boolean',
            'min_cost_price'   => 'nullable|numeric|min:0',
            'max_cost_price'   => 'nullable|numeric|min:0',
            'min_sale_price'   => 'nullable|numeric|min:0',
            'max_sale_price'   => 'nullable|numeric|min:0',
            'category_ids'     => 'nullable|array',
            'category_ids.*'   => 'integer|exists:categories,id',
        ];
    }
}
