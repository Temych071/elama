<?php

declare(strict_types=1);

namespace Module\Campaign\Checks\DTO;

use Spatie\LaravelData\Data;

final class RuleData extends Data
{
    public function __construct(
        public string $title,
        public string $desc,
    ) {
    }
}
