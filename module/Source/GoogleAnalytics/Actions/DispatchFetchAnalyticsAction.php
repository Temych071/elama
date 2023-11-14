<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions;

use DateInterval;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\Source\GoogleAnalytics\Jobs\FetchConversionsJob;
use Module\Source\GoogleAnalytics\Jobs\FetchVisitsJob;
use Module\Source\Sources\Actions\DispatchFetchAction;
use Module\Source\Sources\Models\Source;

final class DispatchFetchAnalyticsAction extends DispatchFetchAction
{
    public function execute(Source $source, ?DateInterval $delay = null, bool $isForce = false): void
    {
        $fromDate = $this->getLastUpdatedDate($source->settings_id);

        $this->startFetching($source, [
            new FetchConversionsJob($source, $fromDate),
            new FetchVisitsJob($source, $fromDate),
        ], $isForce, $delay);
    }

    private function getLastUpdatedDate(int $settingsId): Carbon
    {
        /** @var string|null $lastUpdated */
        $lastUpdated = DB::table('analytics_conversions')
            ->where('settings_id', $settingsId)
            ->min('date');

        if ($lastUpdated !== null) {
            return Carbon::parse($lastUpdated)->subDay();
        }

        return Carbon::now()->subDays(config('sources.initial_range_days', 14));
    }

    protected function onQueue(): string
    {
        return 'analytics';
    }
}
