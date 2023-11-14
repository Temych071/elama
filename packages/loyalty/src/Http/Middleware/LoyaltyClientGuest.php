<?php

declare(strict_types=1);

namespace Loyalty\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LoyaltyClientGuest
{
    /**
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::guard('loyalty')->check()) {
            throw new AuthenticationException(
                'Authenticated.',
                ['loyalty'],
                route('loyalty.public.card.show', $request->route('publicLoyalty'))
            );
        }

        return $next($request);
    }
}
