<?php

namespace Module\Source\Analytics\Contracts;

use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Enums\ChartGroupType;

interface AnalyticsDataProviderInterface
{
    /**
     * @param  Campaign  $campaign
     * @param  DateRange  $dateRange
     * @param  ChartGroupType  $groupType
     * @return array<array{index: string, name: string, metrics: array<string, int|float>, url: string}>
     */
    public function getChart(
        Campaign $campaign,
        DateRange $dateRange,
        ChartGroupType $groupType,
    ): array;

    /**
     * @param  Campaign  $campaign
     * @param  DateRange  $dateRange
     * @return array{index: string, name: string, metrics: array<string, int|float>, url: string}
     */
    public function getSummary(
        Campaign $campaign,
        DateRange $dateRange,
    ): array;
}
