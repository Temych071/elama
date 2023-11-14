<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Auth;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Module\Source\Sources\Actions\AddSourceAction;
use Module\Source\Sources\Actions\RefreshAuthTokenAction;
use Module\Source\Sources\Dto\AddAuthTokenDto;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class SourceAuthCallbackController
{
    public function __invoke(Request $request, AddSourceAction $addSourceAction): RedirectResponse
    {
        $campaignId = $request->session()->pull(AddSourceController::SESSION_CAMPAIGN_KEY);
        $sourceType = $request->session()->pull(AddSourceController::SESSION_TYPE_KEY);

        abort_if(empty($campaignId) || empty($sourceType), 404);

        $driver = config("sources.list.$sourceType")['driver'];

        $socialUser = Socialite::driver($driver)->stateless()->user();
        $authToken = AddAuthTokenDto::fromSocialite($driver, $socialUser);

        if (app(RefreshAuthTokenAction::class)->execute($campaignId, $sourceType, $authToken)) {
            return redirect()->route('campaign.source', $campaignId)
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Обновление доступа к источнику прошло успешно',
                ]);
        }

        $addSourceAction->execute($campaignId, $sourceType, $authToken);

        return redirect()->route("campaign.source.settings.$sourceType.show", [
            'campaign' => $campaignId,
        ]);
    }
}
