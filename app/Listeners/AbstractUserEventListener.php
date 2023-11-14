<?php

namespace App\Listeners;

use App\Events\AbstractUserEvent;
use Module\User\Models\User;

abstract class AbstractUserEventListener
{
    public function handle(AbstractUserEvent $event): void
    {
        $this->handler($event->user);
    }

    abstract protected function handler(User $user): void;
}
