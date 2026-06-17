<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceBooking>
 */
class ServiceBookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'brief_description' => fake()->paragraph(),
            'agreed_price' => fake()->numberBetween(50000, 500000),
            'current_status' => 'pending',
        ];
    }
}
