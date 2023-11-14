<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions\Statistics;

use App\Infrastructure\DateRange;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;

final class GetRegionStatisticsAction
{
    /**
     * @return array{city_reaches: mixed[], city_new_users: mixed[]}
     */
    public function execute(Source $source, DateRange $period): ?array
    {
        /** @var MetrikaSourceSettings $settings */
        $settings = $source->settings;

        $goals = $settings !== null ? array_map(static fn ($goal): int => (int)$goal['id'], $settings->goals->toArray()) : [];

        $q = DB::table('metrika_conversions')
            ->whereNotNull('city')
            ->where('settings_id', $source->settings_id)
            ->whereDate('date', '>=', $period->getFrom())
            ->whereDate('date', '<=', $period->getTo())
            ->whereIn('goal_id', $goals)
            ->groupBy('city')
            ->limit(5);

        return [
            'city_reaches' => $q->clone()
                ->selectRaw('city, SUM(reaches) as reaches')
                ->orderByDesc('reaches')
                ->get()->toArray(),
//            'city_conversion_rate' => $q
//                ->selectRaw('city, AVG(conversion_rate) as conversion_rate')
//                ->orderByDesc('conversion_rate')
//                ->get()->toArray(),
            'city_new_users' => DB::table('metrika_visits')
                ->selectRaw('city, SUM(new_users) AS new_users')
                ->orderByDesc('new_users')
                ->whereNotNull('city')
                ->where('settings_id', $source->settings_id)
                ->whereDate('date', '>=', $period->getFrom())
                ->whereDate('date', '<=', $period->getTo())
                ->groupBy('city')
                ->limit(5)
                ->get()->toArray(),
        ];
    }
}
