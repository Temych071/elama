<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations;

use App\Infrastructure\DateRange;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Module\Source\Analytics\Contracts\AnalyticsDataProviderInterface;
use Module\Source\Analytics\Enums\ChartGroupType;
use Module\Source\Analytics\Implementations\Stats\StatsProvider;
use Module\Source\Analytics\Services\MetricsAdder;
// сюда
abstract class AbstractDataProvider implements AnalyticsDataProviderInterface
{
    public function __construct(
        protected readonly StatsProvider $statsProvider,
    ) {
    }

    protected static function groupChartItems(array $rawItems, ChartGroupType $groupType): array
    {
        return match ($groupType) {
            ChartGroupType::DAYS_1 => static::groupChartItemsByDays($rawItems, 1),
            ChartGroupType::DAYS_3 => static::groupChartItemsByDays($rawItems, 3),
            ChartGroupType::DAYS_7 => static::groupChartItemsByDays($rawItems, 7),
            ChartGroupType::DAYS_30 => static::groupChartItemsByDays($rawItems, 30),

            ChartGroupType::WEEKS => static::groupChartItemsByUnit($rawItems, 'week'),
            ChartGroupType::MONTHS => static::groupChartItemsByUnit($rawItems, 'month'),
        };
    }

    protected static function groupChartItemsByDays(array $rawItems, int $days): array
    {
        if ($days <= 1) {
            return $rawItems;
        }

        return self::sumGroupedItems(array_chunk($rawItems, $days));
    }

    protected static function groupChartItemsByUnit(array $rawItems, string $unit): array
    {
        $groups = [];

        foreach ($rawItems as $item) {
            $key = Carbon::parse($item['name'])->getPaddedUnit($unit);
            $groups[$key][] = $item;
        }

        return self::sumGroupedItems($groups);
    }

    protected static function sumGroupedItems(array $groups): array
    {
        $adder = app(MetricsAdder::class);
        return array_reduce($groups, static function (array $res, array $chunk) use ($adder): array {
            $dateRange = (string)DateRange::make([
                Arr::first($chunk)['name'],
                Arr::last($chunk)['name'],
            ]);

            $groupedItem = [
                'index' => 'date-range:' . $dateRange,
                'name' => $dateRange,
                'metrics' => $adder->sumArray(...array_map(static fn ($it) => $it['metrics'], $chunk)),
            ];

            $res[] = $groupedItem;
            return $res;
        }, []);
    }
}
