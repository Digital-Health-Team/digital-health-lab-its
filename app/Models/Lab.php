<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lab extends Model
{
    protected $guarded = ['id'];

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** All stock entries physically stored in this lab. */
    public function itemStocks(): HasMany
    {
        return $this->hasMany(ItemStock::class);
    }

    /** All inventory items (tools/assets) assigned to this lab. */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class);
    }
}
