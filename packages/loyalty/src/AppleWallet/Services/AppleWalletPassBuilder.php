<?php

declare(strict_types=1);

namespace Loyalty\AppleWallet\Services;

use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyCard;
use PKPass\PKPass;

final class AppleWalletPassBuilder
{
    protected string $certPath;
    protected string $certPass;
    protected string $teamIdentifier;
    protected string $passTypeIdentifier;

    public function __construct()
    {
        $this->certPath = base_path(config('services.apple.wallet.cert_path'));
        $this->certPass = config('services.apple.wallet.cert_pass');
        $this->teamIdentifier = config('services.apple.wallet.team_identifier');
        $this->passTypeIdentifier = config('services.apple.wallet.pass_type_identifier');
    }

    protected function makeBasePass(): PKPass
    {
        return new PkPass($this->certPath, $this->certPass);
    }

    protected function makeBaseData(): array
    {
        // https://developer.apple.com/library/archive/documentation/UserExperience/Reference/PassKit_Bundle/Chapters/TopLevel.html#//apple_ref/doc/uid/TP40012026-CH2-SW1

        return [
            'formatVersion' => 1,
            'teamIdentifier' => $this->teamIdentifier,
            'passTypeIdentifier' => $this->passTypeIdentifier,
            'organizationName' => 'DailyGrow', // Организация, подписавшая карту
        ];
    }

    public function fromLoyalty(LoyaltyCard $loyaltyCard): string
    {
        $cardSettings = $loyaltyCard->loyalty->card_settings;
        $formSettings = $loyaltyCard->loyalty->form_settings;

        // Обновление карты:
        // https://developer.apple.com/library/archive/documentation/PassKit/Reference/PassKit_WebService/WebService.html#//apple_ref/doc/uid/TP40011988

        $pass = $this->makeBasePass();
        $pass->setData([
            'serialNumber' => $this->makeSerialNumber($loyaltyCard),
            'description' => $loyaltyCard->loyalty->name,

            'backgroundColor' => $this->hexToRgb($cardSettings->header_color),
            'logoText' => $formSettings->company_name,

            'barcodes' => [
                $this->makeBarcode($loyaltyCard->card_number),
            ],

            'storeCard' => [
                'primaryFields' => [
                    $this->makeField('loyalty.title', $cardSettings->desc, $cardSettings->title),
                ],
//                'headerFields' => [
//                    $this->makeField('loyalty.title', $cardSettings->desc, $cardSettings->title),
//                ],
                'secondaryFields' => $cardSettings->show_discount ? [
                    $this->makeField('loyalty.discount', 'Размер скидки', "$cardSettings->discount_percent%"),
                ] : [],
                'auxiliaryFields' => $cardSettings->show_name ? [
                    $this->makeField('loyalty.name', 'Имя', $loyaltyCard->form->name),
                ] : [],
            ],

            ...$this->makeBaseData(),
        ]);

        if (!empty($loyaltyCard->loyalty->card_banner_path)) {
            $pass->addFile(
                $loyaltyCard->loyalty->getMediaStorage()->path($loyaltyCard->loyalty->card_banner_path),
                'strip.png'
            );
        }

        if (!empty($loyaltyCard->loyalty->card_logo_url)) {
            $pass->addFile(
                $loyaltyCard->loyalty->getMediaStorage()->path($loyaltyCard->loyalty->card_logo_path),
                'icon.png'
            );
            $pass->addFile(
                $loyaltyCard->loyalty->getMediaStorage()->path($loyaltyCard->loyalty->card_logo_path),
                'logo.png'
            );
        } else {
            $pass->addFile(public_path('/images/loyalty/default-logo.png'), 'icon.png');
            $pass->addFile(public_path('/images/loyalty/default-logo.png'), 'logo.png');
        }

        return $pass->create();
    }

    /**
     * @param string $key
     * @param string $label
     * @param string $value
     * @return array{key: string, label: string, value: string}
     */
    protected function makeField(string $key, string $label, string $value): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'value' => $value,
        ];
    }

    protected function makeSerialNumber(LoyaltyCard $loyaltyCard): string
    {
        return "$loyaltyCard->loyalty_id.$loyaltyCard->card_number";
    }

    protected function makeBarcode(string $message): array
    {
        return [
            'altText' => $message,
            'format' => 'PKBarcodeFormatCode128',
            'message' => $message,
            'messageEncoding' => 'iso-8859-1',
        ];
    }

    protected function hexToRgb(string $hex): string
    {
        return 'rgb(' . implode(',', sscanf($hex, "#%02x%02x%02x")) . ')';
    }
}
