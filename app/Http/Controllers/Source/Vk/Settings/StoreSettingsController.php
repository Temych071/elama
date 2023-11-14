<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Vk\Settings;

use App\Exceptions\BusinessException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Module\Campaign\Models\Campaign;
use Module\Source\Vk\Actions\Settings\StoreSettingsAction;
use Module\Source\Vk\Actions\Settings\StoreVkSettingsCommand;

final class StoreSettingsController
{
    /**
     * @throws BusinessException
     * @throws ValidationException
     */
    public function __invoke(Request $request, Campaign $campaign)
    {
        if ($campaign->vkSource->settings) {
            throw new BusinessException('This source has already been added.');
        }

        Validator::make(
            data: $request->all(),
            rules: [
                'account' => 'required|array',
                'client' => 'nullable',
                'campaigns' => ['nullable', 'array'],
                'key' => 'required|uuid',
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

        app(StoreSettingsAction::class)->execute(
            campaign: $campaign,
            command: StoreVkSettingsCommand::fromRequest($request),
        );

        return redirect()
            ->route('campaign.browse', $campaign)
            ->with('toast', [
                'type' => 'success',
                'message' => trans('toasts.sources.settings.vk.settingsSaved'),
            ]);
    }
}
