<?php

namespace Module\Billing\Payments\Actions;

use Module\Billing\Payments\Models\PaymentMethod;
use Module\User\Models\User;

use function app;

class RunAutoRefillAction
{
    public function __construct(
        private readonly CheckUserAutoRefillLimitAction $checkUserAutoRefillLimitAction,
    ) {
    }

    public function execute(User $user): bool
    {
        if ($this->checkUserAutoRefillLimitAction->execute($user)) {
            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $user->paymentMethods()
                ->findOrFail($user->auto_refill_settings->payment_method_id);

            $paymentMethod->pay($user->auto_refill_settings->amount);
            return true;
        }
        return false;
    }
}
