<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; // 1. Import Trait

class Project extends Model
{
    use HasTranslations; // 2. Gunakan Trait

    protected $guarded = ['id'];
    // --- TAMBAHKAN/PERBAIKI BAGIAN INI ---
    protected $casts = [
        'name' => 'array',        // PENTING: Agar $project->name['id'] bisa diakses
        'description' => 'array',
        'deadline_global' => 'datetime',
    ];

    // Accessor optional untuk label dropdown
    protected $appends = ['label'];
    public function getLabelAttribute()
    {
        return $this->name['id'] ?? $this->name['en'] ?? '-';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jobdesks()
    {
        return $this->hasMany(Jobdesk::class);
    }
}
