<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Transactions;

use App\Http\Controllers\Controller;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Account\Models\Transaction;
use Module\User\Models\User;

class ShowTransactionListController extends Controller
{
    public function __invoke()
    {
        $transactions = Transaction::query()
            ->with(['user' => fn ($query) => $query->withTrashed()])
            ->orderByDesc('id')
            ->simplePaginate();

        return inertia('Admin/Transactions/TransactionList', [
            'transactions' => $transactions,
            'transactionTypes' => TransactionType::cases(),
            'users' => User::query()->select(['email', 'name', 'id'])->orderBy('name')->get(),
        ]);
    }
}
