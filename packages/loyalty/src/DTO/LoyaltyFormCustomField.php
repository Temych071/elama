<?php

declare(strict_types=1);

namespace Loyalty\DTO;

use Spatie\LaravelData\Data;

final class LoyaltyFormCustomField extends Data
{
    public function __construct(
        public string $key,
        public string $title,
        public bool $required = false,
    ) {
    }
}
