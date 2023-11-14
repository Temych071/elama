<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\Statistics\Summary;

use App\Infrastructure\DateRange;
use Module\Campaign\BriefStatistics\Data\BriefStatistics;
use Module\Campaign\BriefStatistics\Data\BriefStatisticsItems;
use Module\Source\Sources\Models\Source;

final class GetSummaryStatisticsAction
{
    public function __construct(
        private readonly GetConversionStatisticsAction $getConversionStatistics,
        private readonly GetVisitsStatisticsAction $getVisitsStatistics,
    ) {
    }

    public function execute(Source $source, DateRange $period): BriefStatistics
    {
        $prevPeriod = $period->getPrev();
        $current = $this->getItems($source, $period);
        $prev = $this->getItems($source, $prevPeriod);

        return new BriefStatistics(
            period: $period,
            periodLength: $period->getLength(),
            prevPeriod: $prevPeriod,
            conversions: $current,
            prevConversions: $prev,
        );
    }

    private function getItems(Source $source, DateRange $period): ?BriefStatisticsItems
    {
        $conversions = $this->getConversionStatistics->execute($source, $period);
        $visits = $this->getVisitsStatistics->execute($source, $period);

        return BriefStatisticsItems::from(array_merge(
            $conversions,
            $visits,
            [
                'conversion_rate' => empty($visits['visits'])
                    ? (0)
                    : round(((int)$conversions['reaches'] / (int)$visits['visits']) * 100, 1),
            ],
        ));
    }
}
