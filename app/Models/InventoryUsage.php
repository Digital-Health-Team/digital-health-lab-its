<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryUsage extends Model
{
    use HasFactory, RecordsActivity;

    protected $guarded = ['id'];

    /**
     * Cast timestamps for checkout/return tracking.
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** The inventory item being used. */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /** The user who checked out this item. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Optional service booking context for this usage. */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(ServiceBooking::class, 'service_booking_id');
    }
}
