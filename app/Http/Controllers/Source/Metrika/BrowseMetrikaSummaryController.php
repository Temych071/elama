<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Metrika;

use App\Infrastructure\DateRange;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Actions\GetAuditAction;
use Module\Campaign\Actions\GetAuditPageDataAction;
use Module\Campaign\Models\Campaign;
use Module\LinkChecker\Models\SeoAdsAudit;
use Module\LinkChecker\Models\SeoAudit;
use Module\Source\Sources\Actions\DataUpdate\SourceDataUpdateService;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Actions\Statistics\GetSummaryStatisticsAction;

final class BrowseMetrikaSummaryController
{
    public function __invoke(Request $request, Campaign $campaign, SourceDataUpdateService $dataUpdateService): Response
    {
        $period = $this->getPeriod($request);
        $source = $campaign->metrikaSource;

        if (!is_null($source)) {
            $statistics = app(GetSummaryStatisticsAction::class)
                ->execute($source, $period);
            $dataStatus = $dataUpdateService->getCampaignSourcesStatus($campaign);
        } else {
            $statistics = null;
            $dataStatus = null;
        }

        return Inertia::render(
            'Campaign/Browse',
            array_merge([
                'activeCampaignId' => $campaign->id,
                'activeSourceType' => is_null($source) ? null : Source::TYPE_YANDEX_METRIKA,
                'minDate' => $this->getMinDate($source),
                'data_status' => $dataStatus,
                'data_update_available' => $dataUpdateService->isUpdateAvailable($campaign),
                'data_update_freq' => Source::MIN_UPDATE_INTERVAL,
                'data_next_refresh_at' => $dataUpdateService->getNextFetchingDate($campaign),
                'period' => $period,
                'dateRange' => $period,
                'periodLength' => $period->getLength(),
                'tableFieldsSettings' => $campaign->analytics_parameters,

                ...app(GetAuditPageDataAction::class)->execute($campaign),
            'settingsChecks' => $campaign->settings_checks,
            ], $statistics?->toArray() ?? [])
        );
    }

    public function getAudit(Campaign $campaign)
    {
        return app(GetAuditAction::class)->handle($campaign);
    }

    private function getMinDate(?Source $source): ?CarbonInterface
    {
        if (is_null($source)) {
            return null;
        }

        $date = DB::table('metrika_visits')
            ->where('settings_id', $source->settings_id)
            ->min('date');

        if ($date === null) {
            return null;
        }

        return Carbon::parse($date)->subDay();
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
