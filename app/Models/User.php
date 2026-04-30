<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasTranslations;

    /**
     * Tentukan kolom mana saja yang bersifat translatable (Spatie).
     * Kosongkan array ini jika belum ada kolom di tabel users yang ditranslate,
     * untuk mencegah error dari trait HasTranslations.
     */
    public array $translatable = [];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'profile_photo',
        'timezone',
        'locale',
        'preferences',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
            'timezone' => 'string',
            'locale' => 'string',
            'is_active' => 'boolean',
        ];
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Helper check admin.
     * Disesuaikan dengan seeder kita: 1 = super_admin, 2 = admin_lab
     */
    public function isAdmin(): bool
    {
        return in_array($this->role_id, [1, 2]);
    }

    /**
     * Helper untuk mengambil inisial nama (Untuk profile_photo).
     * Contoh: "Budi Santoso" -> "BS", "Admin" -> "AD"
     */
    public function initials(): string
    {
        $words = explode(' ', $this->name);

        // Jika nama terdiri dari 2 kata atau lebih (Contoh: Budi Santoso)
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }

        // Jika hanya 1 kata (Contoh: Admin), ambil 2 huruf pertama
        return strtoupper(substr($this->name, 0, 2));
    }

    // ==========================================
    // ELOQUENT RELATIONSHIPS (Sistem Lab)
    // ==========================================

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function openSourceProjects(): HasMany
    {
        return $this->hasMany(OpenSourceProject::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'attachable_id')->where('attachable_type', self::class);
    }
}
