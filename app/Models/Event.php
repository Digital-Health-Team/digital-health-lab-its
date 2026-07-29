<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, RecordsActivity;

    public const STATUS_UPCOMING = 'upcoming';

    public const STATUS_ONGOING = 'ongoing';

    public const STATUS_PAST = 'past';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'year',
        'theme_title',
        'subtitle',
        'description',
        'thumbnail_url',
        'starts_at',
        'ends_at',
        'location',
        'category',
        'registration_url',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * No getRouteKeyName() override: the admin routes bind {event} by id
     * (resources/views/livewire/admin/event/index.blade.php:54 and
     * .../event/team/index.blade.php:5 both pass an integer). The public routes
     * ask for the slug explicitly via {event:slug} instead.
     *
     * The slug is derived here so every row has one, including the bare
     * Event::create() calls in tests and the admin drawer, which has no slug field.
     */
    protected static function booted(): void
    {
        static::saving(function (self $event) {
            if (! $event->slug) {
                $event->slug = Str::slug($event->name.'-'.$event->year);
            }
        });
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Temporal state of the event. Single source of truth for the status badge,
     * the listing filter, and the registration CTA.
     *
     * An event with no start date is treated as upcoming — it has been created
     * but not scheduled yet, which is the admin's most common in-between state.
     */
    public function status(): string
    {
        if ($this->starts_at === null) {
            return self::STATUS_UPCOMING;
        }

        $now = now();

        if ($this->starts_at->isFuture()) {
            return self::STATUS_UPCOMING;
        }

        // Single-day events have no end date; they stay "ongoing" for that day.
        $end = $this->ends_at ?? $this->starts_at->copy()->endOfDay();

        return $now->lessThanOrEqualTo($end) ? self::STATUS_ONGOING : self::STATUS_PAST;
    }
}
