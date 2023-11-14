<?php

declare(strict_types=1);

namespace Loyalty\Services\Wallet;

use Google\Exception;
use Loyalty\Contracts\WalletService;
use Loyalty\Exceptions\LoyaltyClassNotExistsException;
use Loyalty\GoogleWallet\Services\GoogleWalletApiService;
use Loyalty\GoogleWallet\Services\LoyaltyCardToObjectConverter;
use Loyalty\GoogleWallet\Services\LoyaltyToClassConverter;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyCard;

final class GoogleWalletService implements WalletService
{
    public function __construct(
        protected GoogleWalletApiService $service,
        protected LoyaltyToClassConverter $classConverter,
        protected LoyaltyCardToObjectConverter $objectConverter,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createOrUpdateWalletClass(Loyalty $loyalty): void
    {
        $class = $this->classConverter->convert($loyalty);
        if ($this->service->updateClass($loyalty->id, $class) === null) {
            $this->service->createClass($loyalty->id, $class, checkExists: false);
        }

        $loyalty->google_class_updated_at = now();
        $loyalty->save();
    }

    /**
     * @throws Exception
     */
    public function createWalletCard(LoyaltyCard $loyaltyCard): void
    {
        if ($this->service->getObject($loyaltyCard->loyalty_id, $loyaltyCard->card_number) === null) {
            $this->service->createObject(
                $loyaltyCard->loyalty_id,
                $loyaltyCard->card_number,
                $this->objectConverter->convert($loyaltyCard),
                false,
            );

            $loyaltyCard->google_wallet_created_at = now();
            $loyaltyCard->save();
        }
    }

    /**
     * @param  LoyaltyCard  $loyaltyCard
     * @return string
     * @throws Exception
     * @throws LoyaltyClassNotExistsException
     */
    public function getWalletCardLink(LoyaltyCard $loyaltyCard): string
    {
        $loyalty = $loyaltyCard->loyalty;

        if ($loyalty->google_class_updated_at === null) {
            throw new LoyaltyClassNotExistsException();
        }

        return $this->service->getSaveToPayLink($loyaltyCard->loyalty_id, $loyaltyCard->card_number);
    }
}
