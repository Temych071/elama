<?php

declare(strict_types=1);

namespace Module\Source\Avito\Actions\Fetch;

use App\Infrastructure\DateRange;
use DateInterval;
use Illuminate\Support\Carbon;
use Module\Source\Avito\Jobs\FetchItemsExpensesJob;
use Module\Source\Avito\Jobs\FetchItemsJob;
use Module\Source\Avito\Jobs\FetchItemsStatsJob;
use Module\Source\Avito\Models\AvitoItem;
use Module\Source\Avito\Models\AvitoItemStats;
use Module\Source\Sources\Actions\DispatchFetchAction;
use Module\Source\Sources\Models\Source;

final class DispatchFetchAvitoAction extends DispatchFetchAction
{
    public function execute(Source $source, ?DateInterval $delay = null, bool $isForce = false): void
    {
        $dateRange = DateRange::fromArray([self::getLastUpdatedDate($source), now()]);

        $this->startFetching($source, [
            new FetchItemsJob($source->id),
            new FetchItemsStatsJob($source->id, $dateRange),
            new FetchItemsExpensesJob($source->id, $dateRange),
        ], $isForce, $delay);
    }

    private static function getLastUpdatedDate(Source $source): Carbon
    {
        $lastUpdated = AvitoItemStats::query()
            ->whereIn(
                'item_id',
                AvitoItem::query()
                    ->where('source_id', $source->id)
                    ->select('id')
            )
            ->max('date');

        if ($lastUpdated !== null) {
            return Carbon::parse($lastUpdated)->subDays(21);
        }

        return Carbon::now()->subDays(config('sources.initial_range_days', 14));
    }

    protected function onQueue(): string
    {
        return 'avito';
    }
}
