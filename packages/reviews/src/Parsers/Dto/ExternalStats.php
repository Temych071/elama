<?php

declare(strict_types=1);

namespace Reviews\Parsers\Dto;

use Illuminate\Support\Carbon;

final class ExternalStats
{
    public function __construct(
        public readonly Carbon $date,
        public readonly int $views = 0,
        public readonly int $calls = 0,
        public readonly int $site = 0,
        public readonly int $routes = 0,
    ) {
    }

}
