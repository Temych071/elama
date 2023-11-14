<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Vk;

use App\Exceptions\ToastException;
use App\Http\Controllers\Source\Auth\AddSourceController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Source\Sources\Actions\AddSourceAction;
use Module\Source\Sources\Actions\RefreshAuthTokenAction;
use Module\Source\Sources\Dto\AddAuthTokenDto;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Services\VkDomainException;
use Module\Source\Vk\Services\VkService;

final class VkAuthCallbackController
{
    /**
     * @throws ToastException
     * @throws VkDomainException
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $campaignId = $request->session()->get(AddSourceController::SESSION_CAMPAIGN_KEY);
        $code = $request->get('code');

        abort_if(empty($campaignId) || empty($code), 404);

        $response = app(VkService::class)->getAccessToken($code);

        $authToken = new AddAuthTokenDto(
            userId: (string)$response['user_id'],
            driver: Source::TYPE_VK,
            token: $response['access_token'],
            refreshToken: '',
            expiresIn: (int)$response['expires_in'],
            name: null,
        );

        if (app(RefreshAuthTokenAction::class)->execute($campaignId, Source::TYPE_VK, $authToken)) {
            return redirect()->route('campaign.source', $campaignId)
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Обновление доступа к источнику прошло успешно',
                ]);
        }

        app(AddSourceAction::class)
            ->execute($campaignId, Source::TYPE_VK, $authToken);

        return redirect()->route('campaign.source.settings.vk.show', [
            'campaign' => $campaignId,
        ]);
    }
}
