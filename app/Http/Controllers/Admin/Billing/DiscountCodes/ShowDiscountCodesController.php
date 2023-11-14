<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Billing\DiscountCodes;

use Module\Billing\Payments\Models\DiscountCode;

final class ShowDiscountCodesController
{
    public function __invoke()
    {
        $codes = DiscountCode::query()
            ->orderByDesc('created_at')
            ->get();

        return inertia('Admin/Billing/DiscountCode/List', [
            'codes' => $codes,
        ]);
    }
}
