<?php

namespace App\Notifications;

use App\Models\TrainingRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrainingRegistrationConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TrainingRegistration $registration,
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pendaftaran Workshop Dikonfirmasi — '.$this->registration->training->title)
            ->view('emails.training-registration-confirmed', [
                'registration' => $this->registration,
                'training' => $this->registration->training,
            ]);
    }
}
