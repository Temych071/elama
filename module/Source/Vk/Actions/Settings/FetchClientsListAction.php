<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Settings;

use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Data\ClientData;
use Module\Source\Vk\Services\VkService;

final class FetchClientsListAction
{
    /**
     * @return array<ClientData>
     */
    public function execute(Source $source, int $accountId): array
    {
        return (new VkService($source->authToken))->getClients($accountId);
    }
}
