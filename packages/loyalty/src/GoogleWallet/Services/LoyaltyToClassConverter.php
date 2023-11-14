<?php

declare(strict_types=1);

namespace Loyalty\GoogleWallet\Services;

use Google_Service_Walletobjects_Image;
use Google_Service_Walletobjects_ImageUri;
use Google_Service_Walletobjects_LocalizedString;
use Google_Service_Walletobjects_LoyaltyClass;
use Google_Service_Walletobjects_TextModuleData;
use Google_Service_Walletobjects_TranslatedString;
use Google_Service_Walletobjects_Uri;
use Loyalty\Models\Loyalty;

final class LoyaltyToClassConverter
{
    protected readonly string $issuerId;

    public function __construct()
    {
        $this->issuerId = config('services.google.pay.issuer.id');
    }

    public function convert(Loyalty $loyalty): Google_Service_Walletobjects_LoyaltyClass
    {
        // https://developers.google.com/wallet/retail/loyalty-cards/rest/v1/loyaltyclass
        $data = [
            'id' => $this->getClassIndex($loyalty->id),

            'programName' => $loyalty->card_settings->title,
            'issuerName' => $loyalty->form_settings->company_name,
            'hexBackgroundColor' => $loyalty->card_settings->header_color,
            'homepageUri' => $this->makeUri('Веб-версия', route('loyalty.public.login.show', $loyalty->id)),

            'textModulesData' => [
                new Google_Service_Walletobjects_TextModuleData([
                    'header' => $loyalty->card_settings->title,
                    'body' => $loyalty->card_settings->desc,
                    'id' => 'main-text',
                ]),
            ],

            // TODO: Посмотреть что ещё можно прокинуть

            'reviewStatus' => 'UNDER_REVIEW',
            'countryCode' => 'RU',
        ];

        if (!empty($loyalty->card_banner_url)) {
            $data['heroImage'] = $this->makeImage($loyalty->card_banner_url, 'Баннер');
        }

        if (!empty($loyalty->card_logo_url)) {
            $data['programLogo'] = $this->makeImage($loyalty->card_logo_url, 'Логотип');
        } else {
            $data['programLogo'] = $this->makeImage(url('/images/loyalty/default-logo.png'), 'Логотип');
        }

        return new Google_Service_Walletobjects_LoyaltyClass($data);
    }

    private function makeImage(string $sourceUri, string $contentDescription): Google_Service_Walletobjects_Image
    {
        return new Google_Service_Walletobjects_Image([
            'sourceUri' => new Google_Service_Walletobjects_ImageUri([
                'uri' => $sourceUri,
            ]),
            'contentDescription' => $this->makeLocString($contentDescription),
        ]);
    }

    private function makeLocString(string $value): Google_Service_Walletobjects_LocalizedString
    {
        return new Google_Service_Walletobjects_LocalizedString([
            'defaultValue' => new Google_Service_Walletobjects_TranslatedString([
                'language' => 'ru-RU',
                'value' => $value,
            ]),
        ]);
    }

    private function makeUri(string $desc, string $uri): Google_Service_Walletobjects_Uri
    {
        return new Google_Service_Walletobjects_Uri([
            'uri' => $uri,
            'description' => $desc,
        ]);
    }

    private function getClassIndex(string $classId): string
    {
        return "$this->issuerId.$classId";
    }
}
