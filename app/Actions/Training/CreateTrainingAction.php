<?php

namespace App\Actions\Training;

use App\DTOs\Training\TrainingData;
use App\Models\Training;
use Illuminate\Support\Str;

class CreateTrainingAction
{
    public function execute(TrainingData $data): Training
    {
        $slug = $this->generateUniqueSlug($data->title);

        return Training::create([
            'title' => $data->title,
            'slug' => $slug,
            'subtitle' => $data->subtitle,
            'description' => $data->description,
            'thumbnail_url' => $data->thumbnail_url,
            'price' => $data->price,
            'is_paid' => $data->is_paid,
            'is_active' => $data->is_active,
            'is_featured' => $data->is_featured,
            'date' => $data->date,
            'location' => $data->location,
            'max_participants' => $data->max_participants,
            'level' => $data->level,
            'duration' => $data->duration,
            'language' => $data->language,
            'instructor_name' => $data->instructor_name,
            'instructor_title' => $data->instructor_title,
            'instructor_bio' => $data->instructor_bio,
            'instructor_avatar_url' => $data->instructor_avatar_url,
            'what_you_will_learn' => $data->what_you_will_learn,
            'includes' => $data->includes,
            'curriculum' => $data->curriculum,
            'title_en' => $data->title_en,
            'subtitle_en' => $data->subtitle_en,
            'description_en' => $data->description_en,
            'location_en' => $data->location_en,
            'instructor_title_en' => $data->instructor_title_en,
            'instructor_bio_en' => $data->instructor_bio_en,
            'what_you_will_learn_en' => $data->what_you_will_learn_en ?: null,
            'includes_en' => $data->includes_en ?: null,
            'curriculum_en' => $data->curriculum_en ?: null,
        ]);
    }

    private function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Training::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
