<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'cost_price',
        'margin_percent',
        'selling_price',
        'is_active',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'margin_percent' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            $product->selling_price = $product->cost_price
                + ($product->cost_price * $product->margin_percent / 100);
        });
    }
}
