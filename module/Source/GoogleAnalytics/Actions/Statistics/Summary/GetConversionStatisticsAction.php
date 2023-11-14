<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\Statistics\Summary;

use App\Infrastructure\DateRange;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;

final class GetConversionStatisticsAction
{
    public function execute(Source $source, DateRange $period): ?array
    {
        $res = DB::table('analytics_conversions')
            ->selectRaw('SUM(completions) as reaches')
            ->where('settings_id', $source->settings_id)
            ->whereDate('date', '>=', $period->getFrom())
            ->whereDate('date', '<=', $period->getTo())
            ->groupBy('settings_id')
            ->first();

        if ($res === null) {
            return [
                'reaches' => 0,
                'conversion_rate' => 0,
            ];
        }

        return (array)$res;
    }
}
