<?php

declare(strict_types=1);

namespace App\Http\Middleware\Project;

use Illuminate\Http\Request;
use Module\Campaign\Enums\ProjectPermission;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

final class HasProjectPermission
{
    public function handle(Request $request, callable $next, ?string $permissions = null): mixed
    {
        /** @var Campaign $campaign */
        $campaign = $request->route('campaign');

        /** @var User $user */
        $user = $request->user();

        $formattedPermissions = $permissions === null
            ? null
            : array_map(
                static fn (string $item): \Module\Campaign\Enums\ProjectPermission => ProjectPermission::from(trim($item)),
                explode(',', $permissions)
            );

        $campaign->userHasAccess($user, $formattedPermissions);

        return $next($request);
    }
}
