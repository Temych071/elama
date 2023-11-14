<?php

declare(strict_types=1);

namespace Module\Source\Analytics\PathItems;

use Module\Source\Analytics\Enums\RootItemType;

final class VkPathItem extends CabinetPathItem
{
    public function isVkLeads(): bool
    {
        return (RootItemType::tryFrom($this->getType()) === RootItemType::VK_LEADS);
    }
}
