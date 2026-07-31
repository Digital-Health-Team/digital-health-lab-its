<?php

namespace App\Models;

use App\Traits\HasEnglishOverlay;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasEnglishOverlay, HasFactory, RecordsActivity;

    /** Attributes with an `<attr>_en` sibling column — see HasEnglishOverlay. */
    protected array $localizable = [
        'title',
    ];

    protected $fillable = [
        'team_id',
        'title',
        'category',
        'status',
        'validated_by',
        'title_en',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
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
