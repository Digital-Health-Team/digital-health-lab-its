<?php

namespace App\DTOs\Transaction;

class SlicerCalculationData
{
    public function __construct(
        public int $booking_id,
        public int $slicer_weight_grams,
        public int $slicer_print_time_minutes,
        public int $final_price
    ) {}
}
