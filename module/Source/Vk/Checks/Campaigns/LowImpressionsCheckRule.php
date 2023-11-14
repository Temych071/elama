<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Campaigns;

use App\Infrastructure\DateRange;
use DivisionByZeroError;
use Module\Source\Vk\Models\VkAdsStatistics;
use Module\Source\Vk\Models\VkCampaignParam;

final class LowImpressionsCheckRule extends VkCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'low-impressions.title';
    protected string $desc = 'low-impressions.desc';
    protected string $message = 'low-impressions.message';

    private readonly DateRange $curPeriod;
    private readonly DateRange $prePeriod;

    public function __construct()
    {
        $this->curPeriod = DateRange::fromOffset(-1, -4);
        $this->prePeriod = $this->curPeriod->getPrev();

        $this->additionalLangParams['curDay'] = $this->curPeriod->formatByPreset('checks');
        $this->additionalLangParams['preDay'] = $this->prePeriod->formatByPreset('checks');
    }

    /**
     * @param  VkCampaignParam  $object
     */
    public function check($object): bool
    {
        $statCur = VkAdsStatistics::getStatsByPeriod($object->vkAdsStatistics, $this->curPeriod)->sum(static fn ($it) => $it->impressions);
        $statPrev = VkAdsStatistics::getStatsByPeriod($object->vkAdsStatistics, $this->prePeriod)->sum(static fn ($it) => $it->impressions);

        if ($statCur > $statPrev) {
            return true;
        }

        return $this->calcDiffInPercents($statCur, $statPrev) < 0.5;
    }

    private function calcDiffInPercents(float|int $a, float|int $b): float|int
    {
        try {
            return abs(($a - $b) / (($a + $b) / 2));
        } catch (DivisionByZeroError) {
            return 0;
        }
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
}
