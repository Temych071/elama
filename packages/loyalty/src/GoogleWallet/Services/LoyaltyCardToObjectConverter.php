<?php

declare(strict_types=1);

namespace Loyalty\GoogleWallet\Services;

use Google_Service_Walletobjects_Barcode;
use Google_Service_Walletobjects_LoyaltyObject;
use Loyalty\GoogleWallet\Exceptions\CardNotAssociatedError;
use Loyalty\Models\LoyaltyCard;

final class LoyaltyCardToObjectConverter
{
    protected readonly string $issuerId;

    public function __construct()
    {
        $this->issuerId = config('services.google.pay.issuer.id');
    }

    public function convert(LoyaltyCard $card): Google_Service_Walletobjects_LoyaltyObject
    {
        if ($card->client === null) {
            throw new CardNotAssociatedError();
        }

        return new Google_Service_Walletobjects_LoyaltyObject([
            'classId' => $this->getIndex($card->loyalty_id),
            'id' => $this->getIndex($card->loyalty_id . '.' . $card->card_number),
            'state' => 'ACTIVE',
            'barcode' => new Google_Service_Walletobjects_Barcode([
                'type' => 'CODE_39',
                'value' => $card->card_number,
                'alternateText' => $card->card_number,
            ]),
        ]);
    }

    private function getIndex(string $suffix): string
    {
        return "$this->issuerId.$suffix";
    }
}
