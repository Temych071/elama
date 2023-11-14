<?php

namespace Module\Billing\Payments\Actions;

use Illuminate\Support\Facades\Log;
use Module\Billing\Payments\Jobs\RunAutoRefillJob;
use Module\User\Models\User;

class DispatchAutoRefillAction
{

    public function execute(User $user, int $delay = 60): void
    {
        RunAutoRefillJob::dispatch($user)
            ->delay($delay);
    }
}
