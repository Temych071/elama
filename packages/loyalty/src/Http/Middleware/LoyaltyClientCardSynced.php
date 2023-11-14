<?php

declare(strict_types=1);

namespace Loyalty\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyClient;

final class LoyaltyClientCardSynced
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var LoyaltyClient $loyaltyClient */
        $loyaltyClient = $request->user('loyalty');

        /** @var Loyalty $loyalty */
        $loyalty = $request->route('publicLoyalty');

        if (!$loyaltyClient->cardForLoyalty($loyalty)->whereNotNull('synced_at')->exists()) {
            // TODO: Наверное надо пускать, но писать что карта неактивна
            abort(403, 'Карта лояльности ещё не активирована...');
        }

        return $next($request);
    }
}
