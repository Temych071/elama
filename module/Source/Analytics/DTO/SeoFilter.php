<?php

declare(strict_types=1);

namespace Module\Source\Analytics\DTO;

final class SeoFilter
{
    public function __construct(
        public ?array $account_ids = null,
        public ?array $devises = null,
        public ?array $search_engines = null,
        public ?array $goal_ids = null,
        public ?array $source_utms = null,
        public ?array $traffic_sources = null,
    ) {
    }
}
