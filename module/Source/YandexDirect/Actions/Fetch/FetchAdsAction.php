<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions\Fetch;

use Illuminate\Support\Str;
use Module\Campaign\Models\Campaign;
use Module\Source\YandexDirect\Models\DirectAd;
use Module\Source\YandexDirect\Services\YandexDirectService;

final class FetchAdsAction
{
    private const AD_FIELDS_LIST = [
        'Id' => [
            'key' => 'ad_id',
        ],
        'CampaignId' => [
            'key' => 'campaign_id',
        ],
        'AdGroupId' => [
            'key' => 'ad_group_id',
        ],
        'Status' => [
            'key' => 'status',
            'maxSize' => 256,
        ],
        'State' => [
            'key' => 'state',
            'maxSize' => 256,
        ],
        'Type' => [
            'key' => 'type',
            'maxSize' => 256,
        ],
        'Subtype' => [
            'key' => 'subtype',
            'maxSize' => 256,
        ],
        'Href' => [
            'key' => 'href',
            'maxSize' => 512,
        ],
    ];

    public function execute(Campaign $campaign): void
    {
        $service = new YandexDirectService($campaign->yandexDirectSource->authToken);

        $CampaignIds = $campaign->yandexDirectSource
            ->settings
            ->directCampaigns()
            ->get()
            ->map(static fn ($item) => $item->campaign_id)
            ->toArray();
        $chunks = array_chunk($CampaignIds, 10);

        $total = [];
        $adIds = [];
        $now = now();
        foreach ($chunks as $chunk) {
            $res = $service->getAds(
                criteria: [
                    'CampaignIds' => $chunk,
                    // Все, кроме ARCHIVED
                    'States' => ['SUSPENDED', 'OFF_BY_MONITORING', 'ON', 'OFF'],
                ],
                params: YandexDirectService::AD_TYPE_FIELDS_LIST,
            );

            foreach ($res as $ad) {
                foreach (YandexDirectService::AD_TYPE_FIELDS_KEY_LIST as $key) {
                    if (isset($ad[$key])) {
                        /** @noinspection SlowArrayOperationsInLoopInspection */
                        $ad = array_merge($ad, $ad[$key]);
                        unset($ad[$key]);
                    }
                }

                $importantFields = [
                    'domain' => empty($ad['Href']) ? null : self::getDomainFromUrl($ad['Href']),
                    'settings_id' => $campaign->yandexDirectSource->settings_id,
                    'updated_at' => $now,
                    'created_at' => $now,
                ];
                foreach (self::AD_FIELDS_LIST as $field => $key) {
                    $importantFields[$key['key']] = $ad[$field] ?? null;
                    if (!empty($key['maxSize'])) {
                        $importantFields[$key['key']] = Str::limit($importantFields[$key['key']], $key['maxSize']);
                    }
                    if (isset($ad[$field])) {
                        unset($ad[$field]);
                    }
                }
                $importantFields['other'] = json_encode($ad, JSON_THROW_ON_ERROR);

                $total[] = $importantFields;
                $adIds[] = $importantFields['ad_id'];
            }
        }
        DirectAd::query()
            ->where('settings_id', $campaign->yandexDirectSource->settings_id)
            ->whereNotIn('ad_id', $adIds)
            ->forceDelete();

        $chunks = array_chunk($total, 500);
        foreach ($chunks as $chunk) {
            DirectAd::query()->upsert(
                values: $chunk,
                uniqueBy: ['settings_id', 'ad_id'],
                update: [
                    'type',
                    'subtype',
                    'state',
                    'status',
                    'href',
                    'domain',

                    'other',
                    'updated_at',
                ],
            );
        }
    }

    private static function getDomainFromUrl(string $url): string
    {
        return Str::before(Str::after(Str::before($url, '?'), '://'), '/');
    }
}
