<?php

declare(strict_types=1);

namespace Reviews\Actions;

use App\Infrastructure\DateRange;
use Illuminate\Support\Facades\DB;
use Reviews\DTO\StatsSourceData;
use Reviews\Models\ReviewForm;
use Reviews\Parsers\Contracts\StatsService;
use Reviews\Parsers\Dto\ExternalStats;

final class FetchExternalStatsAction
{
    public function execute(
        ReviewForm $reviewForm,
        StatsSourceData $source,
        DateRange $dateRange,
    ): void {
        /** @var StatsService $service */
        $service = app($source->serviceClass);

        $stats = array_map(static fn(ExternalStats $dayStats) => [
            'review_form_id' => $reviewForm->id,
            'source' => $source->source->value,
            'date' => $dayStats->date->format('Y-m-d'),
            'views' => $dayStats->views,
            'calls' => $dayStats->calls,
            'routes' => $dayStats->routes,
            'site' => $dayStats->site,
        ], $service->getStats($source->placeId, $dateRange));

        DB::table('review_external_stats')->upsert($stats, [
            'review_form_id', // Хотя для мускла толку от этого мало)
            'source',
            'date',
        ]);
    }
}
