<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        $startsAt = now()->addDays(30);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1, 99999),
            'year' => (int) $startsAt->format('Y'),
            'theme_title' => $this->faker->sentence(6),
            'subtitle' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'thumbnail_url' => null,
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addDays(2),
            'location' => 'Lab Tekkes — ITS Sukolilo',
            'category' => 'Showcase',
            'registration_url' => null,
            'is_featured' => false,
            'is_active' => true,
        ];
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    public function ongoing(): static
    {
        return $this->state([
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);
    }

    public function past(): static
    {
        return $this->state([
            'starts_at' => now()->subDays(30),
            'ends_at' => now()->subDays(28),
        ]);
    }
}
