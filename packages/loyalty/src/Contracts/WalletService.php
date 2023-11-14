<?php

namespace Loyalty\Contracts;

use Loyalty\Exceptions\LoyaltyClassNotExistsException;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyCard;

interface WalletService
{
    /**
     * Создаёт класс объектов карт лояльности в сервисе Wallet.
     *
     * @param  Loyalty  $loyalty
     * @return void
     */
    public function createOrUpdateWalletClass(Loyalty $loyalty): void;

    /**
     * Создаёт объект карты лояльности в сервисе Wallet.
     *
     * @param  LoyaltyCard  $loyaltyCard
     * @return void
     */
    public function createWalletCard(LoyaltyCard $loyaltyCard): void;

    /**
     * Возвращает ссылку для добавления карты.
     *
     * @param  LoyaltyCard  $loyaltyCard
     * @return string
     *
     * @throws LoyaltyClassNotExistsException
     */
    public function getWalletCardLink(LoyaltyCard $loyaltyCard): string;
}
