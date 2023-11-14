<?php

declare(strict_types=1);

namespace Module\Campaign\Checks\DTO;

use Spatie\LaravelData\Data;

final class CheckObject extends Data
{
    public function __construct(
        public string $type,
        public string $name,
        public ?array $campaign = null,
        public ?string $url = null,
        public ?string $index = null,
        public ?array $custom = [],
    ) {
    }
}
