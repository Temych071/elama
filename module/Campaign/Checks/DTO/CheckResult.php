<?php

declare(strict_types=1);

namespace Module\Campaign\Checks\DTO;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class CheckResult extends Data
{
    public function __construct(
        public RuleData $rule,
        /** @var CheckObject[] */
        #[DataCollectionOf(CheckObject::class)]
        public DataCollection $failedObjects,
        public int $totalObjectsCount,
        public ?string $message = null,
    ) {
    }

    // source => objType => this
}
