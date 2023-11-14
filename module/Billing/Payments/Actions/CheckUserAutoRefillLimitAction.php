<?php

declare(strict_types=1);

namespace Module\Billing\Payments\Actions;

use Module\Billing\Account\Services\TransactionsService;
use Module\Billing\Payments\Models\PaymentMethod;
use Module\User\Models\User;

final class CheckUserAutoRefillLimitAction
{
    public function execute(User $user): bool
    {
        if (!$user->auto_refill_settings?->enabled) {
            return false;
        }

        /** @var ?PaymentMethod $paymentMethod */
        $paymentMethod = PaymentMethod::query()
            ->find($user->auto_refill_settings->payment_method_id ?? null);
        $amount = $user->auto_refill_settings->amount ?? null;
        $limit = $user->auto_refill_settings->limit ?? null;

        if ($paymentMethod === null || $amount === null || $limit === null) {
            return false;
        }

        $balance = app(TransactionsService::class)->balance($user) / 1000;

        return $balance < $limit;
    }
}
