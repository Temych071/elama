<?php

namespace App\Listeners;

use Module\Billing\Account\Models\Transaction;
use Module\Billing\Account\Services\TransactionsService;
use Module\Billing\Events\LowBalanceEvent;
use Module\Billing\Events\NegativeBalanceEvent;
use Module\Billing\Subscription\Models\Subscription;
use Module\Notification\Enums\NotificationStatuses;
use Module\Notification\Enums\NotificationTypes;
use Module\Notification\NotificationService;
use Module\User\Models\User;

class NewTransactionListener extends AbstractTransactionEventListener
{
    private const title = 'На ваш аккаунт в DailyGrow поступила оплата';

    private const MIN_LEFT_DAYS_NUM = 7;

    public function handler(Transaction $transaction): void
    {
        if ($transaction->amount > 0) {
            $notificationService = new NotificationService($transaction->user);
            $notificationService->sendSimpleNotification(
                NotificationTypes::PaymentReceived,
                NotificationStatuses::Necessarily,
                self::title,
                $this->makeDescription($transaction->user->name, $transaction->formattedAmount),
                withoutTextHtml: true,
                tgBoldTitle: true,
            );
        }

        $balance = app(TransactionsService::class)->balance($transaction->user);
        $this->checkBalance($transaction->user, $balance);
    }

    protected function makeDescription(string $name, mixed $info = null): string
    {
        return '<br>Здравствуйте, '.$name.'! Ваш счет пополнен на '.($info ?? 0).' ₽.';
    }

    private function checkBalance(User $user, int $balance): void
    {
        if ($balance <= 0) {
            event(new NegativeBalanceEvent(user: $user));
            return;
        }

        $sumPrice = $user->activeSubscriptions->sum(fn(Subscription $el): int => $el->plan->getPricePerDay());
        if ($sumPrice === 0) {
            return;
        }

        $days = floor($balance / $sumPrice);
        if ($days <= self::MIN_LEFT_DAYS_NUM) {
            event(new LowBalanceEvent($user, (int) $days));
        }
    }
}
