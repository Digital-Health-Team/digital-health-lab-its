<?php

namespace App\Actions\Training;

use App\Models\Training;

class DeleteTrainingAction
{
    public function execute(Training $training): void
    {
        if ($training->registrations()->where('status', 'confirmed')->exists()) {
            throw new \Exception(__('Cannot delete a training that has confirmed participants.'));
        }

        $training->delete();
    }
}
