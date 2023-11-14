<?php

declare(strict_types=1);

namespace Reviews\Parsers\Dto;

use Illuminate\Support\Carbon;

final class ExternalReview
{
    public function __construct(
        public string $id,
        public int $rating,
        public string $name,
        public string $text,
        public Carbon $created_at = new Carbon(),
        public ?ExternalReviewAnswer $answer = null,
    ) {
    }
}
