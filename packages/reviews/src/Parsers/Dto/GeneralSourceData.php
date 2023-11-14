<?php

declare(strict_types=1);

namespace Reviews\Parsers\Dto;

final class GeneralSourceData
{
    public function __construct(
        public readonly float $rating,
        public readonly int $totalReviewsCount,
    ) {
    }
}
