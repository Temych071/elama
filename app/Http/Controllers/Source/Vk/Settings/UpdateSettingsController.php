<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Vk\Settings;

use App\Exceptions\BusinessException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Module\Campaign\Models\Campaign;
use Module\Source\Vk\Data\CampaignData;
use Module\Source\Vk\Data\WebhookData;

final class UpdateSettingsController
{
    /**
     * @throws BusinessException
     */
    public function __invoke(Request $request, Campaign $campaign): RedirectResponse
    {
        $source = $campaign->vkSource;

        if (!$source->settings) {
            throw new BusinessException('Can\'t update this settings.');
        }

        $inputs = Validator::make(
            data: $request->all(),
            rules: [
                'campaigns' => ['nullable', 'array'],
                'webhooks' => 'nullable|array|min:1',
                'webhooks.*.group_id' => 'required|integer',
                'webhooks.*.code' => 'required|string',
                'is_vk_lead_messages' => 'nullable|bool',
                'is_vk_lead_forms' => 'nullable|bool',
            ],
            customAttributes: [
                'webhooks' => 'группа',
                'webhooks.*.group_id' => '"id группы"',
                'webhooks.*.code' => '"строка подтверждения"',
            ],
        )->validate();

        $campaigns = $inputs['campaigns'];
        $webhooks = $inputs['webhooks'];

        $source->settings->update([
            'campaigns' => $campaigns ? CampaignData::collection($campaigns) : null,
            'webhooks' => empty($webhooks) ? null : WebhookData::collection($webhooks),
            'is_vk_lead_messages' => filled($inputs['is_vk_lead_messages']) ? $inputs['is_vk_lead_messages'] : false,
            'is_vk_lead_forms' => filled($inputs['is_vk_lead_forms']) ? $inputs['is_vk_lead_forms'] : true,
        ]);

        return redirect()->route('campaign.browse', $campaign)
            ->with('toast', [
                'type' => 'success',
                'message' => trans('toasts.sources.settings.vk.settingsSaved'),
            ]);
    }
}
