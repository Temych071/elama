<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Billing\DiscountCodes;

use Inertia\Inertia;
use Inertia\Response;
use Module\Billing\Payments\Models\DiscountCode;

final class EditDiscountCodeController
{
    public function __invoke(string $code_id): Response
    {
        return Inertia::render('Admin/Billing/DiscountCode/Edit', [
            'code' => DiscountCode::query()->findOrFail($code_id),
        ]);
    }
}
