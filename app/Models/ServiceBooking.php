<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'service_id',
        'product_reference_id',
        'brief_description',
        'slicer_weight_grams',
        'slicer_print_time_minutes',
        'agreed_price',
        'current_status',
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

    public function materialUsages(): HasMany
    {
        return $this->hasMany(RawMaterialMovement::class);
    }
}
