<?php

namespace Module\Notification\Enums;

enum NotificationTypes: string
{
    case LowBalance = 'Низкий баланс';
    case NegativeBalance = 'Средста закончились';
    case PaymentReceived = 'Поступила оплата';
    case ChangedTariff = 'Подключен новый тариф';
    case Review = 'Отзыв';
}
