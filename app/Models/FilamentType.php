<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FilamentType extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'price_per_gram' => 'integer',
        'sort_order' => 'integer',
    ];

    // ==========================================
    // ELOQUENT SCOPES
    // ==========================================

    /** Returns only active filament types, ordered by sort_order. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
