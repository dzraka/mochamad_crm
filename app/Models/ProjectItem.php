<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectItem extends Model
{
    protected $fillable = [
        'project_id',
        'product_id',
        'normal_price',
        'negotiated_price',
        'qty',
        'subtotal',
    ];

    protected $casts = [
        'normal_price' => 'decimal:2',
        'negotiated_price' => 'decimal:2',
        'qty' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
