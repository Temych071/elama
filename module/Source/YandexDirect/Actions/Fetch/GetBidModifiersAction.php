<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions\Fetch;

use Module\Source\Sources\Exceptions\UnsupportedSourceTypeException;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Services\YandexDirectService;

final class GetBidModifiersAction
{
    public function execute(Source $source): array
    {
        UnsupportedSourceTypeException::throwIfTypeNotIn($source, Source::TYPE_YANDEX_DIRECT);

        $service = new YandexDirectService($source->authToken);

        $directCampaignIdChunks = $source->settings
            ->directCampaignsSel
            ->pluck('campaign_id')
            ->chunk(10)
            ->toArray();

        $res = [];
        foreach ($directCampaignIdChunks as $directCampaignIds) {
            array_push(
                $res,
                ...$service->getBidModifiers(
                    criteria: [
                        'CampaignIds' => array_values($directCampaignIds),
                        'Levels' => ['CAMPAIGN'],
                    ],
                ),
            );
        }

        return $res;
    }
}
