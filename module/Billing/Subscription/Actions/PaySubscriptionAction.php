<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;
use Module\Billing\Payments\Actions\CheckUserAutoRefillLimitAction;
use Module\Billing\Payments\Actions\DispatchAutoRefillAction;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Billing\Subscription\Exceptions\SubscriptionAlreadyExistsException;
use Module\Billing\Subscription\Exceptions\SubscriptionNotExistsException;
use Module\Billing\Subscription\Models\Subscription;
use Module\Billing\Subscription\Services\SubscriptionService;
use Throwable;
use Module\User\Models\User;

final class PaySubscriptionAction
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
        private readonly CheckUserAutoRefillLimitAction $checkUserAutoRefillLimitAction,
        private readonly CheckPlanLimitsAction $checkPlanLimitsAction,
        private readonly DispatchAutoRefillAction $dispatchAutoRefillAction,
    ) {
    }

    /**
     * @return array{paused: int, total: int}
     * @throws Throwable
     */
    #[ArrayShape(['total' => "string", 'paused' => "string"])]
    public function execute(): array
    {
        $count = [
            'total' => 0,
            'paused' => 0,
        ];

        $users = [];

        Subscription::query()
            ->with(['plan', 'user'])
            ->where('status', SubscriptionStatus::active)
            ->whereDate('last_billing_at', '<', Carbon::now()->subDay())
            ->has('campaign') // Эм... А разве может не быть?) (мб на случай удаление проекта, чтобы дальше не списывалось)
            ->chunkById(10, function ($subscriptions) use (&$count, &$users): void {
                foreach ($subscriptions as $subscription) {
                    $sub = $this->checkPlanLimitsAction->execute($subscription);
                    $count['total']++;
                    if (!$this->creditSubscriptionCharge($sub)) {
                        $count['paused']++;
                    }

                    Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/subs-debug.log'),
                    ])->info('PaySubscriptionAction@execute', [
                        '$subscription' => $subscription,
                        '$sub' => $sub,
                    ]);

                    $users[$sub->user->id] = $sub->user;
//                    dump($sub->user->id, $sub->user);
                }
            });

//        dump($users);
        foreach ($users as $user) {
            if ($this->checkUserAutoRefillLimitAction->execute($user)) {
//                dump($user);
                $this->dispatchAutoRefillAction->execute($user);
            }
        }

        return $count;
    }

    /**
     * @throws Throwable
     * @throws SubscriptionNotExistsException
     */
    private function creditSubscriptionCharge(Subscription $subscription): bool
    {
        return $this->subscriptionService->charge($subscription);
    }
}
