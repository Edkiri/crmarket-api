<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_id',
        'reference',
        'stock',
        'name',
        'brand',
        'cost_price',
        'sale_price',
        'is_active',
        'description',
    ];

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }
}
