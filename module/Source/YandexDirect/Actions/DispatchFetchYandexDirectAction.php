<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions;

use App\Infrastructure\DateRange;
use DateInterval;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Module\Source\Sources\Actions\DispatchFetchAction;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Jobs\CheckBidModifiersJob;
use Module\Source\YandexDirect\Jobs\CheckKeywordsJob;
use Module\Source\YandexDirect\Jobs\CheckRetargetingJob;
use Module\Source\YandexDirect\Jobs\CheckSitelinksJob;
use Module\Source\YandexDirect\Jobs\FetchAccountsJob;
use Module\Source\YandexDirect\Jobs\FetchAdGroupsJob;
use Module\Source\YandexDirect\Jobs\FetchAdsJob;
use Module\Source\YandexDirect\Jobs\FetchCampaignsJob;
use Module\Source\YandexDirect\Jobs\FetchReportsJob;

final class DispatchFetchYandexDirectAction extends DispatchFetchAction
{
    public function execute(Source $source, ?DateInterval $delay = null, bool $isForce = false): void
    {
        $campaign = $source->campaign;
        $fromDate = self::getLastUpdatedDate($source->settings_id);

        $this->startFetching($source, [
            new FetchAccountsJob($campaign->id),
            new FetchCampaignsJob($campaign->id),
            new FetchAdGroupsJob($campaign->id),
            new FetchAdsJob($campaign->id),
            new CheckKeywordsJob($campaign->id),

            // Если одного типа может быть несколько источников, надо бы индекс источника закидывать
            new CheckSitelinksJob($source->id),
            new CheckRetargetingJob($source->id),
            new CheckBidModifiersJob($source->id),

            new FetchReportsJob($campaign->id, new DateRange($fromDate, Carbon::now()), Str::uuid()->toString()),
        ], $isForce, $delay);
    }

    private static function getLastUpdatedDate($settingsId): Carbon
    {
        /** @var string|null $lastUpdated */
        $lastUpdated = DB::table('yandex_direct_ad_reports')
            ->where('settings_id', $settingsId)
            ->min('date');

        if ($lastUpdated !== null) {
            return Carbon::parse($lastUpdated)->subDays(3);
        }

        return Carbon::now()->subDays(config('sources.initial_range_days', 14));
    }

    protected function onQueue(): string
    {
        return 'direct';
    }
}
