<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialCategory extends Model
{
    protected $guarded = ['id'];

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** All raw materials under this category. */
    public function rawMaterials(): HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }
}
