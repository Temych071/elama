<?php

namespace App\Listeners;

use App\Events\RegistrationFinishedEvent;
use App\Exceptions\BusinessException;
use Module\Admin\Settings\BillingSettings;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Account\Services\TransactionsService;
use Module\Billing\Subscription\Exceptions\SubscriptionAlreadyExistsException;
use Module\Billing\Subscription\Exceptions\SubscriptionNotExistsException;
use Module\Campaign\Actions\CreateCampaignAction;
use Throwable;

class UserRegisteredListener
{
    final public const DEFAULT_CAMPAIGN_NAME = 'Новый проект';

    /**
     * @throws Throwable
     * @throws SubscriptionAlreadyExistsException
     * @throws SubscriptionNotExistsException
     * @throws BusinessException
     */
    public function handle(RegistrationFinishedEvent $event): void
    {
        // Стартовый баланс
        app(TransactionsService::class)->debit(
            $event->user,
            app(BillingSettings::class)->trial_balance,
            TransactionType::TRIAL_BALANCE,
        );

        // Порядок важен!
        // Иначе после реги подписка сразу станет неоплаченной

        // Создание стартового проекта
        app(CreateCampaignAction::class)->execute(
            user: $event->user,
            name: self::DEFAULT_CAMPAIGN_NAME,
            plan: null,
        );
    }
}
