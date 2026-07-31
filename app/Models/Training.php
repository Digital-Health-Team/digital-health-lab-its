<?php

namespace App\Models;

use App\Traits\HasEnglishOverlay;
use App\Traits\HasScheduleStatus;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasEnglishOverlay, HasFactory, HasScheduleStatus, RecordsActivity;

    /** Attributes with an `<attr>_en` sibling column — see HasEnglishOverlay. */
    protected array $localizable = [
        'title',
        'subtitle',
        'description',
        'location',
        'instructor_title',
        'instructor_bio',
        'what_you_will_learn',
        'includes',
        'curriculum',
    ];

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
        'title_en',
        'subtitle_en',
        'description_en',
        'location_en',
        'instructor_title_en',
        'instructor_bio_en',
        'what_you_will_learn_en',
        'includes_en',
        'curriculum_en',
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
        'what_you_will_learn_en' => 'array',
        'includes_en' => 'array',
        'curriculum_en' => 'array',
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

    /** A training runs on one day; there is no end column. */
    protected function scheduleWindow(): array
    {
        return [$this->date, null];
    }

    /**
     * Shape rendered by CourseCard — on the /events grid and in RelatedTrainings.
     * Expects the query to have run withCount('registrations').
     *
     * @return array<string, mixed>
     */
    public function toCardArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'href' => route('training.show', $this->slug),
            'title' => $this->localized('title'),
            'thumbnailUrl' => $this->thumbnail_url,
            'price' => $this->price,
            'isPaid' => $this->is_paid,
            'level' => $this->level,
            'duration' => $this->duration,
            'language' => $this->language,
            'date' => $this->date?->toIso8601String(),
            'location' => $this->localized('location'),
            'instructorName' => $this->instructor_name,
            'instructorAvatarUrl' => $this->instructor_avatar_url,
            'participantsCount' => $this->registrations_count,
            'rating' => (float) $this->rating,
            'ratingCount' => $this->rating_count,
            'category' => $this->category,
            'extraTags' => $this->extra_tags ?: null,
            'students' => $this->formatCount($this->views),
            'staffPick' => $this->is_featured,
            'instructor' => [
                'name' => $this->instructor_name,
                'title' => $this->localized('instructor_title'),
                'avatarUrl' => $this->instructor_avatar_url,
                'verified' => true,
                'students' => $this->formatCount($this->registrations_count),
            ],
        ];
    }

    /** @return array<string, mixed> */
    public function toStaffPickArray(): array
    {
        return [
            ...$this->toCardArray(),
            'subtitle' => $this->localized('subtitle'),
            'description' => $this->localized('description'),
        ];
    }

    private function formatCount(int $n): string
    {
        if ($n >= 1000) {
            $k = $n / 1000;

            return rtrim(rtrim(number_format($k, 1), '0'), '.').'k';
        }

        return (string) $n;
    }
}
