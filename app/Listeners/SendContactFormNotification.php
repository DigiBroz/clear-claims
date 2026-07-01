<?php

namespace App\Listeners;

use App\Events\ContactFormSubmitted;
use App\Notifications\ContactFormNotification;
use Illuminate\Support\Facades\Notification;

class SendContactFormNotification
{
    public function handle(ContactFormSubmitted $event): void
    {
        Notification::route('mail', ['info@clearclaims.health'])
            ->notify(new ContactFormNotification($event));
    }
}
