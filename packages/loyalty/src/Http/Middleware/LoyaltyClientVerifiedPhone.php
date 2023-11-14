<?php

declare(strict_types=1);

namespace Loyalty\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Loyalty\Models\LoyaltyClient;

final class LoyaltyClientVerifiedPhone
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var LoyaltyClient $loyaltyClient */
        $loyaltyClient = $request->user('loyalty');
        if (empty($loyaltyClient?->phone_verified_at)) {
            abort(403);
        }

        return $next($request);
    }
}
