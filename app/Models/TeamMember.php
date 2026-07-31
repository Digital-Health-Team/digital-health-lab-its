<?php

namespace App\Models;

use App\Traits\RecordsActivity; // Import trait Activity Log yang baru dibuat
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamMember extends Model
{
    use HasFactory, RecordsActivity;

    // Melindungi primary key agar tidak bisa di-mass-assign, sisanya bisa.
    protected $guarded = ['id'];

    // Casting tipe data
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relasi ke Atasan (Misal: Anggota melapor ke CEO)
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'parent_id');
    }

    /**
     * Relasi ke Bawahan (Misal: CEO memiliki banyak anggota)
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Scope: Hanya ambil anggota tim yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Ambil pemimpin tertinggi (CEO/Head) yang tidak punya atasan
     */
    public function scopeLeaders($query)
    {
        return $query->whereNull('parent_id');
    }
}
