<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Vk\Settings;

use Illuminate\Http\Request;
use Module\Campaign\Models\Campaign;
use Module\Source\Vk\Actions\Settings\FetchActiveCampaignsListAction;
use Module\Source\Vk\Actions\Settings\FetchClientsListAction;

final class VkSettingsController
{
    public function getClientList(Request $request, Campaign $campaign): array
    {
        $data = $request->validate([
            'account' => ['required', 'integer'],
        ]);

        return app(FetchClientsListAction::class)->execute(
            $campaign->vkSource,
            (int)$data['account'],
        );
    }

    public function getCampaignList(Request $request, Campaign $campaign): array
    {
        $data = $request->validate([
            'account' => ['required', 'integer'],
            'client' => ['nullable', 'integer'],
        ]);

        return app(FetchActiveCampaignsListAction::class)->execute(
            $campaign->vkSource,
            (int)$data['account'],
            empty($data['client']) ? null : (int)$data['client'],
        );
    }
}
