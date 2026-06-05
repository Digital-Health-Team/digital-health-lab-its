<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMaterial extends Model
{
    use HasFactory, RecordsActivity;

    protected $guarded = ['id'];

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** The lab this material belongs to. */
    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    /** The material category (e.g., Filament, Resin). */
    public function materialCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class);
    }

    /** The brand of this material (e.g., eSUN, Anycubic). */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** The color variant (e.g., White, Standard Grey). */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /** All stock movements (in/out) for this material. */
    public function movements(): HasMany
    {
        return $this->hasMany(RawMaterialMovement::class);
    }
}
