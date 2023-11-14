<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Account;

use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkSettings;
use Module\Source\Vk\Services\VkService;

final class GetVkBudgetAction
{
    public function execute(Source $source): ?float
    {
        /** @var VkSettings $settings */
        $settings = $source->settings;

        if (!$settings?->account?->can_view_budget) {
            return null;
        }

        return (new VkService($source->authToken))
            ->getBudget($settings->account->account_id);
    }
}
