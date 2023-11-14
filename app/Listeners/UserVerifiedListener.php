<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Notification;
use Module\User\Models\User;
use Module\User\Notifications\NewUserRegisteredNotification;

class UserVerifiedListener
{
    final public const DEFAULT_CAMPAIGN_NAME = 'Новая кампания';

    public function handle(Verified $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $user->sendAfterRegistrationNotification();

        if (!is_null($notifyTo = config('mail.settings_request_notification_email'))) {
            Notification::route('mail', $notifyTo)
                ->notify(new NewUserRegisteredNotification($user));
        }
    }
}
