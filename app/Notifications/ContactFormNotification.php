<?php

namespace App\Notifications;

use App\Events\ContactFormSubmitted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ContactFormSubmitted $event,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission from '.$this->event->firstName.' '.$this->event->lastName)
            ->markdown('mail.contact-form', [
                'firstName' => $this->event->firstName,
                'lastName' => $this->event->lastName,
                'email' => $this->event->email,
                'company' => $this->event->company,
                'service' => $this->event->service,
                'body' => $this->event->message,
            ]);
    }
}
