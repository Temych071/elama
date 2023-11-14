<?php

declare(strict_types=1);

namespace Module\Billing\Account\Services;

use App\Exceptions\BusinessException;
use Error;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Account\Models\Transaction;
use Module\Billing\Events\NewTransactionEvent;
use Module\Billing\Payments\Models\Payment;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

final class TransactionsService
{
    /**
     * @throws BusinessException
     */
    public function debit(
        User $user,
        int $amount,
        TransactionType $type,
        ?Payment $payment = null,
        ?string $desc = null,
    ): Transaction {
        if ($amount <= 0) {
            throw new Error("Invalid amount ($amount).");
        }

        if (
            !is_null($payment)
            && $payment->transaction()->exists()
        ) {
            throw new BusinessException("Payment '$payment->payment_id' already has transaction.");
        }

        /** @var Transaction $transaction */
        $transaction = $user->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'payment_id' => $payment?->id ?? null,
            'description' => $desc,
        ]);

        event(new NewTransactionEvent($transaction));

        return $transaction;
    }

    public function credit(
        User $user,
        int $amount,
        TransactionType $type,
        ?Campaign $campaign = null,
        ?string $desc = null,
    ): Transaction {
        if ($amount <= 0) {
            throw new Error("Invalid amount ($amount).");
        }

        /** @var Transaction $transaction */
        $transaction = $user->transactions()->create([
            'type' => $type,
            'amount' => -$amount,
            'campaign_id' => $campaign?->id ?? null,
            'description' => $desc,
        ]);

        event(new NewTransactionEvent($transaction));

        return $transaction;
    }

    public function balance(User $user): int
    {
        return (int)$user->transactions()->sum('amount');
    }
}
