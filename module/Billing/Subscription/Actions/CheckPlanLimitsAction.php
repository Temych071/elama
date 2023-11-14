<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Actions;

use Illuminate\Database\Eloquent\Builder;
use Module\Billing\Events\NewSubscriptionEvent;
use Module\Billing\Subscription\Enums\PlanStatus;
use Module\Billing\Subscription\Exceptions\SubscriptionAlreadyExistsException;
use Module\Billing\Subscription\Exceptions\SubscriptionNotExistsException;
use Module\Billing\Subscription\Models\Plan;
use Module\Billing\Subscription\Models\Subscription;
use Module\Billing\Subscription\Services\SubscriptionService;
use Module\Campaign\Actions\GetVisitsCountAction;
use Throwable;

final class CheckPlanLimitsAction
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService
    ) {
    }

    /**
     * @throws SubscriptionAlreadyExistsException
     * @throws SubscriptionNotExistsException
     * @throws Throwable
     */
    public function execute(Subscription $subscription): Subscription
    {
        $limit = $subscription->plan->visits_limit;
        if (is_null($limit)) {
            return $subscription;
        }

        $visits = app(GetVisitsCountAction::class)->execute($subscription->campaign);

        if ($visits > $limit) {
            return $this->replacePlan($subscription, $visits);
        }

        return $subscription;
    }

    /**
     * @throws Throwable
     * @throws SubscriptionAlreadyExistsException
     * @throws SubscriptionNotExistsException
     */
    private function replacePlan(Subscription $subscription, int $visits): Subscription
    {
        /** @var Plan $plan */
        $plan = Plan::query()
            ->where('status', PlanStatus::active)
            ->where(
                static fn (Builder $q) => $q
                ->orWhere('visits_limit', '>=', $visits)
                ->orWhereNull('visits_limit')
            )
            ->orderBy('price')
            ->first();

        if (is_null($plan)) {
            $plan = Plan::query()
                ->where('status', PlanStatus::active)
                ->orderByDesc('visits_limit')
                ->first();
        }

        if ($plan->id === $subscription->plan->id) {
            return $subscription;
        }

        event(new NewSubscriptionEvent(user: $subscription->user));

        return $this->subscriptionService->replace(
            $subscription->campaign,
            $subscription->user,
            $plan,
        );
    }
}
