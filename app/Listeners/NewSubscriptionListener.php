<?php

namespace App\Listeners;

use Module\Notification\Enums\NotificationStatuses;
use Module\Notification\Enums\NotificationTypes;
use Module\Notification\NotificationService;
use Module\User\Models\User;

class NewSubscriptionListener extends AbstractUserEventListener
{
    private const title = 'На вашем аккаунте в DailyGrow подключен новый тариф';

    protected function handler(User $user): void
    {
        $notificationService = new NotificationService($user);
        $notificationService->sendSimpleNotification(
            NotificationTypes::ChangedTariff,
            NotificationStatuses::Necessarily,
            self::title,
            $this->makeDescription($user->name),
            withoutTextHtml: true,
            tgBoldTitle: true,
        );
    }

    protected function makeDescription(string $name): string
    {
        return '<br>Здравствуйте, '.$name.'! На вашем аккаунте был достигнут лимит, тариф был автоматически переключен на новый';
    }
}
