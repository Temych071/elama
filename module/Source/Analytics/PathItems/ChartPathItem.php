<?php

declare(strict_types=1);

namespace Module\Source\Analytics\PathItems;

use Module\Source\Analytics\Enums\ChartGroupType;

class ChartPathItem extends AnalyticsPathItem
{
    public const CHART_ITEM_TYPE = 'chart';

    public function isChartItem(): bool
    {
        return $this->getType() === self::CHART_ITEM_TYPE;
    }

    public function getChartGroupType(): ?ChartGroupType
    {
        $arg = ChartGroupType::tryFrom($this->getArg());
        if (is_null($arg)) {
            return ChartGroupType::DAYS_1;
        }

        return $arg;
    }
}
