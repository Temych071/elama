<?php

namespace Module\PlanFact\DTO;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class PlanValues extends Data
{
    public function __construct(
        public readonly Carbon $month,
        public readonly ?int $expenses = 0,
        public readonly ?int $income = 0,
        public readonly ?int $clicks = 0,
        public readonly ?int $requests = 0,
        public readonly ?float $cr = 0,
        public readonly ?float $cpl = 0,
        public readonly ?float $cpc = 0,
        public readonly ?float $drr = 0,
    ) {
    }

    public function toArray(): array
    {
        return array_map(
            static fn ($item): string => Carbon::parse($item)
                ->setDay(3)
                ->format('Y-m-d'),
            array_filter(parent::toArray())
        );
    }
}
