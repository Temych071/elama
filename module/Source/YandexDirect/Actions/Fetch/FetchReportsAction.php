<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions\Fetch;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Module\Campaign\Models\Campaign;
use Module\Source\YandexDirect\Exceptions\YandexDirectReportNotReadyException;
use Module\Source\YandexDirect\Services\YandexDirectService;

final class FetchReportsAction
{
    public const REPORT_TYPE = 'AD_PERFORMANCE_REPORT';

    public function execute(Campaign $campaign, DateRange $dateRange, string $reportName = null): bool
    {
        $source = $campaign->yandexDirectSource;
        $settingsId = $source->settings_id;
        $service = new YandexDirectService($source->authToken);

        try {
            $res = $service->reports(
                name: $reportName,
                type: self::REPORT_TYPE,
                dateRange: $dateRange,
                fields: [
                    'Date',
                    'AdId',
                    'AdGroupId',
                    'AdGroupName',
                    'CampaignId',
                    'Clicks',
                    'Cost',
                    'Impressions',
                    'AvgPageviews',
                    'Device',
                    'Sessions',
                    'BounceRate',
                    'AdFormat',
                ],
            );
        } catch (YandexDirectReportNotReadyException) {
            return false;
        }

        $now = Carbon::now();

        collect($res)
            ->map(fn ($item): array => [
                'settings_id' => $settingsId,

                'date' => $item['Date'],

                'ad_id' => (int)$item['AdId'],
                'ad_format' => $item['AdFormat'],

                'ad_group_id' => (int)$item['AdGroupId'],
                'ad_group_name' => $item['AdGroupName'],

                'campaign_id' => (int)$item['CampaignId'],

                'device' => $item['Device'],

                'clicks' => (int)$item['Clicks'],
                'cost' => (int)$item['Cost'],
                'impressions' => (int)$item['Impressions'],
                'sessions' => $this->fixSession($item['Sessions']),
                'avg_page_views' => $this->fixBounceRate($item['AvgPageviews']),
                'bounce_rate' => $this->fixBounceRate($item['BounceRate']),

                'uniq_hash' => self::makeUniqHash($settingsId, $item),

                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->groupBy('uniq_hash')
            ->map(function (Collection $it): array {
                $res = $it->first();

                $res['clicks'] = $it->sum('clicks');
                $res['cost'] = $it->sum('cost');
                $res['impressions'] = $it->sum('impressions');
                $res['sessions'] = $it->sum('sessions');
                $res['avg_page_views'] = $it->avg('avg_page_views');
                $res['bounce_rate'] = $it->avg('bounce_rate');

                return $res;
            })
            ->chunk(500)
            ->each(fn (Collection $it): int => DB::table('yandex_direct_ad_reports')
                ->upsert(
                    values: $it->toArray(),
                    uniqueBy: ['uniq_hash'],
                    update: [
                        'ad_format',
                        'ad_group_name',
                        'clicks',
                        'cost',
                        'impressions',
                        'avg_page_views',
                        'sessions',
                        'bounce_rate',
                        'updated_at',
                    ],
                ));

        return true;
    }

    /** метрика почему то отдает bounce rate -100 */
    private function fixBounceRate($val): float
    {
        $rate = (float)$val;

        if ($rate < 0) {
            return 0.0;
        }

        return $rate;
    }

    /** метрика почему то отдает bounce rate -100 */
    private function fixSession($val): int
    {
        $rate = (int)$val;

        if ($rate < 0) {
            return 0;
        }

        return $rate;
    }

    private static function makeUniqHash(int $settings_id, array $row): string
    {
        return hash(
            'sha256',
            $settings_id .
            $row['Date'] .
            $row['AdId'] .
            $row['AdGroupId'] .
            $row['CampaignId'] .
            $row['Device'] .
            $row['AdFormat']
        );
    }
}
