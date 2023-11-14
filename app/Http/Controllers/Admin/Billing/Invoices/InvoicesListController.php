<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Billing\Invoices;

use Inertia\Inertia;
use Inertia\Response;
use Module\Billing\Invoices\Models\Invoice;

final class InvoicesListController
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Billing/Invoices/List', [
            'invoices' => Invoice::query()
                ->with(['discountCode'])
                ->orderByRaw('IF(transaction_id IS NULL, 1, 0) DESC, updated_at DESC')
                ->paginate(),
        ]);
    }
}
