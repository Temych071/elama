<?php

declare(strict_types=1);

namespace Loyalty\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;
use Loyalty\Models\LoyaltyClient;

final class LoyaltyClientAuth extends Authenticate
{
    protected function authenticate($request, array $guards = ['loyalty']): void
    {
        parent::authenticate($request, ['loyalty']);
    }

    protected function redirectTo($request): ?string
    {
        return route('loyalty.public.login.show', $request->route('publicLoyalty'));
    }
}
