<?php

declare(strict_types=1);

namespace Module\Source\Analytics\PathItems;

use Module\Source\Analytics\Enums\SeoItemType;
use Module\Source\Analytics\Exceptions\UndefinedPathItemTypeException;

final class SeoPathItem extends ChartPathItem
{
    /**
     * @throws UndefinedPathItemTypeException
     */
    public function getSeoItemType(): ?SeoItemType
    {
        $res = SeoItemType::tryFrom($this->getType());
        if (is_null($res)) {
            throw new UndefinedPathItemTypeException($this->getType());
        }

        return $res;
    }
}
