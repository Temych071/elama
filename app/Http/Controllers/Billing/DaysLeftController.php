<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use Module\Billing\Subscription\Actions\CalcDaysLeftAction;
use Illuminate\Http\Request;
use Module\User\Models\User;

final class DaysLeftController
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return app(CalcDaysLeftAction::class)->execute($user);
    }
}
