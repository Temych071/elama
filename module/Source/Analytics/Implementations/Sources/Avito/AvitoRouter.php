<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\Avito;

use App\Exceptions\BusinessException;
use App\Infrastructure\DateRange;
use Module\Source\Analytics\DTO\CabinetsFilter;
use Module\Source\Analytics\Enums\CabinetItemType;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Implementations\AbstractRouter;
use Module\Source\Analytics\PathItems\CabinetPathItem;
use Module\Source\Analytics\Services\AnalyticsPath;
use Module\Source\Sources\Models\Source;

final class AvitoRouter extends AbstractRouter
{
    public function __construct(
        private readonly AvitoDataProvider $dataProvider,
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
        $filter = new CabinetsFilter(),
    ): ?array {
        $filter->source_type = Source::TYPE_AVITO;

        $item = null;
        while (!$path->isEnd()) {
            $item = CabinetPathItem::make($path->step());

            if ($item->isChartItem()) {
                $res = $this->dataProvider->getChart($campaign, $dateRange, $item->getChartGroupType(), $filter);
                return self::prepareData($res, $path, true);
            }

            switch ($item->getCabinetItemType()) {
                case CabinetItemType::ACCOUNT:
                    $filter->account_ids = [$item->getItemIndex()];
                    break;

                case CabinetItemType::AD:
                    $filter->ad_ids = [$item->getItemIndex()];
                    break;

                default:
                    break;
            }
        }

        $isLast = false;
        $next = self::getNestedType(
            $campaign->getAnalyticsCabinetOrder(),
            $item?->getCabinetItemType(),
            $isLast,
            [CabinetItemType::AD_GROUP, CabinetItemType::CAMPAIGN],
        );

        $data = match ($next) {
            CabinetItemType::ACCOUNT => $this->dataProvider->getAccounts($campaign, $dateRange, $filter),
            CabinetItemType::AD => $this->dataProvider->getItems($campaign, $dateRange, $filter),
            default => null,
        };

        return self::prepareData($data, $path, $isLast);
    }
}
