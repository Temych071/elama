<?php

declare(strict_types=1);

namespace Loyalty\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyClient;

final class LoyaltyClientFormFilled
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var LoyaltyClient $loyaltyClient */
        $loyaltyClient = $request->user('loyalty');

        /** @var Loyalty $loyalty */
        $loyalty = $request->route('publicLoyalty');

        if (!$loyaltyClient->cardForLoyalty($loyalty)->exists()) {
            return redirect()->route('loyalty.public.form.show', $loyalty->id);
        }

        return $next($request);
    }
}
