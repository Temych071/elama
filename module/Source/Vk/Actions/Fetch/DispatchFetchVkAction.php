<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Fetch;

use DateInterval;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Actions\DispatchFetchAction;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Jobs\CheckExcludeTargetingJob;
use Module\Source\Vk\Jobs\FetchAdParamsJob;
use Module\Source\Vk\Jobs\FetchStatisticsJob;

final class DispatchFetchVkAction extends DispatchFetchAction
{
    public function execute(Source $source, ?DateInterval $delay = null, bool $isForce = false): void
    {
        $fromDate = self::getLastUpdatedDate($source->settings_id);

        $this->startFetching($source, [
            new FetchAdParamsJob($source->id),
            new FetchStatisticsJob($source->id, $fromDate),
            new CheckExcludeTargetingJob($source->id),
        ], $isForce, $delay);
    }

    private static function getLastUpdatedDate($settingsId): Carbon
    {
        /** @var string|null $lastUpdated */
        $lastUpdated = DB::table('vk_ads_statistics')
            ->where('settings_id', $settingsId)
            ->max('day');

        if ($lastUpdated !== null) {
            return Carbon::parse($lastUpdated)->subDays(21);
        }

        return Carbon::now()->subDays(config('sources.initial_range_days', 14));
    }

    protected function onQueue(): string
    {
        return 'vk';
    }
}
