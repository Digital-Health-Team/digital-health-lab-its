<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    // Relasi ke tabel apapun yang dicatat (Order, Material, dll)
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    // Relasi ke User yang melakukan aksi
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
