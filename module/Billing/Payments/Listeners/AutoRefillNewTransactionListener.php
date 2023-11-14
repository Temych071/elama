<?php

declare(strict_types=1);

namespace Module\Billing\Payments\Listeners;

use App\Listeners\AbstractTransactionEventListener;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Module\Billing\Account\Models\Transaction;
use Module\Billing\Payments\Actions\DispatchAutoRefillAction;

final class AutoRefillNewTransactionListener extends AbstractTransactionEventListener
{
    private const JOB_DELAY = 60;

    public function handler(Transaction $transaction): void
    {
        if ($transaction->amount < 0) {
            RateLimiter::attempt(self::getLimiterKey($transaction), 1, static function () use ($transaction) {
                app(DispatchAutoRefillAction::class)
                    ->execute($transaction->user, self::JOB_DELAY);
            }, self::JOB_DELAY);
        }
    }

    public static function getLimiterKey(Transaction $transaction): string
    {
        return "user.$transaction->user_id.billing.auto-refill.dispatch";
    }
}
