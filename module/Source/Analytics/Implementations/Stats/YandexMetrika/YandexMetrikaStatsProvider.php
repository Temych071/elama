<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Stats\YandexMetrika;

use App\Infrastructure\DateRange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Module\Campaign\Models\Campaign;
use Module\DgMarks\Enums\DgMarkSource;
use Module\Source\Analytics\Actions\CabinetFilterToDgMarks;
use Module\Source\Analytics\Contracts\AnalyticsStatsProviderInterface;
use Module\Source\Analytics\DTO\CabinetsFilter;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;

final class YandexMetrikaStatsProvider implements AnalyticsStatsProviderInterface
{
    public function getStats(
        Campaign $campaign,
        DateRange $dateRange,
        CabinetsFilter $filter,
        ?string $groupBy = null,
    ): array {
        $res = $this->prepareQuery(
            $campaign,
            $dateRange,
            app(CabinetFilterToDgMarks::class)->execute($filter),
            $groupBy,
        )->get();

        if (!is_null($groupBy)) {
            $res = $res->keyBy($groupBy);
        }

        return $res
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();
    }

    private static function objectToMetrics(?object $obj): array
    {
        if (is_null($obj)) {
            return [];
        }

        return [
            'purchases' => (int)$obj->purchases,
            'income' => (float)$obj->income,
            'requests' => (int)$obj->requests,
//            'clicks' => (int)$obj->clicks,
        ];
    }

    private function prepareQuery(
        Campaign $campaign,
        DateRange $dateRange,
        array $filter = [],
        ?string $groupBy = null,
    ): Builder {
        $q = DB::query()
            ->select([
//                'clicks',
                'income',
                'requests',
                'purchases'
            ]);

        $eq = $this->prepareEcommerceQuery($campaign, $dateRange, $filter);
        $cq = $this->prepareConversionsQuery($campaign, $dateRange, $filter);
//        $vq = $this->prepareVisitsQuery($campaign, $dateRange, $filter);

        if (is_null($groupBy)) {
            return $q
                ->fromSub($cq, 'c')
//                ->fromSub($vq, 'v')
//                ->crossJoinSub($cq, 'c')
                ->crossJoinSub($eq, 'e');
        }

//        $vq->groupBy($groupBy)->addSelect($groupBy);
        $cq->groupBy($groupBy)
            ->whereNotNull($groupBy)
            ->addSelect($groupBy);

        $eq->groupBy($groupBy)
            ->whereNotNull($groupBy)
            ->addSelect($groupBy);

        return $q
            ->selectRaw(
                "IF(c.$groupBy IS NULL, e.$groupBy, c.$groupBy) as $groupBy"
//                "IF(v.$groupBy IS NULL, IF(c.$groupBy IS NULL, e.$groupBy, c.$groupBy), v.$groupBy) as $groupBy"
            )
            ->fromSub($cq, 'c')
//            ->fromSub($vq, 'v')
//            ->joinSub(
//                $cq,
//                as: 'c',
//                first: "v.$groupBy",
//                operator: '=',
//                second: "c.$groupBy",
//                type: 'left outer',
//            )
            ->joinSub(
                $eq,
                as: 'e',
                first: "c.$groupBy",
                operator: '=',
                second: "e.$groupBy",
                type: 'left outer',
            );
    }

//    private function prepareVisitsQuery(
//        Campaign $campaign,
//        DateRange $dateRange,
//        array $filter = [],
//    ): Builder {
//        return $this->prepareFilteredQuery($campaign, $dateRange, $filter)
//            ->selectRaw('SUM(visits) as clicks')
//            ->from('metrika_visits');
//    }

    private function prepareConversionsQuery(
        Campaign $campaign,
        DateRange $dateRange,
        array $filter = [],
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

    private function prepareEcommerceQuery(
        Campaign $campaign,
        DateRange $dateRange,
        array $filter = [],
    ): Builder {
        return $this->prepareFilteredQuery($campaign, $dateRange, $filter)
            ->selectRaw('SUM(ecommerce_purchases) as purchases, SUM(ecommerce_revenue) as income')
            ->from('yandex_metrika_ecommerce');
    }

    private function prepareFilteredQuery(
        Campaign $campaign,
        DateRange $dateRange,
        array $filter = [],
    ): Builder {
        /** @var Builder $q */
        $q = DB::query()
            ->whereInDateRange($dateRange)
            ->where('traffic_source', 'ad');

        $aIds = $campaign->yandexMetrikaSources
            ->pluck('settings_id')
            ->toArray();
        $q->whereIn('settings_id', $aIds);

        foreach ($filter as $key => $values) {
            if (is_null($values)) {
                continue;
            }

            if (is_array($values)) {
                $q->whereIn($key, $values);
            } elseif ($key === 'dg_source') {
                $q->where(
                    static fn (Builder $q): \Illuminate\Database\Query\Builder => $q
                    ->orWhere('dg_source', $values)
                    ->orWhere('source', 'LIKE', self::dgMarkToUtmSource($values) . '%')
                );
            } else {
                $q->where($key, $values);
            }
        }

        return $q;
    }

    private static function getMetrikaSourceSelectedGoalIds(Collection $sources): array
    {
        $sources = $sources
            ->where('settings_type', Source::TYPE_YANDEX_METRIKA)
            ->whereNotNull('settings')
            ->load(['settings']);

        $res = collect();
        foreach ($sources as $source) {
            /** @var MetrikaSourceSettings $settings */
            $settings = $source->settings;

            $res->push(...$settings->goals->toCollection()->pluck('id'));
        }
        return $res->unique()->all();
    }

    private static function dgMarkToUtmSource(string $dgSource): string
    {
        return match ($dgSource) {
            DgMarkSource::VK->value => 'vk',
            DgMarkSource::YANDEX_DIRECT->value => 'yandex',
        };
    }
}
