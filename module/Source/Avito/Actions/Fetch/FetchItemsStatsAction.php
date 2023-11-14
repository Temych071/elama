<?php

declare(strict_types=1);

namespace Module\Source\Avito\Actions\Fetch;

use App\Infrastructure\DateRange;
use Module\Source\Avito\Models\AvitoItem;
use Module\Source\Avito\Models\AvitoItemStats;
use Module\Source\Avito\Services\ItemsAvitoService;
use Module\Source\Sources\Models\Source;

final class FetchItemsStatsAction
{
    public function execute(Source $source, DateRange $dateRange): void
    {
        $service = new ItemsAvitoService($source->authToken);

        $itemIds = AvitoItem::query()
            ->where('source_id', $source->id)
            ->pluck('id');

        foreach ($itemIds->chunk(200) as $itemIds) {
            $stats = $service->getItemsStats($dateRange, $itemIds->toArray());
            $stats = array_reduce($stats, static function ($res, $item) use ($source) {
                foreach ($item['stats'] as $statsForDay) {
                    $res[] = [
                        'source_id' => $source->id,
                        'item_id' => $item['itemId'],
                        'views' => $statsForDay['uniqViews'],
                        'favorites' => $statsForDay['uniqFavorites'],
                        'contacts' => $statsForDay['uniqContacts'],
                        'date' => $statsForDay['date'],
                    ];
                }
                return $res;
            }, []);

            AvitoItemStats::query()
                ->upsert($stats, [
                    'source_id', 'item_id', 'date',
                ], [
                    'views',
                    'favorites',
                    'contacts',
                ]);
        }
    }
}
