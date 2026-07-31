<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'description',
        'thumbnail_url',
        'price',
        'is_paid',
        'is_active',
        'is_featured',
        'date',
        'location',
        'max_participants',
        'level',
        'duration',
        'language',
        'instructor_name',
        'instructor_title',
        'instructor_bio',
        'instructor_avatar_url',
        'what_you_will_learn',
        'includes',
        'curriculum',
        'rating',
        'rating_count',
        'category',
        'extra_tags',
        'views',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'integer',
        'max_participants' => 'integer',
        'date' => 'datetime',
        'what_you_will_learn' => 'array',
        'includes' => 'array',
        'curriculum' => 'array',
        'rating' => 'decimal:1',
        'rating_count' => 'integer',
        'extra_tags' => 'integer',
        'views' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(TrainingRegistration::class);
    }

    public function isFull(): bool
    {
        if ($this->max_participants === null) {
            return false;
        }

        return $this->registrations()->count() >= $this->max_participants;
    }

    public function isRegisteredByUser(int $userId): bool
    {
        return $this->registrations()->where('user_id', $userId)->exists();
    }
}
