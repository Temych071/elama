<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\YandexDirect;

use App\Infrastructure\DateRange;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\CabinetsFilter;
use Module\Source\Analytics\Enums\CabinetItemType;
use Module\Source\Analytics\Enums\ChartGroupType;
use Module\Source\Analytics\Implementations\AbstractDataProvider;
use Module\Source\Sources\Models\Source;

final class YandexDirectDataProvider extends AbstractDataProvider
{
    /**
     * @return array{expenses: float, clicks: int}
     */
    #[ArrayShape([
        'expenses' => "float",
        'clicks' => "int",
        'impressions' => "int",
    ])]
    private static function objectToMetrics(?object $obj): array
    {
        return [
            'expenses' => (float)($obj?->expenses ?? 0),
            'clicks' => (int)($obj?->clicks ?? 0),
            'impressions' => (int)($obj?->impressions ?? 0),
        ];
    }

    public function getSummary(Campaign $campaign, DateRange $dateRange): array
    {
        /** @var object $res */
        $res = $this
            ->prepareMetricsQuery($campaign, $dateRange)
            ->first();

        if (is_null($res)) {
            return [];
        }

        return $this->statsProvider->joinStats(
            self::objectToMetrics($res),
            $campaign,
            $dateRange,
            new CabinetsFilter(source_type: Source::TYPE_YANDEX_DIRECT)
        );
    }

    public function getChart(
        Campaign $campaign,
        DateRange $dateRange,
        ChartGroupType $groupType = ChartGroupType::DAYS_1,
        CabinetsFilter $filter = new CabinetsFilter(),
    ): array {
        $res = $this->prepareMetricsQuery($campaign, $dateRange, $filter)
            ->groupBy('date')
            ->addSelect('date')
            ->get()
            ->keyBy('date')
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();

        $res = $this->statsProvider->joinStats(
            $res,
            $campaign,
            $dateRange,
            $filter,
            'date'
        );

        $filledData = [];
        foreach ($dateRange->getDaysWithFormat() as $day) {
            $filledData[] = [
                'index' => "day:$day",
                'name' => $day,
                'metrics' => $res[$day] ?? [],
            ];
        }

        return self::groupChartItems($filledData, $groupType);
    }

    /**
     * @return array[]
     */
    public function getAccounts(Campaign $campaign, DateRange $dateRange, CabinetsFilter $filter): array
    {
        $accounts = array_map(static fn(Source $source): array => [
            'name' => $source->authToken->nickname,
            'index' => $source->settings_id,
        ], $campaign->yandexDirectSources->all());

        $res = $this->prepareMetricsQuery($campaign, $dateRange, $filter)
            ->groupBy('settings_id')
            ->addSelect('settings_id')
            ->get()
            ->keyBy('settings_id')
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();

        $res = $this->statsProvider->joinStats(
            $res,
            $campaign,
            $dateRange,
            $filter,
            'dg_1'
        );

        foreach ($accounts as &$account) {
            $account['metrics'] = $res[$account['index']] ?? [];
            $account['index'] = CabinetItemType::ACCOUNT->value . ':' . $account['index'];
        }
        unset($account, $res);

        return $accounts;
    }

    public function getCampaigns(Campaign $campaign, DateRange $dateRange, CabinetsFilter $filter): array
    {
        /** @var Source[] $sources */
        $sources = $campaign->yandexDirectSources()->with([
            'settings' => static fn ($builder) => $builder->with('directCampaigns'),
        ])->get();

        $directCampaigns = [];
        foreach ($sources as $source) {
            if (
                !is_null($filter->account_ids)
                && !in_array($source->settings_id, $filter->account_ids, true)
            ) {
                continue;
            }

            foreach ($source->settings->directCampaignsSel as $directCampaign) {
                $directCampaigns[] = [
                    'index' => $directCampaign->campaign_id,
                    'name' => $directCampaign->name,
                ];
            }

            DB::table('yandex_direct_ad_reports')
                ->where('settings_id', $source->settings_id)
                ->whereNotIn('campaign_id', $source->settings->directCampaignsSel->pluck('campaign_id'))
                ->select(['campaign_id'])
                ->distinct()
                ->get()
                ->each(static function ($item) use (&$directCampaigns) {
                    $directCampaigns[] = [
                        'index' => $item->campaign_id,
                        'name' => "Кампания #$item->campaign_id",
                    ];
                });
        }

        $res = $this->prepareMetricsQuery($campaign, $dateRange, $filter)
            ->groupBy('campaign_id')
            ->addSelect('campaign_id')
            ->get()
            ->keyBy('campaign_id')
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();

        $res = $this->statsProvider->joinStats(
            $res,
            $campaign,
            $dateRange,
            $filter,
            'dg_2'
        );

        foreach ($directCampaigns as &$directCampaign) {
            $directCampaign['metrics'] = $res[$directCampaign['index']] ?? [];
            $directCampaign['index'] = CabinetItemType::CAMPAIGN->value . ':' . $directCampaign['index'];
        }
        unset($directCampaign, $res);

        return $directCampaigns;
    }

    public function getAdGroups(Campaign $campaign, DateRange $dateRange, CabinetsFilter $filter): array
    {
        /** @var Source[] $sources */
        $sources = $campaign->yandexDirectSources()->with([
            'settings' => static fn ($builder) => $builder->with('directAdGroups'),
        ])->get();

        $res = $this->prepareMetricsQuery($campaign, $dateRange, $filter)
            ->groupBy('ad_group_id')
            ->addSelect('ad_group_id')
            ->get()
            ->keyBy('ad_group_id')
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();

        $res = $this->statsProvider->joinStats(
            $res,
            $campaign,
            $dateRange,
            $filter,
            'dg_3'
        );

        $directAdGroups = [];
        foreach ($sources as $source) {
            if (
                !is_null($filter->account_ids)
                && !in_array($source->settings_id, $filter->account_ids, true)
            ) {
                continue;
            }

            foreach ($source->settings->directAdGroupsSel as $directAdGroup) {
                if (
                    !is_null($filter->campaign_ids)
                    && !in_array($directAdGroup->campaign_id, $filter->campaign_ids, true)
                ) {
                    continue;
                }

                $directAdGroups[] = [
                    'index' => CabinetItemType::AD_GROUP->value . ':' . $directAdGroup->ad_group_id,
                    'name' => $directAdGroup->name,
                    'metrics' => $res[$directAdGroup->ad_group_id] ?? [],
                ];
            }
        }

        return $directAdGroups;
    }

    public function getAds(Campaign $campaign, DateRange $dateRange, CabinetsFilter $filter): array
    {
        /** @var Source[] $sources */
        $sources = $campaign->yandexDirectSources()->with([
            'settings' => static fn ($builder) => $builder->with('directAds'),
        ])->get();

        $res = $this->prepareMetricsQuery($campaign, $dateRange, $filter)
            ->groupBy('ad_id')
            ->addSelect('ad_id')
            ->get()
            ->keyBy('ad_id')
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();

        $res = $this->statsProvider->joinStats(
            $res,
            $campaign,
            $dateRange,
            $filter,
            'dg_4'
        );

        $directAds = [];
        foreach ($sources as $source) {
            if (
                !is_null($filter->account_ids)
                && !in_array($source->settings_id, $filter->account_ids, true)
            ) {
                continue;
            }

            foreach ($source->settings->directAdsSel as $directAd) {
                if (
                    !is_null($filter->campaign_ids)
                    && !in_array($directAd->campaign_id, $filter->campaign_ids, true)
                ) {
                    continue;
                }

                if (
                    !is_null($filter->group_ids)
                    && !in_array($directAd->ad_group_id, $filter->group_ids, true)
                ) {
                    continue;
                }

                $directAds[] = [
                    'index' => CabinetItemType::AD->value . ':' . $directAd->ad_id,
                    'name' => "$directAd->domain (#$directAd->ad_id)",
                    'metrics' => $res[$directAd->ad_id] ?? [],
                ];
            }
        }

        return $directAds;
    }

    private function prepareMetricsQuery(Campaign $campaign, DateRange $dateRange, ?CabinetsFilter $filter = null): Builder
    {
        $q = DB::table('yandex_direct_ad_reports')
            ->whereInDateRange($dateRange)
            ->selectRaw('SUM(clicks) as clicks, SUM(impressions) as impressions, SUM(cost) / 1000000 as expenses');

        $aIds = !empty($filter?->account_ids)
            ? $filter?->account_ids
            : $campaign->yandexDirectSources
                ->map(static fn (Source $it): int => $it->settings_id)
                ->toArray();
        $q->whereIn('settings_id', $aIds);

        if (!empty($filter?->campaign_ids)) {
            $q->whereIn('campaign_id', $filter?->campaign_ids);
        } else {
            $campaignIds = DB::table('yandex_direct_ad_reports')
                ->whereIn('settings_id', $aIds)
                ->select(['campaign_id'])
                ->distinct()
                ->pluck('campaign_id');

            $q->whereIn('campaign_id', $campaignIds);
        }

        if (!empty($filter?->group_ids)) {
            $q->whereIn('ad_group_id', $filter?->group_ids);
        }

        if (!empty($filter?->ad_ids)) {
            $q->whereIn('ad_id', $filter?->ad_ids);
        } // TODO: Возможно как и для кампаний надо будет фильтр по-умолчанию сделать

        return $q;
    }
}
