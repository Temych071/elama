<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Transactions;

use App\Exceptions\BusinessException;
use App\Exceptions\ToastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Account\Models\Transaction;
use Module\Billing\Account\Services\TransactionsService;
use Module\User\Models\User;

final class CreateTransactionController
{
    /**
     * @throws ToastException
     * @throws BusinessException
     */
    public function __invoke(Request $request, TransactionsService $service): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'type' => ['required', new Enum(TransactionType::class)],
            'formatted_amount' => ['required', 'numeric'],
        ]);

        $user = User::query()->findOrFail($data['user_id']);
        $amount = $data['formatted_amount'] * Transaction::AMOUNT_MULTIPLIER;
        $type = TransactionType::from($data['type']);

        if ($data['formatted_amount'] > 0) {
            $service->debit($user, $amount, $type);
        } elseif ($data['formatted_amount'] < 0) {
            $service->credit($user, -$amount, $type);
        } else {
            throw new ToastException('Не указана сумма транзакции.');
        }

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Транзакция добавлена.',
        ]);
    }
}
