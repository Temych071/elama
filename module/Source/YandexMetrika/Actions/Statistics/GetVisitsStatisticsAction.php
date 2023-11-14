<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions\Statistics;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;

final class GetVisitsStatisticsAction
{
    public function execute(Source $source, DateRange $period): ?array
    {
        $res = DB::table('metrika_visits')
            ->selectRaw(
                '
                SUM(visits) as visits,
                SUM(new_users) as new_users,
                SUM(users) as visitors,
                AVG(page_depth) as depth,
                AVG(avg_visit_duration) as avg_visit_duration,
                AVG(bounce_rate) as bounce_rate,
                MAX(created_at) as last_update,
                SUM(IF(device_category = \'mobile\' OR device_category = \'tablet\', page_views, 0)) as mobile_traffic
            '
            )
            ->where('settings_id', $source->settings_id)
            ->whereDate('date', '>=', $period->getFrom())
            ->whereDate('date', '<=', $period->getTo())
            ->groupBy('settings_id')
            ->first();


        if ($res === null) {
            return [
                'visits' => 0,
                'new_users' => 0,
                'users' => 0,
                'depth' => 0,
                'avg_visit_duration' => 0,
                'bounce_rate' => 0,
                'last_update' => Carbon::now(),
                'mobile_traffic' => 0,
            ];
        }

        return (array)$res;
    }
}
