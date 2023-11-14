<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class CounterData extends Data
{
    public function __construct(
        public int $id,
        public string $status,
        public string $name,
        public string $permission,
        public string $time_zone_name,
        public string $site,
        /** @var GoalData[] */
        #[DataCollectionOf(GoalData::class)]
        public ?DataCollection $goals,
        public bool $ecommerce = false,
    ) {
    }
}
