<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Fetch;

use Module\Source\Sources\Exceptions\UnsupportedSourceTypeException;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Services\VkService;

final class GetAdsTargetingAction
{
    public function execute(Source $source): array
    {
        UnsupportedSourceTypeException::throwIfTypeNotIn($source, Source::TYPE_VK);

        $accountId = $source->settings->account->account_id;
        $clientId = $source->settings->client?->id;
        $adIds = $source->settings->vkAdsSel->pluck('id')->toArray();

        return (new VkService($source->authToken))
            ->getAdsTargeting($accountId, $clientId, $adIds);
    }
}
