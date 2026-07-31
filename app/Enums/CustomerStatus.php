<?php

namespace App\Enums;

/**
 * Simplified public-facing order stages shown to customers.
 * Each admin BookingStatus maps into one of these via BookingStatus::customerStage().
 */
enum CustomerStatus: string
{
    case WarehouseCheck = 'warehouse_check'; // Admin: Review Brief + Check Material

    case SetPrice = 'set_price';             // Admin: Slicing + Set Price (prompts the 30% DP)

    case Processing = 'processing';          // Admin: Production — Printing

    case PostProcessing = 'post_processing'; // Admin: Production — Finishing

    case Finish = 'finish';                  // Admin: Final Price + Finished

    case Cancelled = 'cancelled';

    case Consultation = 'consultation';      // Admin: Consultation (a chat thread, not an order)

    public function label(): string
    {
        return match ($this) {
            self::Consultation => __('Consultation'),
            self::WarehouseCheck => __('Warehouse Check'),
            self::SetPrice => __('Set Price'),
            self::Processing => __('Processing'),
            self::PostProcessing => __('Post Processing'),
            self::Finish => __('Finish'),
            self::Cancelled => __('Cancelled'),
        };
    }
}
