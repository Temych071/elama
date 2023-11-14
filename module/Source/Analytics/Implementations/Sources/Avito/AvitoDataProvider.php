<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\Avito;

use App\Infrastructure\DateRange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\CabinetsFilter;
use Module\Source\Analytics\Enums\CabinetItemType;
use Module\Source\Analytics\Enums\ChartGroupType;
use Module\Source\Analytics\Implementations\AbstractDataProvider;
use Module\Source\Avito\Models\AvitoItem;
use Module\Source\Sources\Models\Source;

final class AvitoDataProvider extends AbstractDataProvider
{
    /**
     * @param  object|null  $obj
     * @return array<string, int|float>
     */
    #[ArrayShape([
        'clicks' => "int",
        'requests' => "int",
        'expenses' => "float",
    ])]
    private static function objectToMetrics(?object $obj): array
    {
        return [
            'clicks' => (int)($obj?->clicks ?? 0),
            'requests' => (int)($obj?->requests ?? 0),
            'expenses' => (float)($obj?->expenses ?? 0),
        ];
    }

    public function getChart(
        Campaign $campaign,
        DateRange $dateRange,
        ChartGroupType $groupType,
        ?CabinetsFilter $filter = null,
    ): array {
        $metrics = $this->prepareMetricsQuery($campaign, $dateRange, $filter)
            ->groupBy('date')
            ->addSelect('date')
            ->get()
            ->keyBy('date')
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();

        $days = [];
        foreach ($dateRange->getDaysWithFormat() as $day) {
            $days[] = [
                'index' => 'day:' . $day,
                'name' => $day,
                'metrics' => $metrics[$day] ?? [],
            ];
        }

        return self::groupChartItems($days, $groupType);
    }

    public function getSummary(Campaign $campaign, DateRange $dateRange, ?CabinetsFilter $filter = null): array
    {
        return self::objectToMetrics($this->prepareMetricsQuery($campaign, $dateRange, $filter)->first() ?? []);
    }

    public function getItems(Campaign $campaign, DateRange $dateRange, CabinetsFilter $filter): array
    {
        /** @var Collection<int, AvitoItem> $items */
        $items = AvitoItem::query()
            ->whereIn('source_id', $campaign->avitoSources()->select('id'))
            ->select(['id', 'title', 'url'])
            ->toBase()
            ->get();


        $metrics = $this->prepareMetricsQuery($campaign, $dateRange, $filter)
            ->groupBy('item_id')
            ->addSelect('item_id')
            ->get()
            ->keyBy('item_id')
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();

        $res = [];
        foreach ($items as $item) {
            $res[] = [
                'index' => CabinetItemType::AD->value . ':' . $item->id,
                'name' => $item->title,
                'metrics' => $metrics[$item->id] ?? [],
                'url' => $item->url,
            ];
        }

        return $res;
    }

    public function getAccounts(Campaign $campaign, DateRange $dateRange, CabinetsFilter $filter): array
    {
        /** @var Source[] $sources */
        $sources = $campaign->avitoSources()->get();

        $metrics = $this->prepareMetricsQuery($campaign, $dateRange, $filter)
            ->groupBy('source_id')
            ->addSelect('source_id')
            ->get()
            ->keyBy('source_id')
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();

        $res = [];
        foreach ($sources as $source) {
            $res[] = [
                'index' => CabinetItemType::ACCOUNT->value . ':' . $source->id,
                'name' => $source->authToken->nickname,
                'metrics' => $metrics[$source->id] ?? [],
            ];
        }

        return $res;
    }

    private function prepareMetricsQuery(
        Campaign $campaign,
        DateRange $dateRange,
        ?CabinetsFilter $filter = null,
    ): Builder {
        $q = DB::table('avito_items_stats')
            ->selectRaw('SUM(views) as clicks, SUM(contacts) as requests, SUM(expenses) as expenses')
            ->whereInDateRange($dateRange)
            ->whereIn('source_id', $campaign->avitoSources()->select('id'));

        if (!empty($filter?->account_ids)) {
            $q->whereIn('source_id', $filter?->account_ids);
        }

        if (!empty($filter?->ad_ids)) {
            $q->whereIn('item_id', $filter?->ad_ids);
        }

        return $q;
    }
}
