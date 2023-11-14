<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions\Statistics;

use App\Infrastructure\DateRange;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;

final class GetConversionStatisticsAction
{
    public function execute(Source $source, DateRange $period): ?array
    {
        /** @var MetrikaSourceSettings $settings */
        $settings = $source->settings;

        $goals = $settings !== null ? array_map(static fn ($goal): int => (int)$goal['id'], $settings->goals->toArray()) : [];

        $res = DB::table('metrika_conversions')
            ->selectRaw('SUM(reaches) as reaches')
            ->where('settings_id', $source->settings_id)
            ->whereDate('date', '>=', $period->getFrom())
            ->whereDate('date', '<=', $period->getTo())
            ->whereIn('goal_id', $goals)
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
