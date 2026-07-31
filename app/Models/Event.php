<?php

namespace App\Models;

use App\Traits\HasEnglishOverlay;
use App\Traits\HasScheduleStatus;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasEnglishOverlay, HasFactory, HasScheduleStatus, RecordsActivity;

    /**
     * The taxonomy the public /events page filters by. Trainings are not stored
     * here but join the same grid as 'Workshop' — see EventController::index().
     */
    public const CATEGORIES = ['Exhibition', 'Seminar', 'Workshop'];

    public $timestamps = false;

    /** Attributes with an `<attr>_en` sibling column — see HasEnglishOverlay. */
    protected array $localizable = [
        'name',
        'theme_title',
        'subtitle',
        'description',
        'location',
    ];

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
        'name_en',
        'theme_title_en',
        'subtitle_en',
        'description_en',
        'location_en',
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

    /** @return array{0: ?Carbon, 1: ?Carbon} */
    protected function scheduleWindow(): array
    {
        return [$this->starts_at, $this->ends_at];
    }
}
