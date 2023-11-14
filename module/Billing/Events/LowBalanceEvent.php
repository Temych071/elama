<?php

namespace Module\Billing\Events;

use Module\User\Models\User;

class LowBalanceEvent
{
    public function __construct(
        public readonly User $user,
        public readonly int $days,
    ) {
    }
}
