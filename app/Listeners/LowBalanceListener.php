<?php

namespace App\Listeners;

use Module\Billing\Events\LowBalanceEvent;
use Module\Notification\Enums\NotificationStatuses;
use Module\Notification\Enums\NotificationTypes;
use Module\Notification\NotificationService;

class LowBalanceListener
{
    public function handle(LowBalanceEvent $event): void
    {
        $notificationService = new NotificationService($event->user);
        $notificationService->sendSimpleNotification(
            NotificationTypes::LowBalance,
            NotificationStatuses::Necessarily,
            $this->makeTitle($event->days),
            $this->makeDescription($event->user->name, $event->days),
            withoutTextHtml: true,
            tgBoldTitle: true,
        );
    }

    protected function makeTitle(int $days): string
    {
        return 'Средств на аккаунте в DailyGrow хватит на '.$days.' дней';
    }

    protected function makeDescription(string $name, int $days): string
    {
        $link = route('user.billing.new-payment.show');
        return '<br>Здравствуйте, '.$name.'!<br>'
            .'На счёте аккаунта "'.$name.'" хватит средств на '.$days.' дней.<br>'
            .'Во избежание остановки сбора статистики и проверки рекламы <a href="'.$link.'">пополните баланс</a>';
    }
}
