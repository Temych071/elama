<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Campaigns;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use Module\Source\Vk\Models\VkAdsStatistics;
use Module\Source\Vk\Models\VkCampaignParam;

final class LowReachesCheckRule extends VkCampaignCheckRule
{
    private const MIN_ALLOWED_STAT_DAYS = 8;
    private const MAX_CHECK_DAYS_NUM = 14;
    private const CUR_PERIOD_LENGTH = 3;

    protected bool $textFromLang = true;
    protected string $title = 'low-reaches.title';
    protected string $desc = 'low-reaches.desc';
    protected string $message = 'low-reaches.message';

    private readonly DateRange $curPeriod;
    private readonly DateRange $prePeriod;

    public function __construct()
    {
        $this->curPeriod = DateRange::fromArray([
            Carbon::yesterday()->subDays(self::CUR_PERIOD_LENGTH - 1),
            Carbon::yesterday(),
        ]);
        $this->prePeriod = DateRange::fromArray([
            $this->curPeriod->getTo()->subDays(self::MAX_CHECK_DAYS_NUM),
            $this->curPeriod->getTo()->subDay(),
        ]);

        $this->additionalLangParams['curPeriod'] = $this->curPeriod->formatByPreset('checks');
        $this->additionalLangParams['prePeriod'] = $this->prePeriod->formatByPreset('checks');
    }

    /**
     * @param  VkCampaignParam  $object
     */
    public function check($object): bool
    {
        $curReaches = VkAdsStatistics::getStatsByPeriod($object->vkAdsStatistics, $this->curPeriod)->pluck('reach')->avg();
        $preReaches = VkAdsStatistics::getStatsByPeriod($object->vkAdsStatistics, $this->prePeriod)->pluck('reach')->avg();

        if ($curReaches > $preReaches) {
            return true;
        }

        if (empty($curReaches) || empty($preReaches)) {
            return true;
        }

        return $this->calcDiffInPercents($curReaches, $preReaches) < 0.5;
    }

    /**
     * @param  VkCampaignParam  $object
     */
    public function canApplyRule($object): bool
    {
        return (
            VkAdsStatistics::isStatsHasAllDaysInPeriod($object->vkAdsStatistics, $this->curPeriod)
            && VkAdsStatistics::isStatsHasAllDaysInPeriod($object->vkAdsStatistics, $this->prePeriod)
        );
    }

    private function calcDiffInPercents(float|int $a, float|int $b): float|int
    {
        return abs(($a - $b) / (($a + $b) / 2));
    }
}
