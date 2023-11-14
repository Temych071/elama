<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Services\Conversions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Module\Source\GoogleAnalytics\Data\AnalyticsGoalData;

interface AnalyticsConversionReportService
{
    /**
     * @param Collection<AnalyticsGoalData> $goals
     */
    public function getConversions(Collection $goals, Carbon $from): array;
}
