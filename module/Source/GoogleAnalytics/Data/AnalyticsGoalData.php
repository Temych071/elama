<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Data;

use Spatie\LaravelData\Data;

final class AnalyticsGoalData extends Data
{
    /** @noinspection MagicMethodsValidityInspection */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $type,
    ) {
    }
}
