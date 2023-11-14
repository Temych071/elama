<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Data;

use Spatie\LaravelData\Data;

final class GoalData extends Data
{
    /** @noinspection MagicMethodsValidityInspection */
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public bool $is_retargeting,
        public string $goal_source,
    ) {
    }
}
