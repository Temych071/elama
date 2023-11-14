<?php

declare(strict_types=1);

namespace Loyalty\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Loyalty\Enums\LoyaltyCardTransactionType;

final class LoyaltyCardTransactionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'transactions' => ['required', 'array'],
            'transactions.*' => ['array'],
            'transactions.*.card_number' => ['required', 'string', 'max:255'],
            'transactions.*.date' => ['required', 'date'],
            'transactions.*.type' => ['required', new Enum(LoyaltyCardTransactionType::class)],
            'transactions.*.bonuses_amount' => ['nullable', 'numeric', 'integer'],
            'transactions.*.bonuses_left' => ['nullable', 'numeric', 'integer', 'min:0'],
            'transactions.*.cheque_cost' => ['nullable', 'numeric', 'integer', 'min:0'],
            'transactions.*.discount' => ['nullable', 'numeric', 'integer', 'min:0'],
        ];
    }
}
