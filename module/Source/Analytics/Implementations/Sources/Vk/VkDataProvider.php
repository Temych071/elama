<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\Vk;

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
use Module\Source\Analytics\Implementations\Sources\VkLeads\VkLeadsDataProvider;
use Module\Source\Analytics\Services\MetricsAdder;
use Module\Source\Sources\Models\Source;

final class VkDataProvider extends AbstractDataProvider
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

        $res = self::objectToMetrics($res);

        if ($campaign->hasVkLeads()) {
            $res = app(MetricsAdder::class)
                ->sum($res, app(VkLeadsDataProvider::class)->getSummary($campaign, $dateRange));
        }

        return $this->statsProvider->joinStats(
            $res,
            $campaign,
            $dateRange,
            new CabinetsFilter(source_type: Source::TYPE_VK)
        );
    }

    public function getChart(
        Campaign $campaign,
        DateRange $dateRange,
        ChartGroupType $groupType = ChartGroupType::DAYS_1,
        CabinetsFilter $filter = new CabinetsFilter(),
    ): array {
        $res = $this->prepareMetricsQuery($campaign, $dateRange, $filter)
            ->groupBy('day')
            ->addSelect('day')
            ->get()
            ->keyBy('day')
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
                'metrics' =>  $res[$day] ?? [],
            ];
        }

        return self::groupChartItems($filledData, $groupType);
    }

    public function getAccounts(Campaign $campaign, DateRange $dateRange, CabinetsFilter $filter): array
    {
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

        $accounts = [];
        foreach ($campaign->vkSources as $source) {
            $accounts[] = [
                'index' => CabinetItemType::ACCOUNT->value . ':' . $source->settings_id,
                'name' => $source->settings->getAccountName(),
                'metrics' => $res[$source->settings_id] ?? [],
            ];
        }

        return $accounts;
    }

    public function getCampaigns(Campaign $campaign, DateRange $dateRange, CabinetsFilter $filter): array
    {
        /** @var Source[] $sources */
        $sources = $campaign->vkSources()->with([
            'settings' => static fn ($builder) => $builder->with('vkCampaigns'),
        ])->get();

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

        $vkCampaigns = [];
        foreach ($sources as $source) {
            if (
                !is_null($filter->account_ids)
                && !in_array($source->settings_id, $filter->account_ids, true)
            ) {
                continue;
            }

            foreach ($source->settings->vkCampaignsSel as $vkCampaign) {
                $vkCampaigns[] = [
                    'index' => CabinetItemType::CAMPAIGN->value . ':' . $vkCampaign->id,
                    'name' => $vkCampaign->name,
                    'metrics' => $res[$vkCampaign->id] ?? [],
                ];
            }
        }

        return $vkCampaigns;
    }

    public function getAds(Campaign $campaign, DateRange $dateRange, CabinetsFilter $filter): array
    {
        /** @var Source[] $sources */
        $sources = $campaign->vkSources()->with([
            'settings' => static fn ($builder) => $builder->with('vkAds'),
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
            'dg_3'
        );

        $vkAds = [];
        foreach ($sources as $source) {
            if (
                !is_null($filter->account_ids)
                && !in_array($source->settings_id, $filter->account_ids, true)
            ) {
                continue;
            }

            foreach ($source->settings->vkAdsSel as $vkAd) {
                if (
                    !is_null($filter->campaign_ids)
                    && !in_array($vkAd->campaign_id, $filter->campaign_ids, true)
                ) {
                    continue;
                }

                $vkAds[] = [
                    'index' => CabinetItemType::AD->value . ':' . $vkAd->id,
                    'name' => $vkAd->name,
                    'metrics' => $res[$vkAd->id] ?? [],
                ];
            }
        }

        return $vkAds;
    }

    private function prepareMetricsQuery(
        Campaign $campaign,
        DateRange $dateRange,
        ?CabinetsFilter $filter = null,
    ): Builder {
        $q = DB::table('vk_ads_statistics')
            ->whereInDateRange($dateRange, 'day')
            ->selectRaw('SUM(clicks) as clicks, SUM(impressions) as impressions, SUM(spent) / 100 as expenses');

        if (!is_null($filter?->account_ids)) {
            $q->whereIn('settings_id', $filter?->account_ids);
        } else {
            $aIds = $campaign->vkSources
                ->map(static fn (Source $it): int => $it->settings_id)
                ->toArray();
            $q->whereIn('settings_id', $aIds);
        }

        if (!is_null($filter?->campaign_ids)) {
            $q->whereIn('campaign_id', $filter?->campaign_ids);
        } else {
            $campaignIds = $campaign->vkSources
                ->map(static fn (Source $source): \Illuminate\Support\Collection => $source->settings->vkCampaignsSel->pluck('id'))
                ->reduce(static fn (Collection $res, Collection $ids): \Illuminate\Support\Collection => $res->push(...$ids), collect())
                ?->unique()
                ?->values()
                ?->toArray() ?? [];

            $q->whereIn('campaign_id', $campaignIds);
        }

        if (!is_null($filter?->ad_ids)) {
            $q->where('ad_id', $filter?->ad_ids);
        } // TODO: Возможно как и для кампаний надо будет фильтр по-умолчанию сделать

        return $q;
    }
}
