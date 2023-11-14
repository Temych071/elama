<?php

declare(strict_types=1);

namespace Module\Source\Sources\Actions;

use App\Exceptions\BusinessException;
use DateInterval;
use Module\Source\Avito\Actions\Fetch\DispatchFetchAvitoAction;
use Module\Source\GoogleAnalytics\Actions\DispatchFetchAnalyticsAction;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Actions\Fetch\DispatchFetchVkAction;
use Module\Source\YandexDirect\Actions\DispatchFetchYandexDirectAction;
use Module\Source\YandexMetrika\Actions\DispatchFetchMetricsAction;

final class DispatchSourceFetchAction
{
    /**
     * @throws BusinessException
     */
    public function execute(Source $source, ?DateInterval $delay = null, bool $isForce = false): void
    {
        if (!$source->shouldFetch()) {
            return;
        }

        $dispatcher = match ($source->settings_type) {
            Source::TYPE_YANDEX_METRIKA => DispatchFetchMetricsAction::class,
            Source::TYPE_GOOGLE_ANALYTICS => DispatchFetchAnalyticsAction::class,
            Source::TYPE_YANDEX_DIRECT => DispatchFetchYandexDirectAction::class,
            Source::TYPE_VK => DispatchFetchVkAction::class,
            Source::TYPE_AVITO => DispatchFetchAvitoAction::class,
            default => throw new BusinessException('Неизвестный источник'),
        };

        app($dispatcher)->execute($source, $delay, $isForce);
    }
}
