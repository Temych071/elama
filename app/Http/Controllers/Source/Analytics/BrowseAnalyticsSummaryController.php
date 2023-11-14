<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Analytics;

use App\Infrastructure\DateRange;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Actions\GetAuditPageDataAction;
use Module\Campaign\Models\Campaign;
use Module\Source\GoogleAnalytics\Actions\Statistics\Summary\GetSummaryStatisticsAction;
use Module\Source\Sources\Actions\DataUpdate\SourceDataUpdateService;
use Module\Source\Sources\Models\Source;

final class BrowseAnalyticsSummaryController
{
    public function __invoke(Request $request, Campaign $campaign, SourceDataUpdateService $dataUpdateService): Response
    {
        $period = $this->getPeriod($request);

        $source = $campaign->googleAnalyticsSource;

        $statistics = app(GetSummaryStatisticsAction::class)
            ->execute($source, $period);

        return Inertia::render('Campaign/Browse', array_merge([
            'campaign' => $campaign,
            'activeCampaignId' => $campaign->id,
            'activeSourceType' => Source::TYPE_GOOGLE_ANALYTICS,
            'minDate' => $this->getMinDate($source),
            'data_status' => $dataUpdateService->getCampaignSourcesStatus($campaign),
            'data_update_available' => $dataUpdateService->isUpdateAvailable($campaign),
            'data_update_freq' => Source::MIN_UPDATE_INTERVAL,
            'data_next_refresh_at' => $dataUpdateService->getNextFetchingDate($campaign),
            'period' => $period,
            'dateRange' => $period,
            'periodLength' => $period->getLength(),
            'tableFieldsSettings' => $campaign->analytics_parameters,

            ...app(GetAuditPageDataAction::class)->execute($campaign),
            'settingsChecks' => $campaign->settings_checks,
        ], $statistics->toArray()));
    }

    private function getMinDate(Source $source): \Carbon\Carbon
    {
        $date = DB::table('analytics_visits')
            ->where('settings_id', $source->settings_id)
            ->min('date');

        if ($date !== null) {
            return Carbon::parse($date)->subDay();
        }

        return Carbon::now();
    }

    private function getPeriod(Request $request): DateRange
    {
        /** @var ?string $periodStr */
        $periodStr = $request->input('period', $request->input('dateRange'));

        if (empty($periodStr)) {
            return DateRange::parseFromAlias('30days');
        }

        return DateRange::parse($periodStr);
    }
}
