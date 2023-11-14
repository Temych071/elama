<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Actions;

use Module\Billing\Account\Services\TransactionsService;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Billing\Subscription\Models\Subscription;
use Module\User\Models\User;

final class CalcDaysLeftAction
{
    public function execute(User $user): ?float
    {
        $monthPrice = $user->activeSubscriptions
            ->where('status', SubscriptionStatus::active)
            ->loadMissing('plan')
            ->sum(static fn (Subscription $subscription): int => $subscription->plan->price);

        $dayPrice = $monthPrice / 30;
        $balance = app(TransactionsService::class)->balance($user);

        if (!$dayPrice) {
            return null;
        }

        return $balance / $dayPrice;
    }
}
