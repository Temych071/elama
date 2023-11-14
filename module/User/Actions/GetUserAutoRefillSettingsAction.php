<?php

declare(strict_types=1);

namespace Module\User\Actions;

use Module\Billing\Subscription\Models\Subscription;
use Module\User\DTO\AutoRefillSettings;
use Module\User\Models\User;

final class GetUserAutoRefillSettingsAction
{
    public function execute(User $user): AutoRefillSettings
    {
        if (!empty($user->auto_refill_settings)) {
            return $user->auto_refill_settings;
        }

        $price = $user->activeSubscriptions()
                ->with(['plan' => static fn($q) => $q->select(['id', 'price'])])
                ->select(['plan_id'])
                ->get()
                ->sum(static fn(Subscription $sub) => $sub->plan->price) / 1000;

        if ($price <= 0) {
            return new AutoRefillSettings();
        }

        return new AutoRefillSettings(
            amount: $price,
            limit: (int) ($price * 0.2),
        );
    }
}
