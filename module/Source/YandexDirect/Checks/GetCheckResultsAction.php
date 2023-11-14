<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Module\Campaign\Checks\Checks\CheckRule;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Checks\Account\HasRetargetingCheckRule;
use Module\Source\YandexDirect\Checks\Account\LowBalanceCheckRule;
use Module\Source\YandexDirect\Checks\Account\MoreCampaignsCheckRule;
use Module\Source\YandexDirect\Checks\AdGroups\LowImpressionsCheckRule;
use Module\Source\YandexDirect\Checks\AdGroups\MobileAdsCheckRule;
use Module\Source\YandexDirect\Checks\AdGroups\MoreAdsCheckRule;
use Module\Source\YandexDirect\Checks\Ads\BannerCheckRule;
use Module\Source\YandexDirect\Checks\Ads\HasExtensionsCheckRule;
use Module\Source\YandexDirect\Checks\Ads\HasImageCheckRule;
use Module\Source\YandexDirect\Checks\Ads\HasKeywordInDUrlCheckRule;
use Module\Source\YandexDirect\Checks\Ads\HasKeywordInTitleCheckRule;
use Module\Source\YandexDirect\Checks\Ads\HasVCardCheckRule;
use Module\Source\YandexDirect\Checks\Ads\NotModeratedCheckRule;
use Module\Source\YandexDirect\Checks\Ads\SitelinkSetCheckRule;
use Module\Source\YandexDirect\Checks\Ads\SitelinksHasDescriptionCheckRule;
use Module\Source\YandexDirect\Checks\Ads\SitelinksHasUtmsCheckRule;
use Module\Source\YandexDirect\Checks\Ads\SubTitleCheckRule;
use Module\Source\YandexDirect\Checks\Ads\UtmMarksCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\BudgetSpentCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\HasBidModifiersCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\HasLimitsCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\HighExpensesCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\LinkMetricsServiceCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\NegativeKeywordsCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\PriorityGoalsCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\SearchNetworkCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\SiteMonitoringCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\StoppedCampaignCheckRule;
use Module\Source\YandexDirect\Checks\Campaigns\TimeTargetingCheckRule;
use Module\Source\YandexDirect\Models\DirectAd;
use Module\Source\YandexDirect\Models\DirectAdGroup;
use Module\Source\YandexDirect\Models\DirectCampaign;
use Spatie\LaravelData\DataCollection;

final class GetCheckResultsAction
{
    /**
     * @return array{accounts: \Spatie\LaravelData\DataCollection|null, campaigns: \Spatie\LaravelData\DataCollection|null, adgroups: \Spatie\LaravelData\DataCollection|null, ads: \Spatie\LaravelData\DataCollection|null}
     */
    #[ArrayShape([
        'accounts' => DataCollection::class,
        'campaigns' => DataCollection::class,
        'adgroups' => DataCollection::class,
        'ads' => DataCollection::class
    ])]
    public function execute(Source $directSource, array $filters): array
    {
        $directCampaigns = $directSource->settings->directCampaignsSel;

        if (!empty($filters['state'])) {
            $directCampaigns = $directCampaigns->where('state', $filters['state']);
        }

        if (!empty($filters['status'])) {
            $directCampaigns = $directCampaigns->where('status', $filters['status']);
        }

        $directCampaignIds = $directCampaigns->pluck('campaign_id')->all();

        $directAdGroups = $directSource->settings
            ->directAdGroupsSel
            ->whereIn('campaign_id', $directCampaignIds);

        $directAds = $directSource->settings
            ->directAdsSel
            ->whereIn('campaign_id', $directCampaignIds);

        if (!empty($filters['state'])) {
            $directAds = $directAds->where('state', $filters['state']);
        }

        $adGroupsImpressions = $this->getAdsImpressionDelta($directCampaignIds, $directSource);
        $campaignExpenses = $this->getCampaignsExpensesDelta($directCampaignIds, $directSource);

        $directCampaignGroups = $directAdGroups->groupBy('campaign_id');
        $directCampaignAds = $directAds->groupBy('campaign_id');
        $directAdGroupAds = $directAds->groupBy('ad_group_id');

        /** @var DirectCampaign $directCampaign */
        foreach ($directCampaigns as $directCampaign) {
            if (!empty($campaignExpenses[$directCampaign->campaign_id])) {
                $directCampaign->setAttribute('expensesDelta', $campaignExpenses[$directCampaign->campaign_id]);
            }

            $groups = $directCampaignGroups->get($directCampaign->campaign_id, collect());
            $directCampaign->setRelation(
                'directAdGroups',
                $groups,
            );
            $directCampaign->setRelation(
                'ads',
                $directCampaignAds->get($directCampaign->campaign_id, collect()),
            );

            /** @var DirectAdGroup $directAdGroup */
            foreach ($groups as $directAdGroup) {
                $ads = $directAdGroupAds->get($directAdGroup->ad_group_id, collect());

                $directAdGroup->setRelation('directCampaign', $directCampaign);
                $directAdGroup->setRelation('directAds', $ads);
                if (!empty($adGroupsImpressions[$directAdGroup->ad_group_id])) {
                    $directAdGroup->setAttribute(
                        'impressionsDelta',
                        $adGroupsImpressions[$directAdGroup->ad_group_id]
                    );
                }

                /** @var DirectAd $directAd */
                foreach ($ads as $directAd) {
                    $directAd->setRelation('campaign', $directCampaign);
                    $directAd->setRelation('directAdGroup', $directAdGroup);
                }
            }
        }

        $directAdGroups = $directAdGroups->filter(static fn ($group): bool => $group->directAds->count() > 0);

        return [
            'accounts' => CheckRule::getResultsForObjects([$directSource->settings], [
                MoreCampaignsCheckRule::class,
                LowBalanceCheckRule::class,
                HasRetargetingCheckRule::class,
            ]),
            'campaigns' => CheckRule::getResultsForObjects($directCampaigns, [
                LinkMetricsServiceCheckRule::class,
                TimeTargetingCheckRule::class,
                NegativeKeywordsCheckRule::class,
                HasLimitsCheckRule::class,
                StoppedCampaignCheckRule::class,
                SearchNetworkCheckRule::class,
                SiteMonitoringCheckRule::class,
                BudgetSpentCheckRule::class,
                HighExpensesCheckRule::class,
                PriorityGoalsCheckRule::class,
                HasBidModifiersCheckRule::class,
            ]),
            'adgroups' => CheckRule::getResultsForObjects($directAdGroups, [
                MoreAdsCheckRule::class,
                MobileAdsCheckRule::class,
                LowImpressionsCheckRule::class,
            ]),
            'ads' => CheckRule::getResultsForObjects($directAds, [
                HasVCardCheckRule::class,
                NotModeratedCheckRule::class,
                SubTitleCheckRule::class,
                UtmMarksCheckRule::class,
                HasExtensionsCheckRule::class,
                SitelinkSetCheckRule::class,
                BannerCheckRule::class,
                HasKeywordInDUrlCheckRule::class,
                HasKeywordInTitleCheckRule::class,
                HasImageCheckRule::class,
                SitelinksHasDescriptionCheckRule::class,
                SitelinksHasUtmsCheckRule::class,
            ]),
        ];
    }

    private function getCampaignsExpensesDelta(array $directCampaignIds, Source $directSource): array
    {
        $first = DateRange::fromArray([
            Carbon::now()->subWeeks(2)->startOfWeek(),
            Carbon::now()->subWeeks(2)->endOfWeek(),
        ]);
        $second = DateRange::fromArray([
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek(),
        ]);

        $sub = DB::table('yandex_direct_ad_reports')
            ->selectRaw('campaign_id, SUM(cost) as expenses')
            ->where('settings_id', $directSource->settings_id)
            ->whereIn('campaign_id', $directCampaignIds)
            ->groupBy(['campaign_id']);

        return DB::table('yandex_direct_ad_reports')
            ->fromSub(
                $sub->clone()->whereInDateRange($first, 'date'),
                'f',
            )
            ->joinSub(
                $sub->clone()->whereInDateRange($second, 'date'),
                's',
                'f.campaign_id',
                's.campaign_id',
            )
            ->selectRaw(
                '
                    f.campaign_id as campaign_id,
                    f.expenses as first,
                    s.expenses as second,
                    s.expenses - f.expenses as delta,
                    (ABS(f.expenses - s.expenses) / IF(f.expenses = 0, 1, f.expenses)) as deltaPercents
                '
            )
            ->get()
            ->keyBy('campaign_id')
            ->toArray();
    }

    private function getAdsImpressionDelta(array $directCampaignIds, Source $directSource): array
    {
        $first = DateRange::fromArray([
            Carbon::now()->subWeeks(2)->startOfWeek(),
            Carbon::now()->subWeeks(2)->endOfWeek(),
        ]);
        $second = DateRange::fromArray([
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek(),
        ]);

        $sub = DB::table('yandex_direct_ad_reports')
            ->selectRaw('ad_group_id, SUM(impressions) as impressions')
            ->where('settings_id', $directSource->settings_id)
            ->whereIn('campaign_id', $directCampaignIds)
            ->groupBy(['ad_group_id']);

        return DB::table('yandex_direct_ad_reports')
            ->fromSub(
                $sub->clone()->whereInDateRange($first, 'date'),
                'f',
            )
            ->joinSub(
                $sub->clone()->whereInDateRange($second, 'date'),
                's',
                'f.ad_group_id',
                's.ad_group_id',
            )
            ->selectRaw(
                '
                    f.ad_group_id as ad_group_id,
                    f.impressions as first,
                    s.impressions as second,
                    s.impressions - f.impressions as delta,
                    (ABS(f.impressions - s.impressions) / IF(f.impressions = 0, 1, f.impressions)) as deltaPercents
                '
            )
            ->get()
            ->keyBy('ad_group_id')
            ->toArray();
    }
}
