<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\YandexMetrika;

use App\Infrastructure\DateRange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Enums\ChartGroupType;
use Module\Source\Analytics\Implementations\AbstractDataProvider;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;

abstract class AbstractYandexMetrikaDataProvider extends AbstractDataProvider
{
    abstract public function getChart(Campaign $campaign, DateRange $dateRange, ChartGroupType $groupType): array;

    abstract public function getSummary(Campaign $campaign, DateRange $dateRange): array;

    protected static function objectToMetrics(?object $obj): array
    {
        if (is_null($obj)) {
            return [];
        }

        return [
            'purchases' => (int)$obj->purchases,
            'income' => (float)$obj->income,
            'requests' => (int)$obj->requests,
            'clicks' => (int)$obj->clicks,
        ];
    }

    protected function prepareQuery(
        Campaign $campaign,
        DateRange $dateRange,
        $filter = null,
        ?string $groupBy = null,
    ): Builder {
        $q = DB::query()
            ->select(['clicks', 'income', 'requests', 'purchases']);

        $eq = $this->prepareEcommerceQuery($campaign, $dateRange, $filter);
        $cq = $this->prepareConversionsQuery($campaign, $dateRange, $filter);
        $vq = $this->prepareVisitsQuery($campaign, $dateRange, $filter);

        if (is_null($groupBy)) {
            return $q
                ->fromSub($vq, 'v')
                ->crossJoinSub($cq, 'c')
                ->crossJoinSub($eq, 'e');
        }

        $vq->groupBy($groupBy)->addSelect($groupBy);
        $cq->groupBy($groupBy)->addSelect($groupBy);
        $eq->groupBy($groupBy)->addSelect($groupBy);

        return $q
            ->selectRaw("IF(v.$groupBy IS NULL, IF(c.$groupBy IS NULL, e.$groupBy, c.$groupBy), v.$groupBy) as $groupBy")
            ->fromSub($vq, 'v')
            ->joinSub(
                $cq,
                as: 'c',
                first: "v.$groupBy",
                operator: '=',
                second: "c.$groupBy",
                type: 'left outer',
            )
            ->joinSub(
                $eq,
                as: 'e',
                first: "v.$groupBy",
                operator: '=',
                second: "e.$groupBy",
                type: 'left outer',
            );
    }

    protected function prepareVisitsQuery(
        Campaign $campaign,
        DateRange $dateRange,
        $filter = null,
    ): Builder {
        return $this->prepareFilteredQuery($campaign, $dateRange, $filter)
            ->selectRaw('SUM(visits) as clicks')
            ->from('metrika_visits');
    }

    protected static function getMetrikaSourceSelectedGoalIds(Collection $sources): array
    {
        $sources = $sources
            ->where('settings_type', Source::TYPE_YANDEX_METRIKA)
            ->load(['settings']);

        $res = collect();

        foreach ($sources as $source) {
            /** @var MetrikaSourceSettings $settings */
            $settings = $source->settings;

            $res->push(...$settings->goals->toCollection()->pluck('id'));
        }

        return $res->unique()->all();
    }

    protected function prepareConversionsQuery(
        Campaign $campaign,
        DateRange $dateRange,
        $filter = null,
    ): Builder {
        $q = $this->prepareFilteredQuery($campaign, $dateRange, $filter)
            ->selectRaw('SUM(reaches) as requests')
            ->from('metrika_conversions');

        $goalIds = self::getMetrikaSourceSelectedGoalIds($campaign->yandexMetrikaSources);
        if ($goalIds !== []) {
            $q = $q->whereIn('goal_id', $goalIds);
        }

        return $q;
    }

    protected function prepareEcommerceQuery(
        Campaign $campaign,
        DateRange $dateRange,
        $filter = null,
    ): Builder {
        return $this->prepareFilteredQuery($campaign, $dateRange, $filter)
            ->selectRaw('SUM(ecommerce_purchases) as purchases, SUM(ecommerce_revenue) as income')
            ->from('yandex_metrika_ecommerce');
    }

    /**
     * Should be overridden.
     *
     * @param $filter
     */
    protected function prepareFilteredQuery(
        Campaign $campaign,
        DateRange $dateRange,
        $filter = null,
    ): Builder {
        $aIds = $campaign->yandexMetrikaSources
            ->map(static fn (Source $it): int => $it->settings_id)
            ->toArray();

        return DB::query()
            ->whereIn('settings_id', $aIds)
            ->whereInDateRange($dateRange);
    }
}
