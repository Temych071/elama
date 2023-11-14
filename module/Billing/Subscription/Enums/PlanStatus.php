<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Enums;

enum PlanStatus: string
{
    case active = 'active';
    case archived = 'archived';
}
