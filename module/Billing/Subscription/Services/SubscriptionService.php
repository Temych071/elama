<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Account\Services\TransactionsService;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Billing\Subscription\Exceptions\SubscriptionAlreadyExistsException;
use Module\Billing\Subscription\Exceptions\SubscriptionNotExistsException;
use Module\Billing\Subscription\Exceptions\SubscriptionsCantReplacePlanToSame;
use Module\Billing\Subscription\Models\Plan;
use Module\Billing\Subscription\Models\Subscription;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;
use Throwable;

final class SubscriptionService
{
    /**
     * @throws SubscriptionNotExistsException
     * @throws Throwable
     */
    public function charge(Subscription|Campaign $model): bool
    {
        $sub = $this->getSubscriptionOrThrow($model);

        $service = app(TransactionsService::class);

        $currentBalance = $service->balance($sub->user);
        $cost = $sub->getChargeCost();

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/subs-debug.log'),
        ])->info('SubscriptionService@charge', [
            '$sub' => $sub,
            '$currentBalance' => $currentBalance,
            '$cost' => $cost,
        ]);

        if ($cost <= 0) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/subs-debug.log'),
            ])->info('SubscriptionService@charge - $cost <= 0');
            return true;
        }

        if ($currentBalance >= $cost) {
            DB::transaction(static function () use ($service, $cost, $sub): void {
                $service->credit(
                    user: $sub->user,
                    amount: $cost,
                    type: TransactionType::SUBSCRIPTION_CHARGE,
                    campaign: $sub->campaign,
                    desc: $sub->plan->name,
                );
                $sub->charge();
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/subs-debug.log'),
                ])->info('SubscriptionService@charge - charged');
            });
        } else {
            $sub->markAsNotCharge();
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/subs-debug.log'),
            ])->info('SubscriptionService@charge - not charged');
            return false;
        }

        return true;
    }

    /**
     * @throws SubscriptionAlreadyExistsException
     * @throws SubscriptionNotExistsException
     * @throws Throwable
     */
    public function add(Campaign $campaign, User $user, Plan $plan): Subscription
    {
        $this->throwIfHas($campaign);

        $sub = $this->create($campaign, $user, $plan);
        $this->charge($sub);

        return $sub;
    }

    /**
     * @throws SubscriptionNotExistsException
     * @throws Throwable
     */
    public function resume(Subscription|Campaign $model): bool
    {
        $sub = $this->getSubscriptionOrThrow($model);

        if ($sub->status === SubscriptionStatus::active) {
            return true;
        }

        return $this->charge($sub);
    }

    public function end(Subscription|Campaign $model): void
    {
        $this->getSubscription($model)
            ?->markAsEnded();
    }

    /**
     * @throws SubscriptionAlreadyExistsException
     * @throws SubscriptionNotExistsException
     * @throws Throwable
     */
    public function replace(Campaign $campaign, User $user, Plan $plan): Subscription
    {
        if ($this->findActive($campaign)?->plan?->id === $plan->id) {
            throw new SubscriptionsCantReplacePlanToSame();
        }

        $this->end($campaign);

        return $this->add($campaign, $user, $plan);
    }

    private function create(Campaign $campaign, User $user, Plan $plan): Subscription
    {
        /** @var Subscription $sub */
        $sub = $campaign->subscriptions()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::noCharged,
            'start_at' => now(),
            'last_billing_at' => null,
        ]);

        return $sub;
    }

    /**
     * @throws SubscriptionAlreadyExistsException
     */
    public function throwIfHas(Campaign $campaign): void
    {
        if ($this->has($campaign)) {
            throw new SubscriptionAlreadyExistsException('Подписка уже существует.');
        }
    }

    public function has(Campaign $campaign): bool
    {
        return $campaign->activeSubscription()->exists();
    }

    private function findActive(Campaign $campaign): ?Subscription
    {
        /** @var Subscription $sub */
        $sub = $campaign->activeSubscription()->first();
        return $sub;
    }

    /**
     * @throws SubscriptionNotExistsException
     */
    private function findActiveOrThrow(Campaign $campaign): Subscription
    {
        $sub = $this->findActive($campaign);
        if (is_null($sub)) {
            throw new SubscriptionNotExistsException("Подписка ещё не оформлена.");
        }
        return $sub;
    }

    /**
     * @throws SubscriptionNotExistsException
     */
    private function getSubscriptionOrThrow(Subscription|Campaign $model): Subscription
    {
        if ($model instanceof Subscription) {
            return $model;
        }

        return $this->findActiveOrThrow($model);
    }

    private function getSubscription(Subscription|Campaign $model): ?Subscription
    {
        if ($model instanceof Subscription) {
            return $model;
        }

        return $this->findActive($model);
    }
}
