<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Loyalty\Models\Loyalty;

final class ApproveCardsApiController
{
    public function __invoke(Request $request, Loyalty $apiLoyalty): JsonResponse
    {
        $cardNumbers = $request->json()?->get('card_numbers') ?? [];
        $updated = $apiLoyalty->cards()
            ->whereIn('card_number', $cardNumbers)
            ->whereNull('synced_at')
            ->whereHas('form')
            ->update(['synced_at' => now()]);

        return new JsonResponse([
            'approved_count' => $updated,
        ]);
    }
}
