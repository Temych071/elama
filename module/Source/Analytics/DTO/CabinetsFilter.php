<?php

declare(strict_types=1);

namespace Module\Source\Analytics\DTO;

final class CabinetsFilter
{
    /**
     * @param  int[]|null  $account_ids
     * @param  int[]|null  $campaign_ids
     * @param  int[]|null  $group_ids
     * @param  int[]|null  $ad_ids
     */
    public function __construct(
        public ?string $source_type = null,
        public ?array $account_ids = null,
        public ?array $campaign_ids = null,
        public ?array $group_ids = null,
        public ?array $ad_ids = null,
    ) {
    }
}
