<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\DirectTrafficYandexMetrika;

use App\Infrastructure\DateRange;
use Illuminate\Database\Query\Builder;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Enums\ChartGroupType;
use Module\Source\Analytics\Implementations\Sources\YandexMetrika\AbstractYandexMetrikaDataProvider;

final class DirectTrafficYandexMetrikaDataProvider extends AbstractYandexMetrikaDataProvider
{
    public function getChart(Campaign $campaign, DateRange $dateRange, ChartGroupType $groupType): array
    {
        $res = $this->prepareQuery($campaign, $dateRange, null, 'date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $filledData = [];
        foreach ($dateRange->getDaysWithFormat() as $day) {
            $filledData[] = [
                'index' => "day:$day",
                'name' => $day,
                'metrics' => isset($res[$day]) ? self::objectToMetrics($res[$day]) : [],
            ];
        }

        return self::groupChartItems($filledData, $groupType);
    }

    public function getSummary(Campaign $campaign, DateRange $dateRange): array
    {
        /** @var object $res */
        $res = $this
            ->prepareQuery($campaign, $dateRange)
            ->first();

        return self::objectToMetrics($res);
    }

    protected function prepareFilteredQuery(
        Campaign $campaign,
        DateRange $dateRange,
        $filter = null,
    ): Builder {
        return parent::prepareFilteredQuery($campaign, $dateRange, $filter)
            ->where('traffic_source', 'direct');
    }
}
