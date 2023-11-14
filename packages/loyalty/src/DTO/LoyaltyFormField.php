<?php

declare(strict_types=1);

namespace Loyalty\DTO;

use Spatie\LaravelData\Data;

final class LoyaltyFormField extends Data
{
    public function __construct(
        public string $title,
        public bool $required = true,
    ) {
    }
}
