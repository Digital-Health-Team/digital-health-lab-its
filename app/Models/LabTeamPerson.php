<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LabTeamPerson extends Model
{
    protected $fillable = [
        'section_id', 'is_leader', 'name_full', 'slug', 'display_line_1', 'display_line_2',
        'role_id', 'role_en', 'bio', 'email', 'linkedin_url', 'instagram_url', 'expertise',
        'completed_projects', 'education', 'units', 'departments', 'pic',
        // ponytail: initials doubles as the roster code (IQB/RAY/…); split them when the
        // avatar fallback glyph and the code need to differ.
        'initials', 'photo_url', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_leader' => 'boolean',
        'is_active' => 'boolean',
        'expertise' => 'array',
        'completed_projects' => 'array',
        'education' => 'array',
        'units' => 'array',
        'departments' => 'array',
        'pic' => 'array',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(LabTeamSection::class);
    }

    /**
     * Slugify $name, appending -2/-3/... until it's unique. $ignoreId skips
     * self on update so an unchanged name doesn't collide with its own row.
     */
    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'anggota';
        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
