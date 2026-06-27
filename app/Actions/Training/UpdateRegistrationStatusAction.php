<?php

namespace App\Actions\Training;

use App\Models\TrainingRegistration;

class UpdateRegistrationStatusAction
{
    public function execute(TrainingRegistration $registration, string $status): TrainingRegistration
    {
        if (! in_array($status, ['pending', 'confirmed', 'cancelled'])) {
            throw new \Exception(__('Invalid registration status.'));
        }

        $registration->update(['status' => $status]);

        return $registration;
    }
}
