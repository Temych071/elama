<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Vk\Settings;

use Illuminate\Support\Str;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Module\DgMarks\Services\DgMarksGenerator;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Actions\Settings\FetchAccountListAction;
use Module\Source\Vk\Actions\Settings\FetchActiveCampaignsListAction;
use Module\Source\Vk\Models\VkSettings;

final class ShowSettingsController
{
    public function __invoke(Campaign $campaign): Response
    {
        $source = $campaign->vkSource;

        if ($source->settings) {
            return $this->showUpdate($source);
        }

        return $this->showCreate($source);
    }

    private function showCreate(Source $source): Response
    {
        $accounts = app(FetchAccountListAction::class)->execute($source);

        return inertia('Sources/Settings/Vk/VkCreateSettings', [
            'source_type' => $source->settings_type,
            'accounts' => $accounts,
            'sourceKey' => Str::uuid()->toString(),
            'webhooks' => null,
            'dgMark' => app(DgMarksGenerator::class)
                ->gen($source),
        ]);
    }

    private function showUpdate(Source $source): Response
    {
        /** @var VkSettings $settings */
        $settings = $source->settings;

        $campaigns = app(FetchActiveCampaignsListAction::class)
            ->execute($source, $settings->account->account_id, $settings->client?->id);

        $account = $settings->account->account_name . ' (' . $settings->account->account_id . ')';
        $client = $settings->client ? $settings->client->name . ' (' . $settings->client->id . ')' : null;

        return inertia('Sources/Settings/Vk/VkUpdateSettings', [
            'source_type' => $source->settings_type,
            'settings' => [
                'account' => $account,
                'client' => $client,
                'selected_campaigns' => $settings?->campaigns?->toArray(),
                'key' => $settings->uuid,
                'webhooks' => $settings->webhooks,
                'is_vk_lead_forms' => $settings->is_vk_lead_forms,
                'is_vk_lead_messages' => $settings->is_vk_lead_messages,
            ],
            'available_campaigns' => $campaigns,
            'dgMark' => app(DgMarksGenerator::class)
                ->gen($source),
        ]);
    }
}
