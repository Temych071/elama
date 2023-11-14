<?php

namespace App\Listeners;

use Module\Notification\Enums\NotificationStatuses;
use Module\Notification\Enums\NotificationTypes;
use Module\Notification\NotificationService;
use Module\User\Models\User;

class NegativeBalanceListener extends AbstractUserEventListener
{
    private const title = 'Пополните счёт проекта для продолжения работы с DailyGrow';

    protected function handler(User $user): void
    {
        $notificationService = new NotificationService($user);
        $notificationService->sendSimpleNotification(
            NotificationTypes::NegativeBalance,
            NotificationStatuses::Necessarily,
            self::title,
            $this->makeDescription($user->name),
            withoutTextHtml: true,
            tgBoldTitle: true,
        );
    }

    protected function makeDescription(string $name): string
    {
        $link = route('user.billing.new-payment.show');
        return '<br>Здравствуйте, '.$name.'!<br>'
            .'На счёте аккаунта "'.$name.'" недостаточно средств для продолжения работы с сервисом DailyGrow.<br>'
            .'Работа с проектом изменится следующим образом:<br>'
            .'- Статистика из подключенных источников перестанет собираться<br>'
            .'- Данные в отчете план/факт и виджетах перстанут отображаться<br>'
            .'- Проверка качества настройки рекламы и SEO будет приостановлен<br>'
            .'<a href="'.$link.'">Пополнить баланс</a>';
    }
}
