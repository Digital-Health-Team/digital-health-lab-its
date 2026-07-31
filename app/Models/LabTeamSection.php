<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class LabTeamSection extends Model
{
    protected $fillable = ['label_id', 'label_en', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function leader(): HasOne
    {
        return $this->hasOne(LabTeamPerson::class, 'section_id')->where('is_leader', true);
    }

    public function members(): HasMany
    {
        return $this->hasMany(LabTeamPerson::class, 'section_id')
            ->where('is_leader', false)
            ->orderBy('sort_order');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable')->orderBy('sort_order');
    }
}
