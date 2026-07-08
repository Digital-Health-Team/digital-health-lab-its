<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Color extends Model
{
    protected $guarded = ['id'];

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** All stock entries that use this color. */
    public function itemStocks(): HasMany
    {
        return $this->hasMany(ItemStock::class);
    }

    /** Brands that support this color. */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brand_colors');
    }
}
