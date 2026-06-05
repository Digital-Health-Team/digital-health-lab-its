<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Color extends Model
{
    protected $guarded = ['id'];

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** All raw materials of this color. */
    public function rawMaterials(): HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }
}
