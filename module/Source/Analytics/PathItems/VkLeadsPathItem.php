<?php

declare(strict_types=1);

namespace Module\Source\Analytics\PathItems;

use Module\Source\Analytics\Enums\VkLeadsItemType;
use Module\Source\Analytics\Enums\VkLeadType;
use Module\Source\Analytics\Exceptions\UndefinedPathItemTypeException;

final class VkLeadsPathItem extends ChartPathItem
{
    /**
     * @throws UndefinedPathItemTypeException
     */
    public function getVkLeadsItemType(): ?VkLeadsItemType
    {
        $res = VkLeadsItemType::tryFrom($this->getType());
        if (is_null($res)) {
            throw new UndefinedPathItemTypeException($this->getType());
        }

        return $res;
    }

    public function getVkLeadType(): VkLeadType
    {
        return VkLeadType::from($this->getArg());
    }
}
