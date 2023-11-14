<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Billing\DiscountCodes;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Billing\Payments\Models\DiscountCode;

final class StoreDiscountCodesController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:32', 'unique:billing_discount_codes,code'],
            'amount' => ['exclude_unless:percent,null', 'required', 'numeric'],
            'percent' => ['exclude_unless:amount,null', 'required', 'numeric', 'min:1', 'max:100'],
            'is_one_time' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['amount'])) {
            $data['amount'] = ((int)$data['amount']) * 1000;
        }

        DiscountCode::query()->create($data);

        return redirect()->route('admin.discount-codes.list');
    }
}
