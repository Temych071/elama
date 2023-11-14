<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Module\User\Enums\UserRole;
use Module\User\Models\User;

final class EnableDebugBarMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
//        $user = $request->user();

//        if ($user?->role === UserRole::admin) {
//            Debugbar::enable();
//        } else {
//            Debugbar::disable();
//        }

        return $next($request);
    }
}
