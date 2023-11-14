<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Listeners;

use Module\Admin\Settings\BillingSettings;
use Module\Billing\Subscription\Exceptions\SubscriptionAlreadyExistsException;
use Module\Billing\Subscription\Exceptions\SubscriptionNotExistsException;
use Module\Billing\Subscription\Models\Plan;
use Module\Billing\Subscription\Services\SubscriptionService;
use Module\Campaign\Events\CreateProjectEvent;
use Module\User\Models\User;
use Throwable;

final class AddSubscriptionForNewProjectListener
{
    /**
     * @throws Throwable
     * @throws SubscriptionAlreadyExistsException
     * @throws SubscriptionNotExistsException
     */
    public function handle(CreateProjectEvent $event): void
    {
        if ($event->plan === false) {
            return;
        }

        $initialPlanId = app(BillingSettings::class)?->initial_plan_id;
        if (!$initialPlanId) {
            return;
        }

        /** @var Plan $plan */
        $plan = $event->plan ?? Plan::query()->find(app(BillingSettings::class)->initial_plan_id);

        /** @var User $owner */
        $owner = $event->campaign->owner?->first();

        if (is_null($plan) || is_null($owner)) {
            return;
        }

        app(SubscriptionService::class)->add(
            $event->campaign,
            $owner,
            $plan,
        );
    }
}
