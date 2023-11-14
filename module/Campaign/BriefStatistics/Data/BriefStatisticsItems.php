<?php

declare(strict_types=1);

namespace Module\Campaign\BriefStatistics\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class BriefStatisticsItems extends Data
{
    public function __construct(
        public ?int $reaches,
        public ?int $page_views,
        public ?int $visits,
        public ?int $new_users,
        public ?int $visitors,
        public ?float $conversion_rate,
        public ?float $avg_visit_duration,
        public ?float $bounce_rate,
        public ?int $mobile_traffic,
        public ?float $depth,
        public ?int $purchases,
        public ?int $income,
        public ?array $devices,
        public ?array $city_reaches,
        public ?array $city_conversion_rate,
        public ?array $city_new_users,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public Carbon $last_update,
    ) {
    }
}
