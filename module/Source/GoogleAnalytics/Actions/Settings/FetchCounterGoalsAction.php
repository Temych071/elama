<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\Settings;

use Google\Service\Analytics\Goal;
use Module\Source\GoogleAnalytics\Services\GoogleAnalyticsService;
use Module\Source\Sources\Models\Source;

final class FetchCounterGoalsAction
{
    public function __construct(private readonly GoogleAnalyticsService $service)
    {
    }

    /**
     * @return Goal[]
     */
    public function execute(
        Source $source,
        ?int $account_id = null,
        ?string $property_id = null,
        ?int $counter_id = null
    ): array {
        $account_id ??= $source->settings->google_id;
        $property_id ??= $source->settings->property_id;
        $counter_id ??= $source->settings->view_id;

        return $this->service
            ->connectUsing($source->authToken)
            ->analyticsService()
            ->management_goals
            ->listManagementGoals($account_id, $property_id, $counter_id)
            ->getItems();
    }
}
