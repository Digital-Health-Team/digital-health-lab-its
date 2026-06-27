<?php

namespace Database\Factories;

use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TrainingFactory extends Factory
{
    protected $model = Training::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'subtitle' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'thumbnail_url' => null,
            'price' => 0,
            'is_paid' => false,
            'is_active' => true,
            'is_featured' => false,
            'date' => now()->addDays(30),
            'location' => 'Lab A',
            'max_participants' => null,
            'level' => 'Beginner',
            'duration' => '3h',
            'language' => 'Indonesian',
            'instructor_name' => $this->faker->name(),
            'instructor_title' => null,
            'instructor_bio' => null,
            'instructor_avatar_url' => null,
            'what_you_will_learn' => [],
            'includes' => [],
            'curriculum' => [],
        ];
    }

    public function paid(int $price = 350000): static
    {
        return $this->state(['is_paid' => true, 'price' => $price]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }
}
