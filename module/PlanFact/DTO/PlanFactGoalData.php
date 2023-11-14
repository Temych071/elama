<?php

declare(strict_types=1);

namespace Module\PlanFact\DTO;

use Spatie\LaravelData\Data;

final class PlanFactGoalData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}
