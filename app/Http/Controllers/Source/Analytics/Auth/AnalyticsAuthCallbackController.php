<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Analytics\Auth;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Source\Auth\AddSourceController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Source\GoogleAnalytics\Services\GoogleAnalyticsService;
use Module\Source\Sources\Actions\AddSourceAction;
use Module\Source\Sources\Dto\AddAuthTokenDto;
use Module\Source\Sources\Models\Source;

final class AnalyticsAuthCallbackController
{
    /**
     * @throws BusinessException
     */
    public function authCallback(Request $request): RedirectResponse
    {
        $campaignId = $request->session()->get(AddSourceController::SESSION_CAMPAIGN_KEY);

        abort_if(empty($campaignId), 404);

        $client = app(GoogleAnalyticsService::class)->client();
        $accessToken = $client->fetchAccessTokenWithAuthCode($request->get('code'));

        if (array_key_exists('error', $accessToken)) {
            throw new BusinessException('Не удалось авторизоваться', '/');
        }

        $client->setAccessToken($accessToken);

        $authToken = new AddAuthTokenDto(
            userId: null,
            driver: Source::TYPE_GOOGLE_ANALYTICS,
            token: $accessToken['access_token'],
            refreshToken: $accessToken['refresh_token'],
            expiresIn: (int)$accessToken['expires_in'],
            name: null,
            scopes: explode(' ', (string) $accessToken['scope']),
        );

        app(AddSourceAction::class)
            ->execute($campaignId, Source::TYPE_GOOGLE_ANALYTICS, $authToken);

        return redirect()->route('campaign.source.settings.google-analytics.show', [
            'campaign' => $campaignId,
        ]);
    }
}
