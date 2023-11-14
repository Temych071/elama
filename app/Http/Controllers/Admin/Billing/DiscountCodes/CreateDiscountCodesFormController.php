<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Billing\DiscountCodes;

use Illuminate\Http\Request;

final class CreateDiscountCodesFormController
{
    public function __invoke(Request $request)
    {
        return inertia('Admin/Billing/DiscountCode/Edit');
    }
}
