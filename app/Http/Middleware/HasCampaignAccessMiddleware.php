<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Module\User\Enums\UserRole;
use Module\User\Models\User;

class HasCampaignAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->role === UserRole::admin) {
            return $next($request);
        }

        $campaignId = $request->route()->originalParameter('campaign');

        $hasCampaign = $user->campaigns()->where('campaigns.id', '=', $campaignId)->exists();

        if (!$hasCampaign) {
            throw new AuthenticationException('Unauthenticated.');
        }

        return $next($request);
    }
}
