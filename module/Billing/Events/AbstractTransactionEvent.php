<?php

namespace Module\Billing\Events;

use Module\Billing\Account\Models\Transaction;
use Module\User\Models\User;

abstract class AbstractTransactionEvent
{
    public readonly User $user;

    public function __construct(
        public readonly Transaction $transaction,
    ) {
        $this->user = $this->transaction->user;
    }
}
