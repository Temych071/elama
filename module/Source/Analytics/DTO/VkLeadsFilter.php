<?php

declare(strict_types=1);

namespace Module\Source\Analytics\DTO;

use Module\Source\Analytics\Enums\VkLeadType;

final class VkLeadsFilter
{
    public function __construct(
        public ?VkLeadType $lead_type = null,
    ) {
    }
}
