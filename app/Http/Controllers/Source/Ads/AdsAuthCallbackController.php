<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Ads;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Source\Auth\AddSourceController;
use Illuminate\Http\Request;
use Module\Source\Adsense\Services\AdsenseAuthService;
use Module\Source\Sources\Actions\AddSourceAction;
use Module\Source\Sources\Models\Source;

final class AdsAuthCallbackController
{
    /**
     * @throws BusinessException
     */
    public function __invoke(Request $request, AdsenseAuthService $auth)
    {
        $campaignId = $request->session()->get(AddSourceController::SESSION_CAMPAIGN_KEY);

        abort_if(empty($campaignId), 404);

        $authToken = $auth->fetchAuthToken($request->get('code'));

        app(AddSourceAction::class)
            ->execute($campaignId, Source::TYPE_GOOGLE_ADS, $authToken);

        return redirect()->route('campaign.source.settings.google-ads.show', [
            'campaign' => $campaignId,
        ]);
    }
}
