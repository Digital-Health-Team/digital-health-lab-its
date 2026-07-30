<?php

namespace App\Models;

use App\Traits\HasEnglishOverlay;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Service extends Model
{
    use HasEnglishOverlay, HasFactory, RecordsActivity;

    public $timestamps = false;

    /** Attributes with an `<attr>_en` sibling column — see HasEnglishOverlay. */
    protected array $localizable = [
        'name',
        'description',
    ];

    protected $fillable = [
        'name',
        'service_type',
        'description',
        'base_price',
        'whatsapp_number',
        'name_en',
        'description_en',
    ];

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
