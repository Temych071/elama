<?php

declare(strict_types=1);

namespace App\Events;

use Module\User\Models\User;

abstract class AbstractUserEvent
{
    public function __construct(
        public readonly User $user,
    ) {
    }
}
