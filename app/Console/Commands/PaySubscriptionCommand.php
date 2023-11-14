<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Module\Billing\Subscription\Actions\PaySubscriptionAction;

final class PaySubscriptionCommand extends Command
{
    protected $signature = 'subscriptions:pay';

    public function handle(PaySubscriptionAction $action): void
    {
        $count = $action->execute();
        $this->info("Subscriptions paid [Total: {$count['total']} | Paused: {$count['paused']}].");
    }
}
