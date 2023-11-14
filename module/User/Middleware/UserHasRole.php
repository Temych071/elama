<?php

declare(strict_types=1);

namespace Module\User\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Module\User\Enums\UserRole;
use Module\User\Models\User;

final class UserHasRole
{
    /**
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next, string $userRole)
    {
        $role = UserRole::from($userRole);

        /** @var User $user */
        $user = $request->user();

        print_r($user);
        print_r($role);
        return;
        if ($user->role === $role || $user->role === UserRole::admin) {
            return $next($request);
        }

        throw new AuthorizationException();
    }
}
