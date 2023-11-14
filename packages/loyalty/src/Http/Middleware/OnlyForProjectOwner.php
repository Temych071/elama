<?php

declare(strict_types=1);

namespace Loyalty\Http\Middleware;

use App\Exceptions\ToastException;
use Closure;
use Illuminate\Http\Request;
use Loyalty\Models\Loyalty;
use Module\User\Enums\UserRole;
use Module\User\Models\User;

final class OnlyForProjectOwner
{
    /**
     * @throws ToastException
     */
    public function handle(Request $request, Closure $next): mixed
    {

        /** @var User $user */
        $user = $request->user();

        if ($user->role === UserRole::admin) {
            return $next($request);
        }

        /** @var Loyalty $loyalty */
        $loyalty = $request->route('loyalty');

        if ($loyalty->project->owner->where('id', $user?->id)->count() <= 0) {
            throw new ToastException('Эта страница только для владельцев проекта.');
        }

        return $next($request);
    }
}
