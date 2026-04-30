<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerService extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'subscription_price',
        'start_date',
        'end_date',
        'status',
    ];
    protected $casts = [
        'subscription_price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
