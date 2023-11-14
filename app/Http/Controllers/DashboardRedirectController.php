<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardRedirectController
{
    public function __invoke(): RedirectResponse
    {
        
        $user = Auth::user();
        foreach ($user?->campaigns ?? [] as $campaign) {
            if ($campaign->sources->count() > 0) {
                return redirect()->route('campaign.index');
            }
        }

        return redirect()->route('help');
    }
}
