<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Loyalty\Enums\LoyaltyCardTransactionType;
use Loyalty\Http\Requests\LoyaltyCardTransactionsRequest;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyCardTransaction;

final class TransactionsApiController
{
    public function __invoke(LoyaltyCardTransactionsRequest $request): JsonResponse
    {
        /** @var array<array> $transactions */
        $transactions = $request->input('transactions');

        $cardNumbers = array_values(array_unique(Arr::pluck($transactions, 'card_number')));

        /** @var Loyalty $loyalty */
        $loyalty = $request->route('apiLoyalty');

        $cardsForNumbers = $loyalty->cards()
            ->whereIn('card_number', $cardNumbers)
            ->select(['card_number', 'id', 'loyalty_id'])
            ->get()
            ->mapWithKeys(static fn($item) => [$item->card_number => $item])
            ->all();

        $fillable = (new LoyaltyCardTransaction())->getFillable();
        $transactions = array_filter(array_map(static function (array $transaction) use ($cardsForNumbers, $fillable) {
            $card = $cardsForNumbers[$transaction['card_number']] ?? null;

            if (empty($card)) {
                return null;
            }

            return [
                'loyalty_card_id' => $card->id,
                ...Arr::only($transaction, $fillable),
                'date' => Carbon::parse($transaction['date']),
                'created_at' => now(),
            ];
        }, $transactions));

        return new JsonResponse([
            'inserted' => LoyaltyCardTransaction::insert($transactions),
        ]);
    }
}

