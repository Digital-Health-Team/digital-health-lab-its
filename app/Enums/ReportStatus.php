<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Open = 'open';

    case InReview = 'in_review';

    case Resolved = 'resolved';

    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Open => __('Open'),
            self::InReview => __('In Review'),
            self::Resolved => __('Resolved'),
            self::Rejected => __('Rejected'),
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Resolved, self::Rejected], true);
    }
}
