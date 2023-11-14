<?php

namespace Module\Source\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Module\Source\GoogleAnalytics\Models\AnalyticsSettings;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkSettings;
use Module\Source\YandexDirect\Models\YandexDirectSettings;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;

class SourceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Relation::enforceMorphMap([
            Source::TYPE_YANDEX_METRIKA => MetrikaSourceSettings::class,
            Source::TYPE_GOOGLE_ANALYTICS => AnalyticsSettings::class,
            Source::TYPE_YANDEX_DIRECT => YandexDirectSettings::class,
            Source::TYPE_VK => VkSettings::class,
        ]);
    }
}
