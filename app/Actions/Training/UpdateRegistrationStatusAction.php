<?php

namespace App\Actions\Training;

use App\Models\TrainingRegistration;
use App\Notifications\TrainingRegistrationConfirmed;

class UpdateRegistrationStatusAction
{
    public function execute(TrainingRegistration $registration, string $status): TrainingRegistration
    {
        if (! in_array($status, ['pending', 'confirmed', 'cancelled'])) {
            throw new \Exception(__('Invalid registration status.'));
        }

        $registration->update(['status' => $status]);

        if ($status === 'confirmed') {
            $registration->load('training');
            $registration->user?->notify(new TrainingRegistrationConfirmed($registration));
        }

        return $registration;
    }
}
