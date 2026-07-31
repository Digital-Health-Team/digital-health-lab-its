<?php

namespace App\Models;

use App\Support\UniqueCodeGenerator;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMaterial extends Model
{
    use HasFactory, RecordsActivity;

    protected $guarded = ['id'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            if (empty($model->unique_code)) {
                $model->unique_code = UniqueCodeGenerator::generate('BAHAN', now(), 'raw_materials');
            }
        });
    }

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** The brand this item belongs to (e.g., eSUN, Anycubic). */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** Per-location, per-color stock entries for this item. */
    public function stocks(): HasMany
    {
        return $this->hasMany(ItemStock::class);
    }

    /** All stock movements (in/out) audit trail. */
    public function movements(): HasMany
    {
        return $this->hasMany(RawMaterialMovement::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
