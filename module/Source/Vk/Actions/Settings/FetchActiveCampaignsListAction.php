<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Settings;

use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Data\CampaignData;
use Module\Source\Vk\Services\VkService;

final class FetchActiveCampaignsListAction
{
    /**
     * @return array<CampaignData>
     */
    public function execute(Source $source, int $accountId, ?int $clientId): array
    {
        $service = new VkService($source->authToken);

        return array_values(array_filter(
            $service->getCampaigns($accountId, $clientId),
            static fn (CampaignData $campaign): bool => $campaign->status === CampaignData::STATUS_ACTIVE
        ));
    }
}
