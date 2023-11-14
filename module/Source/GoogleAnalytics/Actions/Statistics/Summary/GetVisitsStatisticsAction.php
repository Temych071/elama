<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\Statistics\Summary;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;

final class GetVisitsStatisticsAction
{
    public function execute(Source $source, DateRange $period): ?array
    {
        $res = DB::table('analytics_visits')
            ->selectRaw('
                SUM(sessions) as visits,
                SUM(new_users) as new_users,
                SUM(sessions) as visitors,
                SUM(page_views) / IF(SUM(sessions) != 0, SUM(sessions), 1) as depth,
                AVG(session_duration) as avg_visit_duration,
                (100 * SUM(bounces) / SUM(page_views)) as bounce_rate,
                MAX(updated_at) as last_update,
                SUM(IF(device_category = \'mobile\' OR device_category = \'tablet\', page_views, 0)) as mobile_traffic
            ')
            ->where('settings_id', $source->settings_id)
            ->whereDate('date', '>=', $period->getFrom())
            ->whereDate('date', '<=', $period->getTo())
            ->groupBy('settings_id')
            ->first();

        if ($res === null) {
            return [
                'visits' => 0,
                'new_users' => 0,
                'visitors' => 0,
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
