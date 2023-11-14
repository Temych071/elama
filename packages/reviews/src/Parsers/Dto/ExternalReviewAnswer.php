<?php

declare(strict_types=1);

namespace Reviews\Parsers\Dto;

use Illuminate\Support\Carbon;

final class ExternalReviewAnswer
{
    public function __construct(
        public string $text,
        public Carbon $created_at = new Carbon(),
    ) {
    }
}
