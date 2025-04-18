<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'market_id'     => $this->market_id,
            'reference'     => $this->reference,
            'stock'         => $this->stock,
            'name'          => $this->name,
            'brand'         => $this->brand,
            'cost_price'    => $this->cost_price,
            'sale_price'    => $this->sale_price,
            'is_active'     => $this->is_active,
            'description'   => $this->description,
            'created_at'    => $this->created_at,
             
            'categories' => $this->categories->map(function ($category) {
                return [
                    'id'    => $category->id,
                    'name'  => $category->name,
                    'slug'  => $category->slug,
                ];
            }),
        ];
    }
}
