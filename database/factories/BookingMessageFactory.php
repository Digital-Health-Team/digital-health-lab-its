<?php

namespace Database\Factories;

use App\Models\ServiceBooking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingMessage>
 */
class BookingMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'service_booking_id' => ServiceBooking::factory(),
            'sender_id' => User::factory(),
            'body' => fake()->sentence(),
            'read_at' => null,
        ];
    }
}
