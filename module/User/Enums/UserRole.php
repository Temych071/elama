<?php

declare(strict_types=1);

namespace Module\User\Enums;

enum UserRole: string
{
    case user = 'user';
    case admin = 'admin';
    case banned = 'banned';
}
