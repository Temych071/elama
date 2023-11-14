<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Exceptions;

final class SubscriptionsCantReplacePlanToSame extends SubscriptionException
{
    public function __construct(?string $redirectTo = null)
    {
        parent::__construct('Нельзя сменить тариф на тот же самый.', $redirectTo);
    }
}
