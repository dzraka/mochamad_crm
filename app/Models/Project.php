<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'approved_by',
        'status',
        'total_price',
        'needs_approval',
        'notes',
        'approved_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'needs_approval' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProjectItem::class);
    }
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }
}
