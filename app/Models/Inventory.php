<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory, RecordsActivity;

    protected $guarded = ['id'];

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** The lab this inventory item belongs to. */
    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    /** The brand of this inventory item (nullable). */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** All checkout/return records for this inventory item. */
    public function usages(): HasMany
    {
        return $this->hasMany(InventoryUsage::class);
    }
}
