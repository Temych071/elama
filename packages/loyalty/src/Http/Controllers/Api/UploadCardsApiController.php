<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Loyalty\Exceptions\LoyaltyApiError;
use Loyalty\Http\Requests\LoyaltyCardsRequest;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyCard;
use Loyalty\Models\LoyaltyClient;

final class UploadCardsApiController
{
    public function __invoke(LoyaltyCardsRequest $request, Loyalty $apiLoyalty): JsonResponse
    {
        foreach ($request->getCardsData() as $cardData) {
            DB::transaction(static function () use ($apiLoyalty, $cardData) {
                /** @var LoyaltyCard $card */
                $card = $apiLoyalty->cards()->firstOrCreate([
                    'card_number' => $cardData['card_number'],
                ]);

                if (!empty($cardData['phone'])) {
                    /** @var LoyaltyClient $client */
                    $client = LoyaltyClient::firstOrCreate([
                        'phone' => $cardData['phone'],
                    ]);

                    $card->form()->updateOrCreate([
                        'loyalty_client_id' => $client->id,
                        'loyalty_card_id' => $card->id,
                    ], [
                        ...Arr::only($cardData, [
                            'name',
                            'surname',
                            'email',
                            'birthday',
                            'gender',
                            'sms_notifications',
                            'email_notifications',
                            'terms_accepted',
                        ]),
                        'custom_fields' => Arr::only(
                            $cardData,
                            $apiLoyalty->form_settings
                                ->custom_fields
                                ->only('key')
                                ->toArray()
                        ),
                    ]);

                    $card->synced_at = now();
                    $card->save();
                }
            });
        }

        return new JsonResponse([
            'status' => 'OK',
        ]);
    }
}
