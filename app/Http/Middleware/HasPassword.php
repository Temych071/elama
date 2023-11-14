<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasPassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        print_r('445545533');
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->password || empty($user->phone)) {
            return redirect()->route('register-finish');
        }

        return $next($request);
    }
}
