<?php

namespace App\Models;

use App\Traits\HasEnglishOverlay;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OpenSourceProject extends Model
{
    use HasEnglishOverlay, HasFactory, RecordsActivity;

    /** Attributes with an `<attr>_en` sibling column — see HasEnglishOverlay. */
    protected array $localizable = [
        'title',
        'caption',
        'description',
        'highlights',
        'includes',
    ];

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
        'title_en',
        'caption_en',
        'description_en',
        'highlights_en',
        'includes_en',
        'withdrawal_requested_at',
    ];

    protected $casts = [
        'description' => 'array',
        'highlights' => 'array',
        'includes' => 'array',
        'is_featured' => 'boolean',
        'withdrawal_requested_at' => 'datetime',
        'description_en' => 'array',
        'highlights_en' => 'array',
        'includes_en' => 'array',
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
