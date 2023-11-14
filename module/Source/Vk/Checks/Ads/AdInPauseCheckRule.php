<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use Module\Source\Vk\Models\VkAdParam;
use Module\Source\Vk\Models\VkAdsStatistics;

final class AdInPauseCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'ad-in-pause.title';
    protected string $desc = 'ad-in-pause.desc';
    protected string $message = 'ad-in-pause.message';

    private readonly DateRange $period;

    public function __construct()
    {
        $this->period = DateRange::fromArray([
            Carbon::today()->subDays(2),
            Carbon::today(),
        ]);

        $this->additionalLangParams['period'] = $this->period->formatByPreset('checks');
    }

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        $impressions = VkAdsStatistics::getStatsByPeriod($object->vkAdsStatistics, $this->period)
            ->sum('impressions') ?? 0;

        return !(
            $object->status === VkAdParam::STATUS_STOPPED
            && !$impressions
        );
    }
}
