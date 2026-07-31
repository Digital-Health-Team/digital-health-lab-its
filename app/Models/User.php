<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use App\Traits\RecordsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable implements HasLocalePreference, MustVerifyEmail
{
    use HasFactory, HasTranslations, Notifiable, RecordsActivity;

    /**
     * Laravel sends every notification/mailable to this user in their saved locale.
     */
    public function preferredLocale(): ?string
    {
        return $this->locale;
    }

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
        'email_verified_at',
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
     * The role currently in use — session override takes priority over the primary role_id.
     */
    public function activeRoleName(): string
    {
        $sessionRole = session('active_role');
        if ($sessionRole && $this->roles->contains('name', $sessionRole)) {
            return $sessionRole;
        }

        return $this->role?->name ?? '';
    }

    /**
     * Whether the role-switcher UI should be shown.
     */
    public function canSwitchRoles(): bool
    {
        return $this->roles->count() > 1;
    }

    /**
     * Helper check admin — uses active role so switching works correctly.
     */
    public function isAdmin(): bool
    {
        return in_array($this->activeRoleName(), ['super_admin', 'admin_lab', 'admin_gudang']);
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
            return strtoupper(substr($words[0], 0, 1).substr(end($words), 0, 1));
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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
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

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }
}
