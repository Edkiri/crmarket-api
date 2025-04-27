<?php

namespace App\Http\Filters;

use App\Http\Requests\Products\ProductFilterRequest;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter
{
    protected $builder;

    public function __construct(protected ProductFilterRequest $request) {}

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $filter => $value) {
            if (method_exists($this, $filter) && $value !== null) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    protected function filters()
    {
        return $this->request->validated();
    }

    protected function market_id($value)
    {
        $this->builder->where('market_id', $value);
    }

    protected function reference($value)
    {
        $this->builder->where('reference', 'like', "%$value%");
    }

    protected function name($value)
    {
        $this->builder->where('name', 'like', "%$value%");
    }

    protected function brand($value)
    {
        $this->builder->where('brand', 'like', "%$value%");
    }

    protected function is_active($value)
    {
        $this->builder->where('is_active', (bool)$value);
    }

    protected function min_cost_price($value)
    {
        $this->builder->where('cost_price', '>=', $value);
    }

    protected function max_cost_price($value)
    {
        $this->builder->where('cost_price', '<=', $value);
    }

    protected function min_sale_price($value)
    {
        $this->builder->where('sale_price', '>=', $value);
    }

    protected function max_sale_price($value)
    {
        $this->builder->where('sale_price', '<=', $value);
    }

    protected function category_ids($value)
    {
        $categories = is_array($value)
            ? $value
            : explode(',', $value);

        foreach ($categories as $category) {
            $this->builder->whereHas('categories', function (Builder $query) use ($category) {
                $query->where('categories.id', $category);
            });
        }
    }
}
