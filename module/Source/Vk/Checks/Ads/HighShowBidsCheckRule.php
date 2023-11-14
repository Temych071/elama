<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use Module\Source\Vk\Models\VkAdParam;
use Module\Source\Vk\Models\VkAdsStatistics;

final class HighShowBidsCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'high-show-bids.title';
    protected string $desc = 'high-show-bids.desc';
    protected string $message = 'high-show-bids.message';

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        /** @var VkAdsStatistics $dayStats */
        foreach ($object->vkAdsStatistics->slice(0, 2) as $dayStats) {
            if (!$this->isHighCpm($dayStats)) {
                return true;
            }
        }

        return false;
    }

    private function isHighCpm(VkAdsStatistics $stat): bool
    {
        if ($stat->impressions === 0) {
            return false;
        }

        return (($stat->spent / 100) / $stat->impressions) * 1000 > 300.0;
    }

    /**
     * @param  VkAdParam  $object
     */
    public function canApplyRule($object): bool
    {
        return !is_null($object->vkAdsStatistics);
    }
}
