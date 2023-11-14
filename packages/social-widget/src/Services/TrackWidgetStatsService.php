<?php

declare(strict_types=1);

namespace SocialWidget\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use SocialWidget\Models\SocialWidget;

final class TrackWidgetStatsService
{
    public function incViews(SocialWidget $widget): void
    {
        $this->incValue($widget, 'views');
    }
    public function incClicks(SocialWidget $widget): void
    {
        $this->incValue($widget, 'clicks');
    }

    private function incValue(SocialWidget $widget, string $key): void
    {
        DB::insert("INSERT INTO sw_stats (widget_id, date) VALUES (?, ?) ON DUPLICATE KEY UPDATE $key = $key + 1;", [
            $widget->id,
            Carbon::now()->toDateString()
        ]);
    }
}
