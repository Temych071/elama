<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions\Fetch;

use Module\Campaign\Models\Campaign;
use Module\Source\YandexDirect\Models\DirectCampaign;
use Module\Source\YandexDirect\Services\YandexDirectService;

final class GetKeywordsAction
{
    public function execute(Campaign $campaign): array
    {
        $service = new YandexDirectService($campaign->yandexDirectSource->authToken);
        $settings = $campaign
            ->yandexDirectSource
            ->settings;

        $campaignIds = $settings
            ->directCampaigns
            ->map(static fn (DirectCampaign $c): int => $c->campaign_id)
            ->toArray();

        $chunks = array_chunk($campaignIds, 10);

        $total = [];
        foreach ($chunks as $chunk) {
            $res = $service->getKeywords(
                criteria: ['CampaignIds' => $chunk],
                fields: YandexDirectService::KEYWORD_FIELDS_LIST,
            );
            foreach ($res as $keyword) {
                $total[] = $keyword;
            }
        }

        return $total;
    }
}
