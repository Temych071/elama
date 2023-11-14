<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Fetch;

use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkAdParam;

final class CheckAdsTargetAction

{
    public function execute(Source $source): void
    {
        $vkAds = $source->settings->vkAdsSel;

        $adsTarget = collect(app(GetAdsTargetingAction::class)->execute($source))
            ->groupBy('id')
            ->toArray();

        $vkAds->each(static function (VkAdParam $vkAd) use ($adsTarget): void {
            $vkAd->has_retargeting_groups_not = false;
            foreach ($adsTarget[$vkAd->id] ?? [] as $adTargeting) {
                if (!empty($adTargeting['retargeting_groups_not'])) {
                    $vkAd->has_retargeting_groups_not = true;
                    break;
                }
            }
            $vkAd->save();
        });
    }
}
