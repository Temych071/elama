<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use App\Infrastructure\DateRange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Module\Source\Vk\Models\VkAdParam;
use Module\Source\Vk\Models\VkAdsStatistics;

final class BurnoutCreativesCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'burnout-creatives.title';
    protected string $desc = 'burnout-creatives.desc';
    protected string $message = 'burnout-creatives.message';

    private readonly DateRange $prePeriod;
    private readonly DateRange $curPeriod;

    public function __construct()
    {
        $this->curPeriod = DateRange::fromArray([
            Carbon::yesterday()->subDays(2),
            Carbon::yesterday(),
        ]);

        $this->prePeriod = DateRange::fromArray([
            Carbon::yesterday()->subDays(6),
            Carbon::yesterday()->subDays(3),
        ]);

        $this->additionalLangParams['curPeriod'] = $this->curPeriod->formatByPreset('checks');
        $this->additionalLangParams['prePeriod'] = $this->prePeriod->formatByPreset('checks');
    }

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        $first = $this->averageCoverage(VkAdsStatistics::getStatsByPeriod($object->vkAdsStatistics, $this->curPeriod));
        $second = $this->averageCoverage(VkAdsStatistics::getStatsByPeriod($object->vkAdsStatistics, $this->prePeriod));

        if (empty($first) && empty($second)) {
            return true;
        }

        if ($first === 0.0) {
            $first = 1.0;
        }

        return (1 - ($second / $first)) <= 0.3;
    }

    private function averageCoverage(Collection $stats): float|int|null
    {
        return $stats->avg('uniq_views_count');
//        $views = 0;
//
//        /** @var VkAdsStatistics $dayStats */
//        foreach ($stats as $dayStats) {
//            $views += $dayStats->uniq_views_count;
//        }
//
//        if ($views === 0) {
//            return 0;
//        }
//        return $views / $stats->average();
    }

    private function calcCtr(Collection $stats): float
    {
        $views = 0;
        $clicks = 0;

        /** @var VkAdsStatistics $dayStats */
        foreach ($stats as $dayStats) {
            $views += $dayStats->impressions;
            $clicks += $dayStats->clicks;
        }

        if ($views === 0) {
            return 0;
        }

        return $clicks / $views;
    }

    /**
     * @param  VkAdParam  $object
     */
    public function canApplyRule($object): bool
    {
        if (is_null($object->vkAdsStatistics)) {
            return false;
        }

        return (
            VkAdsStatistics::isStatsHasAllDaysInPeriod($object->vkAdsStatistics, $this->prePeriod)
            && VkAdsStatistics::isStatsHasAllDaysInPeriod($object->vkAdsStatistics, $this->curPeriod)
        );
    }
}
