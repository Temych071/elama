<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions\Fetch;

use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Models\YandexDirectSettings;
use Module\Source\YandexDirect\Services\YandexDirectService;

final class FetchCampaignsAction
{
    private const CAMPAIGN_FIELDS_LIST = [
        'Id' => 'campaign_id',
        'Name' => 'name',
        'Type' => 'type',
        'State' => 'state',
        'Status' => 'status',
    ];

    public function execute(Campaign $campaign): void
    {
        /** @var Source $source */
        $source = $campaign->yandexDirectSource;

        /** @var YandexDirectSettings $settings */
        $settings = $source->settings;

//        $directCampaignIds = array_map(static fn($item) => $item['Id'], $settings->campaigns->toArray());

        $service = new YandexDirectService($source->authToken);

        $res = $service->getCampaigns(
            criteria: [
                // Все, кроме архивных
                'States' => ['CONVERTED', 'ENDED', 'OFF', 'ON', 'SUSPENDED'],
            ],
            params: YandexDirectService::CAMPAIGN_TYPE_FIELDS_LIST,
        );
        $campaignIds = [];
        $now = now();
        $res = array_map(static function ($item) use ($settings, &$campaignIds, $now): array {
            $key = YandexDirectService::CAMPAIGN_TYPE_FIELDS_KEY[$item['Type']];
            if (!is_null($key) && !empty($item[$key])) {
                $item = array_merge($item, $item[$key]);
                unset($item[$key]);
            }

            $importantFields = [
                'settings_id' => $settings->id,
                'updated_at' => $now,
                'created_at' => $now,
            ];

            foreach (self::CAMPAIGN_FIELDS_LIST as $field => $dbCol) {
                $importantFields[$dbCol] = $item[$field];
                unset($item[$field]);
            }
            $importantFields['other'] = json_encode($item, JSON_THROW_ON_ERROR);

            $campaignIds[] = $importantFields['campaign_id'];
            return $importantFields;
        }, $res);

        $settings
            ->directCampaigns()
            ->whereNotIn('campaign_id', $campaignIds)
            ->forceDelete();

        $settings
            ->directCampaigns()
            ->upsert(
                values: $res,
                uniqueBy: ['settings_id', 'campaign_id'],
                update: [
                    'name',
                    'type',
                    'status',
                    'state',
                    'other',

                    'updated_at',
                ],
            );
    }
}
