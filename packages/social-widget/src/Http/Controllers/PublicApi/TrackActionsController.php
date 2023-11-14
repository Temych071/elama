<?php

declare(strict_types=1);

namespace SocialWidget\Http\Controllers\PublicApi;

use SocialWidget\Models\SocialWidget;
use SocialWidget\Services\TrackWidgetStatsService;

final class TrackActionsController
{
    public function trackView(string $uuid): string
    {
        /** @var SocialWidget $widget */
        $widget = SocialWidget::query()->findOrFail($uuid);
        app(TrackWidgetStatsService::class)->incViews($widget);
        return 'OK';
    }

//    public function trackClick(string $uuid): string
//    {
//        /** @var SocialWidget $widget */
//        $widget = SocialWidget::query()->findOrFail($uuid);
//        app(TrackWidgetStatsService::class)->incClicks($widget);
//        return 'OK';
//    }
}
