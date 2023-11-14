<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions;

use DateInterval;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Actions\DispatchFetchAction;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Jobs\FetchConversionsJob;
use Module\Source\YandexMetrika\Jobs\FetchEcommerceJob;
use Module\Source\YandexMetrika\Jobs\FetchVisitsJob;

final class DispatchFetchMetricsAction extends DispatchFetchAction
{
    public function execute(Source $source, ?DateInterval $delay = null, bool $isForce = false): void
    {
        $fromDate = self::getLastUpdatedDate($source->settings_id);

        $this->startFetching($source, [
            new FetchConversionsJob($source->id, $fromDate),
            new FetchVisitsJob($source->id, $fromDate),
            new FetchEcommerceJob($source->id, $fromDate),
        ], $isForce, $delay);
    }

    private static function getLastUpdatedDate($settingsId): Carbon
    {
        return self::getLastDate('metrika_conversions', $settingsId)
            ?->min(self::getLastDate('metrika_visits', $settingsId))
            ?->min(self::getLastDate('yandex_metrika_ecommerce', $settingsId))
            ?->subDay() ?? Carbon::now()->subDays(config('sources.initial_range_days', 14));
    }

    private static function getLastDate(string $table, int $settingsId): ?Carbon
    {
        return Carbon::make(DB::table($table)
            ->where('settings_id', $settingsId)
            ->max('date'));
    }

    protected function onQueue(): string
    {
        return 'metrika';
    }
}
