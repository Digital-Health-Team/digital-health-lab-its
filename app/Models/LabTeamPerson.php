<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabTeamPerson extends Model
{
    protected $fillable = [
        'section_id', 'is_leader', 'name_full', 'display_line_1', 'display_line_2',
        'role_id', 'role_en', 'bio', 'initials', 'photo_url', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_leader' => 'boolean', 'is_active' => 'boolean'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(LabTeamSection::class);
    }
}
