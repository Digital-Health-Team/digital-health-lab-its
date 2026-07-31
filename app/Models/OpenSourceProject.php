<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OpenSourceProject extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'caption',
        'category',
        'listing_type',
        'status',
        'validated_by',
        'description',
        'highlights',
        'cover_color',
        'is_featured',
        'license',
        'version',
        'format',
        'includes',
    ];

    protected $casts = [
        'description' => 'array',
        'highlights' => 'array',
        'includes' => 'array',
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
