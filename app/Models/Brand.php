<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $guarded = ['id'];

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** The material category this brand primarily belongs to. */
    public function materialCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class);
    }

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

    /** Colors this brand supports (declared via brand_colors pivot). */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'brand_colors');
    }
}
