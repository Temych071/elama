<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Billing\DiscountCodes;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Module\Billing\Payments\Models\DiscountCode;

final class UpdateDiscountCodeController
{
    public function __invoke(Request $request, string $code_id): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:32', Rule::unique('billing_discount_codes', 'code')->ignore($code_id)],
            'amount' => [Rule::requiredIf(!$request->input('percent')), 'nullable', 'numeric'],
            'percent' => [Rule::requiredIf(!$request->input('amount')), 'nullable', 'numeric', 'min:1', 'max:100'],
            'is_one_time' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['amount'])) {
            $data['amount'] = ((int)$data['amount']) * 1000;
        }

        DiscountCode::query()
            ->findOrFail($code_id)
            ?->update($data);

        return redirect()->route('admin.discount-codes.list')->with('toast', [
            'type' => 'success',
            'message' => 'Промокод успешно изменён.',
        ]);
    }
}
