<?php

declare(strict_types=1);

namespace Module\Campaign\BriefStatistics\Data;

use App\Infrastructure\DateRange;
use Spatie\LaravelData\Data;

final class BriefStatistics extends Data
{
    /** @noinspection MagicMethodsValidityInspection */
    public function __construct(
        public DateRange $period,
        public int $periodLength,
        public DateRange $prevPeriod,
        public ?BriefStatisticsItems $conversions,
        public ?BriefStatisticsItems $prevConversions,
    ) {
    }
}
