<?php

namespace App\Listeners;

use Module\Billing\Account\Models\Transaction;
use Module\Billing\Events\AbstractTransactionEvent;

abstract class AbstractTransactionEventListener
{
    public function handle(AbstractTransactionEvent $event): void
    {
        $this->handler($event->transaction);
    }

    abstract protected function handler(Transaction $transaction): void;
}
