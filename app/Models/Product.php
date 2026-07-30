<?php

namespace App\Models;

use App\Traits\HasEnglishOverlay;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasEnglishOverlay, HasFactory, RecordsActivity;

    public $timestamps = false;

    /** Attributes with an `<attr>_en` sibling column — see HasEnglishOverlay. */
    protected array $localizable = [
        'name',
        'description',
    ];

    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'price_min',
        'price_max',
        'is_active',
        'name_en',
        'description_en',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
