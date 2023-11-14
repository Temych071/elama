<?php

namespace Module\Notification\Enums;

enum NotificationStatuses: string
{
    case Necessarily = 'Обязательно';
    case EnabledInSettings = 'Если указано в настройках';
}
