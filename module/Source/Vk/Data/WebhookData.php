<?php

declare(strict_types=1);

namespace Module\Source\Vk\Data;

use Spatie\LaravelData\Data;

final class WebhookData extends Data
{
    public function __construct(
        public readonly int $group_id,
        public readonly string $code,
    ) {
    }
}
