<?php

namespace Module\Source\Analytics\Contracts;

use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\CabinetsFilter;

interface AnalyticsStatsProviderInterface
{
    /**
     * @return array<string|int, array<string, float|int>>|array<string, float|int>
     */
    public function getStats(
        Campaign $campaign,
        DateRange $dateRange,
        CabinetsFilter $filter,
        ?string $groupBy = null,
    ): array;
}
