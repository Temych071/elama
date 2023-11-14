<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions;

use Illuminate\Support\Facades\DB;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Data\CampaignData;
use Module\Source\YandexDirect\Models\YandexDirectSettings;
use Spatie\LaravelData\DataCollection;
use Throwable;

final class AddYandexDirectSettingsAction
{
    /**
     * @param  DataCollection<int, CampaignData>|null  $directCampaigns
     * @throws Throwable
     */
    public function execute(Campaign $campaign, ?DataCollection $directCampaigns): YandexDirectSettings
    {
        /** @var Source $source */
        $source = $campaign->yandexDirectSource()->firstOrFail();
        $data = ['campaigns' => $directCampaigns];

        /** @var YandexDirectSettings $settings */
        $settings = DB::transaction(static function () use ($source, $data): \Module\Source\YandexDirect\Models\YandexDirectSettings|\Module\Source\YandexMetrika\Models\MetrikaSourceSettings|\Module\Source\Vk\Models\VkSettings|\Module\Source\GoogleAnalytics\Models\AnalyticsSettings {
            if ($settings = $source->settings) {
                $settings->update($data);
            } else {
                /** @var YandexDirectSettings $settings */
                $settings = YandexDirectSettings::query()->create($data);
                $source->settings()->associate($settings)->save();

                app(DispatchFetchYandexDirectAction::class)->execute($source);
            }

            return $settings;
        });



        return $settings;
    }
}
