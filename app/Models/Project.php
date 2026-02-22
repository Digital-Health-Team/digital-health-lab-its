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
        'slug', // Tambahkan ke fillable
        'description' => 'array',
        'deadline_global' => 'datetime',
    ];

    // Accessor optional untuk label dropdown
    protected $appends = ['label'];
    // INI KUNCI UTAMANYA:
    // Laravel akan otomatis mencari project berdasarkan kolom 'slug' saat parameter dikirim via URL
    // 1. Agar route() otomatis meng-generate URL menggunakan slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // 2. KUNCI AGAR BISA BACA ID & SLUG SEKALIGUS
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)
            ->orWhere('slug', $value)
            ->firstOrFail();
    }
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
