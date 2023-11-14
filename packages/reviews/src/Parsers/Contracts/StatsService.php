<?php

declare(strict_types=1);

namespace Reviews\Parsers\Contracts;

use App\Infrastructure\DateRange;

interface StatsService
{
    public function getStats(string $placeId, DateRange $dateRange): array;
}
