<?php

namespace App\Actions\Training;

use App\Models\TrainingRegistration;
use Illuminate\Http\UploadedFile;

class UploadTrainingPaymentProofAction
{
    public function execute(TrainingRegistration $registration, UploadedFile $proof): TrainingRegistration
    {
        $path = $proof->store('training-payments', 'public');

        $registration->update([
            'payment_proof' => $path,
            'payment_status' => 'awaiting_verification',
        ]);

        return $registration;
    }
}
