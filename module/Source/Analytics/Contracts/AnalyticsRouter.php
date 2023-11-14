<?php

namespace Module\Source\Analytics\Contracts;

use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\ItemData;
use Module\Source\Analytics\Services\AnalyticsPath;

interface AnalyticsRouter
{
    /**
     * @param  null  $filter
     * @return ?ItemData[]
     */
    public function getData(
        Campaign $campaign,
        DateRange $dateRange,
        AnalyticsPath $path,
        $filter = null,
    ): ?array;
}
