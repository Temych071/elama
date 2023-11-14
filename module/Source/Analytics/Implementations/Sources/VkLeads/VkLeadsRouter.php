<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\VkLeads;

use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\VkLeadsFilter;
use Module\Source\Analytics\Enums\VkLeadsItemType;
use Module\Source\Analytics\Exceptions\EmptyPathItemException;
use Module\Source\Analytics\Exceptions\UndefinedPathItemTypeException;
use Module\Source\Analytics\Implementations\AbstractRouter;
use Module\Source\Analytics\PathItems\VkLeadsPathItem;
use Module\Source\Analytics\Services\AnalyticsPath;

final class VkLeadsRouter extends AbstractRouter
{
    private const ORDER = [VkLeadsItemType::LEAD_TYPE];

    public function __construct(
        private readonly VkLeadsDataProvider $dataProvider,
    ) {
    }

    /**
     * @throws EmptyPathItemException
     * @throws UndefinedPathItemTypeException
     */
    public function getData(
        Campaign $campaign,
        DateRange $dateRange,
        AnalyticsPath $path,
        $filter = new VkLeadsFilter(),
    ): ?array {
        $item = null;
        while (!$path->isEnd()) {
            $item = VkLeadsPathItem::make($path->step());

            if ($item->isChartItem()) {
                $res = $this->dataProvider->getChart($campaign, $dateRange, $item->getChartGroupType(), $filter);
                return self::prepareData($res, $path, true);
            }

            if ($item->getVkLeadsItemType() === VkLeadsItemType::LEAD_TYPE) {
                $filter->lead_type = $item->getVkLeadType();
            }
        }

        $isLast = false;
        $next =  self::getNestedType(
            self::ORDER,
            $item?->getVkLeadsItemType(),
            $isLast,
        );

        $data = match ($next) {
            VkLeadsItemType::LEAD_TYPE => $this->dataProvider->getLeadTypes($campaign, $dateRange, $filter),
            default => null,
        };

        return self::prepareData($data, $path, $isLast);
    }
}
