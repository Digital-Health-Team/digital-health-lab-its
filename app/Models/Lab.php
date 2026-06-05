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

    /** All raw materials assigned to this lab. */
    public function rawMaterials(): HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }

    /** All inventory items (tools/assets) assigned to this lab. */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
