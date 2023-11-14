<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Public;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LoyaltyClientLogout
{
    public function __invoke(Request $request): RedirectResponse
    {
        Auth::guard('loyalty')->logout();

        return redirect()->route('loyalty.public.login.show', $request->route('publicLoyalty'));
    }
}
