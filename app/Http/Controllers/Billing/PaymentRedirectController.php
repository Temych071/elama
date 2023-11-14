<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class PaymentRedirectController
{
    public function success(): RedirectResponse
    {
        return redirect()->route("user.billing.new-payment.show")->with('toast', [
            'type' => 'success',
            'message' => 'Оплата прошла успешно.',
        ]);
    }

    public function fail(): RedirectResponse
    {
        return redirect()->route("user.billing.new-payment.show")->with('toast', [
            'type' => 'error',
            'message' => 'Оплата не была произведена...',
        ]);
    }
}
