<?php

namespace Database\Factories;

use App\Models\ServiceBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingPayment>
 */
class BookingPaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'service_booking_id' => ServiceBooking::factory(),
            'amount' => fake()->numberBetween(50000, 250000),
            'termin_name' => fake()->randomElement(['DP', 'Termin 2', 'Pelunasan']),
            'status' => 'pending',
            'payment_proof' => null,
            'paid_at' => null,
            'verified_by' => null,
        ];
    }
}
