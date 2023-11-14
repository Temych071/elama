<?php

declare(strict_types=1);

namespace Module\Source\Analytics\PathItems;

use Module\Source\Analytics\Enums\RootItemType;
use Module\Source\Analytics\Exceptions\UndefinedPathItemTypeException;

final class RootPathItem extends ChartPathItem
{
    /**
     * @throws UndefinedPathItemTypeException
     */
    public function getRootItemType(): RootItemType
    {
        $res = RootItemType::tryFrom($this->getType());
        if (is_null($res)) {
            throw new UndefinedPathItemTypeException($this->getType());
        }

        return $res;
    }
}
