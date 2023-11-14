<?php

declare(strict_types=1);

namespace Module\LinkChecker\Dto;

use Spatie\LaravelData\Data;

final class LinkCheckAdDto extends Data
{
    public function __construct(
        public readonly string $title,
        public readonly string $cabinet_link,
    ) {
    }
}
