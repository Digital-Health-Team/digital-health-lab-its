<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $guarded = ['id'];

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** All raw materials of this brand. */
    public function rawMaterials(): HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }

    /** All inventory items (tools/assets) of this brand. */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
