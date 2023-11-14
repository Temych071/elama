<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\SeoYandexMetrika;

use App\Infrastructure\DateRange;
use Illuminate\Database\Query\Builder;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\SeoFilter;
use Module\Source\Analytics\Enums\ChartGroupType;
use Module\Source\Analytics\Enums\SeoItemType;
use Module\Source\Analytics\Implementations\Sources\YandexMetrika\AbstractYandexMetrikaDataProvider;
use Module\Source\Sources\Models\Source;

final class SeoYandexMetrikaDataProvider extends AbstractYandexMetrikaDataProvider
{
    public function getSummary(
        Campaign $campaign,
        DateRange $dateRange,
        SeoFilter $filter = new SeoFilter()
    ): array {
        /** @var object $res */
        $res = $this
            ->prepareQuery($campaign, $dateRange, $filter)
            ->first();

        return self::objectToMetrics($res);
    }

    public function getChart(
        Campaign $campaign,
        DateRange $dateRange,
        ChartGroupType $groupType,
        SeoFilter $filter = new SeoFilter(),
    ): array {
        $res = $this->prepareQuery($campaign, $dateRange, $filter, 'date')
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

    public function getAccounts(
        Campaign $campaign,
        DateRange $dateRange,
        SeoFilter $filter = new SeoFilter()
    ): array {
        $metrics = $this
            ->prepareQuery($campaign, $dateRange, $filter, 'settings_id')
            ->get()
            ->keyBy('settings_id');

        return $campaign->yandexMetrikaSources
            ->map(static fn (Source $source): array => [
                'name' => $source->authToken->nickname,
                'index' => SeoItemType::ACCOUNT->value . ':' . $source->settings_id,
                'metrics' => self::objectToMetrics($metrics[$source->settings_id] ?? null),
            ])->toArray();
    }

    public function getSearchEngines(
        Campaign $campaign,
        DateRange $dateRange,
        SeoFilter $filter = new SeoFilter()
    ): array {
        return $this
            ->prepareQuery($campaign, $dateRange, $filter, 'search_engine')
            ->get()
            ->map(static fn (object $item): array => [
                // ВНИМАНИЕ! Костыль))
                'index' => SeoItemType::SEARCH_ENGINE->value . ':' . str_replace(',', '*', (string)$item->search_engine),
                'name' => $item->search_engine ?? 'Неизвестное',
                'metrics' => self::objectToMetrics($item),
            ])->toArray();
    }

    protected function prepareFilteredQuery(
        Campaign $campaign,
        DateRange $dateRange,
        $filter = null,
    ): Builder {
        $q = parent::prepareFilteredQuery($campaign, $dateRange, $filter);

        if (!is_null($filter?->account_ids)) {
            $q->whereIn('settings_id', $filter?->account_ids);
        }

        if (!is_null($filter?->search_engines)) {
//            $q->whereIn('search_engine', $filter->search_engines);
            $q->where(static function (Builder $q) use ($filter): \Illuminate\Database\Query\Builder {
                foreach ($filter->search_engines as $search_engine) {
                    // ВНИМАНИЕ! Костыль))
                    $search_engine = str_replace('*', ',', $search_engine);
                    $q->orWhere('search_engine', 'LIKE', "%$search_engine%");
                }
                return $q;
            });
        }

        if (!is_null($filter?->devises)) {
            $q->whereIn('device_category', $filter?->devises);
        }

        $q->whereIn('traffic_source', [...($filter->traffic_sources ?? []), 'organic']);

        if (!is_null($filter?->source_utms)) {
            $q->whereIn('source', $filter?->source_utms);
        }

        return $q;
    }
}
