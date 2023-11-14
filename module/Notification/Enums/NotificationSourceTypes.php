<?php

namespace Module\Notification\Enums;

enum NotificationSourceTypes: string
{
    case Telegram = 'Telegram';
    case Mail = 'Mail';
    case Web = 'Web';
}
