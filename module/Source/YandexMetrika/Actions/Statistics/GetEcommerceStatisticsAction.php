<?php

namespace Module\Source\YandexMetrika\Actions\Statistics;

use App\Infrastructure\DateRange;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;

class GetEcommerceStatisticsAction
{
    public function execute(Source $source, DateRange $period)
    {
        $res = DB::table('yandex_metrika_ecommerce')
            ->selectRaw(
                '
                SUM(ecommerce_purchases) as purchases,
                SUM(ecommerce_revenue) as income
                '
            )
            ->where('settings_id', $source->settings_id)
            ->whereDate('date', '>=', $period->getFrom())
            ->whereDate('date', '<=', $period->getTo())
            ->groupBy('settings_id')
            ->first();

        if ($res === null) {
            return [
                'purchases' => 0,
                'income' => 0,
            ];
        }

        return (array)$res;
    }
}
