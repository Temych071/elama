<?php

declare(strict_types=1);

namespace Module\Campaign\Actions;

use App\Infrastructure\DateRange;
use Illuminate\Support\Facades\DB;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;

final class GetVisitsCountAction
{
    private const SOURCES_PRIORITY = [
        Source::TYPE_YANDEX_METRIKA,
        Source::TYPE_GOOGLE_ANALYTICS,
        Source::TYPE_YANDEX_DIRECT,
        Source::TYPE_VK,
    ];

    public function execute(Campaign $campaign): int
    {
        $period = DateRange::parseFromAlias('30days');

        $sources = $campaign->sources->groupBy('settings_type');

        foreach (self::SOURCES_PRIORITY as $sourceType) {
            if (!$sources->has($sourceType)) {
                continue;
            }

            $settingsIds = $sources->get($sourceType)->pluck('settings_id');

            return  (int) match ($sourceType) {
                Source::TYPE_YANDEX_METRIKA =>
                DB::table('metrika_visits')
                    ->whereIn('settings_id', $settingsIds)
                    ->whereInDateRange($period)
                    ->sum('visits'),

                Source::TYPE_GOOGLE_ANALYTICS =>
                DB::table('analytics_visits')
                    ->whereIn('settings_id', $settingsIds)
                    ->whereInDateRange($period)
                    ->sum('sessions'),

                Source::TYPE_YANDEX_DIRECT =>
                DB::table('yandex_direct_ad_reports')
                    ->whereIn('settings_id', $settingsIds)
                    ->whereInDateRange($period)
                    ->sum('clicks'),

                Source::TYPE_VK =>
                DB::table('vk_ads_statistics')
                    ->whereIn('settings_id', $settingsIds)
                    ->whereInDateRange($period, 'day')
                    ->sum('clicks'),
            };
        }

        return 0;
    }
}
