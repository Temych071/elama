<?php

declare(strict_types=1);

namespace Module\User\Enums;

enum UserTariff: string
{
    case free = 'free';
    case unlimited = 'unlimited';
    case test = 'test';
}
