<?php

namespace App\Models;

use App\Support\UniqueCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Tool extends Model
{
    protected $guarded = ['id'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            if (empty($model->unique_code)) {
                $model->unique_code = UniqueCodeGenerator::generate('ALAT', now(), 'tools');
            }
        });
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable')->orderBy('sort_order');
    }

    public function primaryAttachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachable')->where('is_primary', true);
    }
}
