<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use Module\Source\Vk\Models\VkAdParam;
use Module\Source\Vk\Models\VkAdsStatistics;

final class HighShowsRateCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'high-shows-rate.title';
    protected string $desc = 'high-shows-rate.desc';
    protected string $message = 'high-shows-rate.message';

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        $views = 0;
        $users = 0;

        /** @var VkAdsStatistics $dayStats */
        foreach ($object->vkAdsStatistics->slice(0, 14) as $dayStats) {
            $views += $dayStats->impressions;
            $users += $dayStats->uniq_views_count;
        }

        return $views / max($users, 1) <= 4;
    }

    /**
     * @param  VkAdParam  $object
     */
    public function canApplyRule($object): bool
    {
        return !is_null($object->vkAdsStatistics);
    }
}
