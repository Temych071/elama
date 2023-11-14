<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyCard;

final class DownloadCardsApiController
{
    public function __invoke(Request $request, Loyalty $apiLoyalty): JsonResponse
    {
        $cards = $apiLoyalty->cards()
            ->whereNull('synced_at')
            ->whereHas('form')
//            ->select(['card_number'])
            ->with(['form', 'client'])
            ->get()
            ->transform(static fn(LoyaltyCard $loyaltyCard) => [
                'card_number' => $loyaltyCard->card_number,
                'phone' => $loyaltyCard->client->phone,
                ...$loyaltyCard->form->only([
                    'name',
                    'surname',
                    'email',
                    'birthday',
                    'gender',
                    'sms_notifications',
                    'email_notifications',
                    'terms_accepted',
                ]),
                ...($loyaltyCard->form->custom_fields ?? []),
            ])
            ->all();

        return new JsonResponse([
            'cards' => $cards,
        ]);
    }
}
