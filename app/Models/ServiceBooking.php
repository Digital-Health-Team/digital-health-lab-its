<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceBooking extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'service_id',
        'product_reference_id',
        'brief_description',
        'reference_photo_path',
        'model_file_path',
        'material_preference',
        'filament_width',
        'scan_purpose',
        'object_dimensions',
        'slicer_weight_grams',
        'slicer_print_time_minutes',
        'agreed_price',
        'current_status',
        'material_verified_at',
        'material_verified_by',
        'material_flagged_at',
        'material_flag_note',
    ];

    protected $casts = [
        'object_dimensions' => 'array',
        'current_status' => BookingStatus::class,
        'material_verified_at' => 'datetime',
        'material_flagged_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_reference_id');
    }

    public function progressUpdates(): HasMany
    {
        return $this->hasMany(ServiceProgressUpdate::class);
    }

    public function materialMovements(): HasMany
    {
        return $this->hasMany(RawMaterialMovement::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(BookingMessage::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BookingPayment::class);
    }

    /** Warehouse admin who verified material availability. */
    public function materialVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'material_verified_by');
    }

    public function isMaterialVerified(): bool
    {
        return $this->material_verified_at !== null;
    }

    /**
     * Simplified public-facing stage shown to customers.
     */
    public function getCustomerStageAttribute(): string
    {
        return $this->current_status->customerStage()->value;
    }

    /**
     * Sum of all verified (paid) payment termins.
     */
    public function getTotalPaidAttribute(): int
    {
        return (int) $this->payments()->where('status', 'paid')->sum('amount');
    }

    /**
     * Outstanding balance against the agreed total price.
     */
    public function getRemainingBalanceAttribute(): int
    {
        return (int) $this->agreed_price - $this->total_paid;
    }
}
