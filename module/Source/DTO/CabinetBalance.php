<?php

declare(strict_types=1);

namespace Module\Source\DTO;

use Spatie\LaravelData\Data;

final class CabinetBalance extends Data
{
    public function __construct(
        public string $currency,
        public float|int $amount,
        public float|int|null $dailyBudget = null,
        public ?string $dailyType = null,
    ) {
    }
}
