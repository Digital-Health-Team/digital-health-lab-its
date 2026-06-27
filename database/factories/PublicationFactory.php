<?php

namespace Database\Factories;

use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PublicationFactory extends Factory
{
    protected $model = Publication::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(4),
            'author' => $this->faker->name(),
            'category' => $this->faker->randomElement(['Journals', 'Papers', 'Research']),
            'abstract' => $this->faker->paragraph(),
            'description' => [$this->faker->paragraph(), $this->faker->paragraph()],
            'keywords' => $this->faker->words(5),
            'doi' => '10.1000/'.$this->faker->numerify('###.####'),
            'journal' => $this->faker->words(3, true),
            'pmid' => (string) $this->faker->numerify('#######'),
            'thumbnail_path' => null,
            'pdf_path' => null,
            'pdf_file_size' => null,
            'view_count' => $this->faker->numberBetween(0, 500),
            'is_free_access' => $this->faker->boolean(),
            'is_featured' => false,
            'published_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
