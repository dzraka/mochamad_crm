<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'lead_id',
        'project_id',
        'user_id',
        'name',
        'phone',
        'address',
        'status',
    ];
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function services(): HasMany
    {
        return $this->hasMany(CustomerService::class);
    }
}
