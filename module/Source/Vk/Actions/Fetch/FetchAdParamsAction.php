<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Fetch;

use App\Exceptions\BusinessException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkAdParam;
use Module\Source\Vk\Models\VkCampaignParam;
use Module\Source\Vk\Models\VkSettings;
use Module\Source\Vk\Services\VkService;

final class FetchAdParamsAction
{
    private VkService $service;

    public function execute(Source $source): void
    {
        $this->service = new VkService($source->authToken);

        /** @var VkSettings $settings */
        $settings = $source->settings;

//        Log::build([
//            'driver' => 'single',
//            'path' => storage_path('logs/vk-fetch-ad-params.log'),
//        ])->info('vk start fetching', [
//            'settings_id' => $settings->id,
//            'account_id' => $settings->account->account_id,
//            'client_id' => $settings->client?->id,
//            'now' => Carbon::now(),
//        ]);

        $this->updateCampaigns($settings);

        sleep(3); // чтобы избежать flood control
        $this->updateAds($settings);

//        Log::build([
//            'driver' => 'single',
//            'path' => storage_path('logs/vk-fetch-ad-params.log'),
//        ])->info('vk after fetching', [
//            'settings_id' => $settings->id,
//            'account_id' => $settings->account->account_id,
//            'client_id' => $settings->client?->id,
//            'now' => Carbon::now(),
//        ]);
    }

    private function updateCampaigns(VkSettings $settings): void
    {
        $now = Carbon::now();

        $campaigns = $this->service->getCampaignsRaw($settings->account->account_id, $settings->client?->id);

//        Log::build([
//            'driver' => 'single',
//            'path' => storage_path('logs/vk-fetch-ad-params.log'),
//        ])->info('vk raw campaigns', [
//            'settings_id' => $settings->id,
//            'account_id' => $settings->account->account_id,
//            'client_id' => $settings->client?->id,
//            'now' => Carbon::now(),
//            'data' => $campaigns,
//        ]);

        $campaigns = array_map(static fn($item) => array_merge($item, [
            'uuid' => Str::uuid(),
            'setting_id' => $settings->id,
            'created_at' => $now,
        ]), $campaigns);

        VkCampaignParam::updateAll($campaigns, $settings->id);
    }

    private function updateAds(VkSettings $settings): void
    {
        $adsChunks = array_chunk($this->fetchAds($settings), 500);

//        Log::build([
//            'driver' => 'single',
//            'path' => storage_path('logs/vk-fetch-ad-params.log'),
//        ])->info('vk fetched ads', [
//            'settings_id' => $settings->id,
//            'account_id' => $settings->account->account_id,
//            'client_id' => $settings->client?->id,
//            'now' => Carbon::now(),
//            'data' => $adsChunks,
//        ]);

        VkAdParam::where('setting_id', $settings->id)->delete();

//        Log::build([
//            'driver' => 'single',
//            'path' => storage_path('logs/vk-fetch-ad-params.log'),
//        ])->info('vk ads deleted', [
//            'settings_id' => $settings->id,
//            'account_id' => $settings->account->account_id,
//            'client_id' => $settings->client?->id,
//            'now' => Carbon::now(),
//        ]);

        foreach ($adsChunks as $chunk) {
            VkAdParam::insertFillable($chunk);
        }
    }

    private function fetchAds(VkSettings $settings): array
    {
        $now = Carbon::now();
        $accountId = $settings->account->account_id;

        [
            'ads' => $ads,
            'layouts' => $layouts,
        ] = $this->fetchAdsForClient($accountId, $settings->client?->id);

//        Log::build([
//            'driver' => 'single',
//            'path' => storage_path('logs/vk-fetch-ad-params.log'),
//        ])->info('vk raw ads', [
//            'settings_id' => $settings->id,
//            'account_id' => $settings->account->account_id,
//            'client_id' => $settings->client?->id,
//            'now' => Carbon::now(),
//            'data' => [
//                'ads' => $ads,
//                'layouts' => $layouts,
//            ],
//        ]);

        return array_map(static function ($item) use ($settings, $now, $layouts): array {
            $layout = Arr::first($layouts, static fn ($it): bool => $it['id'] === $item['id']);
            $layout['uuid'] = Str::uuid();
            $layout['setting_id'] = $settings->id;
            $layout['created_at'] = $now;

            foreach ($item as $key => $value) {
                if (is_array($value)) {
                    $layout[$key] = json_encode($value, JSON_THROW_ON_ERROR);
                }
            }

            return array_merge($item, $layout);
        }, $ads);
    }

    public function findLayoutById(array $layouts, int $adId): array
    {
        foreach ($layouts as $layout) {
            if ($layout['id'] === $adId) {
                return $layout;
            }
        }

        throw new BusinessException('Ad layout is not found');
    }

    private function fetchAdsForClient(int $accountId, ?int $clientId): array
    {
        if ($clientId === null) {
            return $this->service->execute(
                <<<EOT
                return {
                    ads: API.ads.getAds({
                        account_id: $accountId,
                    }),
                    layouts: API.ads.getAdsLayout({
                        account_id: $accountId,
                    }),
                };
            EOT
            );
        }

        return $this->service->execute(
            <<<EOT
                return {
                    ads: API.ads.getAds({
                        account_id: $accountId,
                        client_id: $clientId,
                    }),
                    layouts: API.ads.getAdsLayout({
                        account_id: $accountId,
                        client_id: $clientId,
                    }),
                };
            EOT
        );
    }
}
