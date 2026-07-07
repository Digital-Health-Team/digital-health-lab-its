<?php

namespace App\Enums;

enum ReportType: string
{
    case Damaged = 'damaged';

    case OutOfStock = 'out_of_stock';

    case Faulty = 'faulty';

    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Damaged => __('Damaged Stock'),
            self::OutOfStock => __('Out of Stock'),
            self::Faulty => __('Faulty Equipment'),
            self::Other => __('Other Issue'),
        };
    }
}
