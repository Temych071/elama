<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\Settings;

use Google\Service\Analytics\AccountSummary;
use Module\Source\GoogleAnalytics\Services\GoogleAnalyticsService;
use Module\Source\Sources\Models\Source;

final class FetchAccountSummariesAction
{
    public function __construct(
        private readonly GoogleAnalyticsService $service,
    ) {
    }

    /**
     * @return AccountSummary[]
     */
    public function execute(Source $source): array
    {
        $list = $this->service
            ->connectUsing($source->authToken)
            ->analyticsService()
            ->management_accountSummaries
            ->listManagementAccountSummaries()
            ->getItems();

        return array_filter($list, static fn ($item): bool => !empty($item->getWebProperties()));
    }
}
