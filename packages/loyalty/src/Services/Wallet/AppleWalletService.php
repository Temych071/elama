<?php

declare(strict_types=1);

namespace Loyalty\Services\Wallet;

use Loyalty\Contracts\WalletService;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyCard;

final class AppleWalletService implements WalletService
{
    /**
     * @inheritDoc
     */
    public function createOrUpdateWalletClass(Loyalty $loyalty): void
    {
        // Эплу ничего отправлять не надо
        // UPD: Или всё же надо) https://developer.apple.com/library/archive/documentation/UserExperience/Conceptual/PassKit_PG/Updating.html#//apple_ref/doc/uid/TP40012195-CH5-SW1
    }

    /**
     * @inheritDoc
     */
    public function createWalletCard(LoyaltyCard $loyaltyCard): void
    {
        // Эплу ничего отправлять не надо
    }

    /**
     * @inheritDoc
     */
    public function getWalletCardLink(LoyaltyCard $loyaltyCard): string
    {
        return '';
    }
}
