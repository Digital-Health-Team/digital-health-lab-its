<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

/**
 * Temporal state of anything with a scheduled window. Single source of truth for
 * the status badge, the /events listing filter, and the registration CTA — shared
 * by Event (starts_at → ends_at) and Training (a single date).
 */
trait HasScheduleStatus
{
    public const STATUS_UPCOMING = 'upcoming';

    public const STATUS_ONGOING = 'ongoing';

    public const STATUS_PAST = 'past';

    /**
     * The model's scheduled window. A null end means a single-day thing.
     *
     * @return array{0: ?Carbon, 1: ?Carbon} [start, end]
     */
    abstract protected function scheduleWindow(): array;

    /**
     * Something with no start date is treated as upcoming — it has been created
     * but not scheduled yet, which is the admin's most common in-between state.
     */
    public function status(): string
    {
        [$start, $end] = $this->scheduleWindow();

        if ($start === null) {
            return self::STATUS_UPCOMING;
        }

        if ($start->isFuture()) {
            return self::STATUS_UPCOMING;
        }

        // No end date means it runs for the day only.
        $end ??= $start->copy()->endOfDay();

        return now()->lessThanOrEqualTo($end) ? self::STATUS_ONGOING : self::STATUS_PAST;
    }
}
