<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\DirectTrafficYandexMetrika;

use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Exceptions\EmptyPathItemException;
use Module\Source\Analytics\Implementations\AbstractRouter;
use Module\Source\Analytics\PathItems\ChartPathItem;
use Module\Source\Analytics\Services\AnalyticsPath;

final class DirectTrafficYandexMetrikaRouter extends AbstractRouter
{
    public function __construct(
        private readonly DirectTrafficYandexMetrikaDataProvider $dataProvider,
    ) {
    }

    /**
     * @throws EmptyPathItemException
     */
    public function getData(Campaign $campaign, DateRange $dateRange, AnalyticsPath $path, $filter = null): ?array
    {
        if ($path->isEnd()) {
            return null;
        }

        $item = ChartPathItem::make($path->step());
        if (!$item->isChartItem()) {
            return null;
        }

        $res = $this->dataProvider->getChart($campaign, $dateRange, $item->getChartGroupType());
        return self::prepareData($res, $path, true);
    }
}
