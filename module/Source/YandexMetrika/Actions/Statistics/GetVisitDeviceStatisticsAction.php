<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions\Statistics;

use App\Infrastructure\DateRange;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;

final class GetVisitDeviceStatisticsAction
{
    public function execute(Source $source, DateRange $period): ?array
    {
        $res = DB::table('metrika_visits')
            ->selectRaw(
                '
                device_category,
                SUM(visits) as visits_count
            '
            )
            ->where('settings_id', $source->settings_id)
            ->whereDate('date', '>=', $period->getFrom())
            ->whereDate('date', '<=', $period->getTo())
            ->groupBy('device_category')
            ->get();

        if ($res === null) {
            return [
                'desktop' => 0,
            ];
        }

        return $res
            ->keyBy('device_category')
            ->map(fn ($item): int => (int)$item->visits_count)
            ->toArray();
    }
}
