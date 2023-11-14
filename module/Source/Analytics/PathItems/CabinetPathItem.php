<?php

declare(strict_types=1);

namespace Module\Source\Analytics\PathItems;

use Module\Source\Analytics\Enums\CabinetItemType;
use Module\Source\Analytics\Exceptions\UndefinedPathItemTypeException;

class CabinetPathItem extends ChartPathItem
{
    /**
     * @throws UndefinedPathItemTypeException
     */
    public function getCabinetItemType(): ?CabinetItemType
    {
        $res = CabinetItemType::tryFrom($this->getType());
        if (is_null($res)) {
            throw new UndefinedPathItemTypeException($this->getType());
        }

        return $res;
    }

    public function getItemIndex(): int
    {
        return (int)$this->getArg();
    }
}
