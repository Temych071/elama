<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Billing\Invoices;

use App\Exceptions\BusinessException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Billing\Invoices\Models\Invoice;
use Throwable;

final class InvoicesConfirmController
{
    /**
     * @throws BusinessException
     * @throws Throwable
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_id' => ['required', 'numeric', 'exists:billing_invoices,id'],
            'formatted_amount' => ['required', 'numeric'],
        ]);

        /** @var Invoice $invoice */
        $invoice = Invoice::query()->findOrFail($data['invoice_id']);

        $invoice->confirm($data['formatted_amount'] * 1000);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Оплата счёта подтвержена. Средства зачислены на счёт пользователя.',
        ]);
    }
}
