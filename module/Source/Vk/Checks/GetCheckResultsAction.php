<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks;

use App\Infrastructure\DateRange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use Module\Campaign\Checks\Checks\CheckRule;
use Module\Source\Sources\Exceptions\UnsupportedSourceTypeException;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Checks\Account\LowBalanceCheckRule;
use Module\Source\Vk\Checks\Ads\AdInPauseCheckRule;
use Module\Source\Vk\Checks\Ads\BurnoutCreativesCheckRule;
use Module\Source\Vk\Checks\Ads\HasAllLimitCheckRule;
use Module\Source\Vk\Checks\Ads\HasDayLimitCheckRule;
use Module\Source\Vk\Checks\Ads\HasExcludeTargetCheckRule;
use Module\Source\Vk\Checks\Ads\HasSavingAudienceCheckRule;
use Module\Source\Vk\Checks\Ads\HasUtmCheckRule;
use Module\Source\Vk\Checks\Ads\HighShowBidsCheckRule;
use Module\Source\Vk\Checks\Ads\HighShowsRateCheckRule;
use Module\Source\Vk\Checks\Ads\RejectedAdCheckRule;
use Module\Source\Vk\Checks\Campaigns\HighExpensesCheckRule;
use Module\Source\Vk\Checks\Campaigns\LowImpressionsCheckRule;
use Module\Source\Vk\Checks\Campaigns\LowReachesCheckRule;
use Module\Source\Vk\Models\VkAdParam;
use Module\Source\Vk\Models\VkAdsStatistics;
use Module\Source\Vk\Models\VkCampaignParam;
use Module\Source\Vk\Models\VkSettings;
use Spatie\LaravelData\DataCollection;

final class GetCheckResultsAction
{
    /**
     * @return array{accounts: \Spatie\LaravelData\DataCollection|null, campaigns: \Spatie\LaravelData\DataCollection|null, ads: \Spatie\LaravelData\DataCollection|null}
     */
    #[ArrayShape([
        'accounts' => DataCollection::class,
        'campaigns' => DataCollection::class,
        'ads' => DataCollection::class,
    ])]
    public function execute(Source $vkSource, array $filters): array
    {
        UnsupportedSourceTypeException::throwIfTypeNotIn($vkSource, Source::TYPE_VK);

        /** @var VkSettings $settings */
        $settings = $vkSource->settings;

        $this->prepareRelations($settings, $filters);

        return [
            'accounts' => CheckRule::getResultsForObjects([$settings], [
                LowBalanceCheckRule::class,
            ]),
            'campaigns' => CheckRule::getResultsForObjects($settings->vkCampaigns, [
                LowReachesCheckRule::class,
                HighExpensesCheckRule::class,
                LowImpressionsCheckRule::class,
            ]),
            'ads' => CheckRule::getResultsForObjects($settings->vkAds, [
                HasDayLimitCheckRule::class,
                HasAllLimitCheckRule::class,
                RejectedAdCheckRule::class,
                HasUtmCheckRule::class,
                HighShowsRateCheckRule::class,
                BurnoutCreativesCheckRule::class,
                HighShowBidsCheckRule::class,
                AdInPauseCheckRule::class,
                HasExcludeTargetCheckRule::class,
                HasSavingAudienceCheckRule::class,
            ]),
        ];
    }

    private function prepareRelations(VkSettings $settings, array $filters): void
    {
        $vkCampaigns = $settings->vkCampaignsSel;
        if (isset($filters['status'])) {
            $vkCampaigns = $vkCampaigns->where('status', $filters['status']);
        }
        $vkCampaignIds = $vkCampaigns->pluck('id');

        $vkAds = $settings
            ->vkAdsSel
            ->whereIn('campaign_id', $vkCampaignIds);
        if (isset($filters['status'])) {
            $vkAds = $vkAds->where('status', $filters['status']);
        }

        $vkAdsStatistics = $settings
            ->vkAdsStatistics()
            ->whereIn('campaign_id', $vkCampaignIds)
            ->whereInDateRange(
                DateRange::fromArray([
                    Carbon::now()->subDays(60),
                    Carbon::now(),
                ]),
                'day'
            )
            ->get();

        $settings->setRelation('vkAds', $vkAds);
        $settings->setRelation('vkCampaigns', $vkCampaigns);
        $settings->setRelation('vkAdsStatistics', $vkAdsStatistics);

        $vkAdsByCampaign = $settings->vkAds->groupBy('campaign_id');
        $vkAdsStatisticsByCampaign = VkAdsStatistics::groupByCampaign($vkAdsStatistics, true);
        $vkAdsStatisticsByAd = $vkAdsStatistics->groupBy('ad_id');

        $settings->vkCampaigns->each(
            static function (VkCampaignParam $vkCampaign) use (
                $settings,
                $vkAdsByCampaign,
                $vkAdsStatisticsByCampaign,
                $vkAdsStatisticsByAd
            ): void {
                $vkCampaign->setRelation('settings', $settings);
                $vkCampaign->setRelation('vkAds', $vkAdsByCampaign[$vkCampaign->id] ?? null);
                $vkCampaign->setRelation('vkAdsStatistics', $vkAdsStatisticsByCampaign[$vkCampaign->id] ?? null);

                $vkCampaign->vkAds?->each(static function (VkAdParam $vkAd) use ($vkCampaign, $vkAdsStatisticsByAd): void {
                    $vkAd->setRelation('vkCampaign', $vkCampaign);

                    $stats = ($vkAdsStatisticsByAd[(string)$vkAd->id] ?? null)?->keyBy('day');
                    $vkAd->setRelation('vkAdsStatistics', $stats ?? Collection::empty());
                });
            }
        );
    }
}
