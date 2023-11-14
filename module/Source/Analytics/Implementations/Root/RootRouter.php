<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Root;

use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Contracts\AnalyticsRouter;
use Module\Source\Analytics\DTO\ItemData;
use Module\Source\Analytics\Enums\RootItemType;
use Module\Source\Analytics\Exceptions\EmptyPathItemException;
use Module\Source\Analytics\Exceptions\UndefinedPathItemTypeException;
use Module\Source\Analytics\Implementations\AbstractRouter;
use Module\Source\Analytics\Implementations\Sources\Avito\AvitoRouter;
use Module\Source\Analytics\Implementations\Sources\DirectTrafficYandexMetrika\DirectTrafficYandexMetrikaRouter;
use Module\Source\Analytics\Implementations\Sources\SeoYandexMetrika\SeoYandexMetrikaRouter;
use Module\Source\Analytics\Implementations\Sources\Vk\VkRouter;
use Module\Source\Analytics\Implementations\Sources\VkLeads\VkLeadsRouter;
use Module\Source\Analytics\Implementations\Sources\YandexDirect\YandexDirectRouter;
use Module\Source\Analytics\PathItems\RootPathItem;
use Module\Source\Analytics\Services\AnalyticsPath;

final class RootRouter extends AbstractRouter
{
    public function __construct(
        private readonly RootDataProvider $rootDataProvider,
    ) {
    }

    /**
     * @param  null  $filter
     * @return ?ItemData[]
     * @throws EmptyPathItemException|UndefinedPathItemTypeException
     */
    public function getData(
        Campaign $campaign,
        DateRange $dateRange,
        AnalyticsPath $path,
        $filter = null,
    ): ?array {
        if ($path->isEmpty()) {
            $res = $this->rootDataProvider->getSources($campaign, $dateRange);
            return self::prepareData($res, $path);
        }

        $rootItem = RootPathItem::make($path->step());

        if ($rootItem->isChartItem()) {
            $res = $this->rootDataProvider->getChart($campaign, $dateRange, $rootItem->getChartGroupType());
            return self::prepareData($res, $path, true);
        }

        /** @var AnalyticsRouter $router */
        $router = app(match ($rootItem->getRootItemType()) {
            RootItemType::AVITO => AvitoRouter::class,
            RootItemType::YANDEX_DIRECT => YandexDirectRouter::class,
            RootItemType::VK => VkRouter::class,
            RootItemType::VK_LEADS => VkLeadsRouter::class,
            RootItemType::SEO_YANDEX_METRIKA => SeoYandexMetrikaRouter::class,
            RootItemType::DIRECT_TRAFFIC_YANDEX_METRIKA => DirectTrafficYandexMetrikaRouter::class,
        });

        return $router->getData($campaign, $dateRange, $path);
    }
}
