<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Fetch;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkAdParam;
use Module\Source\Vk\Models\VkAdsStatistics;
use Module\Source\Vk\Models\VkSettings;
use Module\Source\Vk\Services\VkService;

final class FetchStatisticsAction
{
    public function execute(Source $source, CarbonInterface $from): void
    {
        /** @var VkSettings $settings */
        $settings = $source->settings;

        $service = new VkService($source->authToken);

        $data = $this->fetchData($service, $settings, $from);

        $this->store($data, $settings->id, $from);
    }

    private function fetchData(VkService $service, VkSettings $settings, CarbonInterface $fromDate): array
    {
        $from = $fromDate->format('Y-m-d');
        $to = Carbon::now()->format('Y-m-d');

        $ads = VkAdParam::query()
            ->where('setting_id', $settings->id)
            ->limit(2000)
            ->pluck('campaign_id', 'id');

        if ($ads->isEmpty()) {
            return [];
        }

        $adIds = $ads->keys()->filter();

        if ($adIds->isEmpty()) {
            return [];
        }

        $statistics = $service->get('ads.getStatistics', [
            'account_id' => $settings->account->account_id,
            'ids_type' => 'ad',
            'ids' => $adIds->join(','),
            'period' => 'day',
            'date_from' => $from,
            'date_to' => $to,
        ]);

//        Log::build([
//            'driver' => 'single',
//            'path' => storage_path('logs/vkstat.log'),
//        ])->info('Raw vk stat', [
//            'settings_id' => $settings->id,
//            'account_id' => $accountId,
//            'now' => Carbon::now(),
//            'ads' => $ads,
//            'data' => $statistics,
//        ]);

        return $this->normalizeStat($statistics, $ads->toArray(), $settings->id);
    }

    private function store(array $data, int $settingsId, CarbonInterface $from): void
    {
        if ($data === []) {
            return;
        }

//        Log::build([
//            'driver' => 'single',
//            'path' => storage_path('logs/vkstat.log'),
//        ])->info('Store vk stat', [
//            'settings_id' => $settingsId,
//            'from' => $from,
//            'data' => $data,
//        ]);

        VkAdsStatistics::query()
            ->where('settings_id', $settingsId)
            ->whereDate('day', '>=', $from->format('Y-m-d'))
            ->delete();

        $data = array_chunk($data, 500);

        foreach ($data as $it) {
            VkAdsStatistics::insert($it);
        }
    }

    private function normalizeStat(array $statistics, array $ads, int $settingsId): array
    {
        $data = [];

        foreach ($statistics as $adStat) {
            foreach ($adStat['stats'] as $stat) {
                $data[] = [
                    'ad_id' => $adStat['id'],
                    'campaign_id' => $ads[$adStat['id']],
                    'settings_id' => $settingsId,
                    'clicks' => isset($stat['clicks']) ? (int)$stat['clicks'] : 0,
                    'impressions' => $stat['impressions'] ?? 0,
                    'uniq_views_count' => $stat['uniq_views_count'] ?? 0,
                    'reach' => $stat['reach'] ?? 0,
                    'spent' => (int)(isset($stat['spent']) ? ((float)$stat['spent']) * 100 : 0),
                    'join_rate' => isset($stat['join_rate']) ? (int)$stat['join_rate'] : null,
                    'day' => $stat['day'],
                    'message_sends' => (int)($stat['message_sends'] ?? null),
                    'message_sends_by_any_user' => (int)($stat['message_sends_by_any_user'] ?? null),
                ];
            }
        }

        return $data;
    }
}
