<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Module\Billing\Subscription\Models\Subscription;
use Module\Billing\Subscription\Services\SubscriptionService;

class CleanupSubscriptionsForRemovedCampaigns extends Command
{
    protected $signature = 'subscriptions:cleanup';
    protected $description = 'Cleanup subscriptions for removed campaigns.';

    public function handle(): int
    {
        $count = 0;
        Subscription::query()
            ->with(['campaign'])
            ->get()
            ->each(static function (Subscription $sub) use (&$count): void {
                if (!$sub->campaign) {
                    app(SubscriptionService::class)->end($sub);
                    $count++;
                }
            });

        $this->info("Stopped $count subscriptions.");

        return 0;
    }
}
