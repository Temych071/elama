<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\SeoYandexMetrika;

use App\Exceptions\BusinessException;
use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\SeoFilter;
use Module\Source\Analytics\Enums\SeoItemType;
use Module\Source\Analytics\Implementations\AbstractRouter;
use Module\Source\Analytics\PathItems\SeoPathItem;
use Module\Source\Analytics\Services\AnalyticsPath;

final class SeoYandexMetrikaRouter extends AbstractRouter
{
    public function __construct(
        private readonly SeoYandexMetrikaDataProvider $dataProvider,
    ) {
    }

    /**
     * @inheritDoc
     * @throws BusinessException
     */
    public function getData(
        Campaign $campaign,
        DateRange $dateRange,
        AnalyticsPath $path,
        $filter = new SeoFilter()
    ): ?array {
        $item = null;
        while (!$path->isEnd()) {
            $item = SeoPathItem::make($path->step());

            if ($item->isChartItem()) {
                $data = $this->dataProvider->getChart($campaign, $dateRange, $item->getChartGroupType(), $filter);
                return self::prepareData($data, $path, true);
            }

            switch ($item->getSeoItemType()) {
                case SeoItemType::ACCOUNT:
                    $filter->account_ids = [(int)$item->getArg()];
                    break;

                case SeoItemType::SEARCH_ENGINE:
                    $filter->search_engines = [$item->getArg()];
                    break;
            }
        }

        $isLast = false;
        $next = self::getNestedType(
            [SeoItemType::ACCOUNT, SeoItemType::SEARCH_ENGINE],
            $item?->getSeoItemType(),
            $isLast
        );

        $data = match ($next) {
            SeoItemType::ACCOUNT => $this->dataProvider->getAccounts($campaign, $dateRange, $filter),
            SeoItemType::SEARCH_ENGINE => $this->dataProvider->getSearchEngines($campaign, $dateRange, $filter),
            default => null,
        };

        return self::prepareData($data, $path, $isLast);
    }
}
