<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Settings;

use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Data\AccountData;
use Module\Source\Vk\Services\VkService;

final class FetchAccountListAction
{
    /**
     * @return array<AccountData>
     */
    public function execute(Source $source): array
    {
        $service = new VkService($source->authToken);

        return array_filter(
            $service->getAccounts(),
            static fn (AccountData $account): int => $account->account_status //&& $account->can_view_budget
        );
    }
}
