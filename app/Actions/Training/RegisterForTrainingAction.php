<?php

namespace App\Actions\Training;

use App\DTOs\Training\TrainingRegistrationData;
use App\Models\Training;
use App\Models\TrainingRegistration;

class RegisterForTrainingAction
{
    public function execute(Training $training, TrainingRegistrationData $data): TrainingRegistration
    {
        if ($training->isFull()) {
            throw new \Exception(__('This training is already at full capacity.'));
        }

        if ($training->isRegisteredByUser($data->user_id)) {
            throw new \Exception(__('You are already registered for this training.'));
        }

        return TrainingRegistration::create([
            'training_id' => $data->training_id,
            'user_id' => $data->user_id,
            'full_name' => $data->full_name,
            'email' => $data->email,
            'phone_number' => $data->phone_number,
            'preferred_session' => $data->preferred_session,
            'additional_notes' => $data->additional_notes,
            'status' => 'pending',
        ]);
    }
}
