<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Enums;

enum SubscriptionStatus: string
{
    case active = 'active';
    case paused = 'paused';
    case ended = 'stopped';
    case noCharged = 'not-charged';
}
