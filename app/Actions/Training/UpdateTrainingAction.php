<?php

namespace App\Actions\Training;

use App\DTOs\Training\TrainingData;
use App\Models\Training;

class UpdateTrainingAction
{
    public function execute(Training $training, TrainingData $data): Training
    {
        $training->update([
            'title' => $data->title,
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
        ]);

        return $training;
    }
}
