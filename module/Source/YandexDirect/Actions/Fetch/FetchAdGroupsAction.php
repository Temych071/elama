<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions\Fetch;

use JetBrains\PhpStorm\NoReturn;
use Module\Campaign\Models\Campaign;
use Module\Source\YandexDirect\Models\YandexDirectSettings;
use Module\Source\YandexDirect\Services\YandexDirectService;

final class FetchAdGroupsAction
{
    private const FIELDS_LIST = [
        'Id' => 'ad_group_id',
        'CampaignId' => 'campaign_id',
        'Name' => 'name',
        'Type' => 'type',
        'Status' => 'status',
    ];

    #[NoReturn]
    public function execute(Campaign $campaign): void
    {
        $service = new YandexDirectService($campaign->yandexDirectSource->authToken);

        /** @var YandexDirectSettings $settings */
        $settings = $campaign->yandexDirectSource->settings;

        $CampaignIds = $settings
            ->directCampaigns()
            ->get()
            ->map(static fn ($item) => $item->campaign_id)
            ->toArray();
        $chunks = array_chunk($CampaignIds, 10);

        $total = [];
        $adGroupIds = [];
        $now = now();
        foreach ($chunks as $chunk) {
            $res = $service->getAdGroups(
                criteria: ['CampaignIds' => $chunk],
                params: YandexDirectService::AD_GROUP_TYPE_FIELDS_LIST,
            );

            foreach ($res as $adGroup) {
                foreach (YandexDirectService::AD_GROUP_TYPE_FIELDS_KEY_LIST as $key) {
                    if (isset($adGroup[$key])) {
                        /** @noinspection SlowArrayOperationsInLoopInspection */
                        $adGroup = array_merge($adGroup, $adGroup[$key]);
                        unset($adGroup[$key]);
                    }
                }

                $importantFields = [
                    'settings_id' => $settings->id,
                    'updated_at' => $now,
                    'created_at' => $now,
                ];

                foreach (self::FIELDS_LIST as $field => $key) {
                    $importantFields[$key] = $adGroup[$field] ?? null;
                    if (isset($adGroup[$field])) {
                        unset($adGroup[$field]);
                    }
                }
                $importantFields['other'] = json_encode($adGroup, JSON_THROW_ON_ERROR);

                $total[] = $importantFields;
                $adGroupIds[] = $importantFields['ad_group_id'];
            }
        }

        $settings->directAdGroups()
            ->where('settings_id', $settings->id)
            ->whereNotIn('ad_group_id', $adGroupIds)
            ->forceDelete();

        $chunks = array_chunk($total, 500);
        foreach ($chunks as $chunk) {
            $settings->directAdGroups()->upsert(
                values: $chunk,
                uniqueBy: ['settings_id', 'ad_group_id'],
                update: [
                    'type',
                    'subtype',
                    'status',

                    'other',
                    'updated_at',
                ],
            );
        }
    }
}
