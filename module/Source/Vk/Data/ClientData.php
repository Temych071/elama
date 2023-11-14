<?php

declare(strict_types=1);

namespace Module\Source\Vk\Data;

use Spatie\LaravelData\Data;

final class ClientData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $day_limit,
        public readonly int $all_limit,
    ) {
    }
}
