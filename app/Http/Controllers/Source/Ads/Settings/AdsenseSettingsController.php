<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Ads\Settings;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Module\Source\Adsense\Services\AdsenseService;

final class AdsenseSettingsController
{
    public function show(Campaign $campaign): Response
    {
        $token = $campaign->googleAdsSource->authToken;

        $service = new AdsenseService($token->refresh_token);
        $accountIds = $service->getAccessibleCustomerIds();

        return Inertia::render('Sources/Settings/AdsenseSettings', [
            'available_accounts' => $accountIds,
        ]);
    }

    public function store(): void
    {
        // todo
    }

    public function campaigns(Request $request, Campaign $campaign): array
    {
        $request->validate(['account' => 'required|integer']);
        $account = (int)$request->get('account');
        $token = $campaign->googleAdsSource->authToken;
        return (new AdsenseService($token->refresh_token))
            ->getCampaigns($account);
    }
}
