<?php

namespace Reviews\Parsers\Dto;

use Spatie\LaravelData\Data;

final class Review extends Data
{
    public function __construct(
        public string $id,
        public string $text,
        public string $name,
        public string $date,
        public float $rating,
    ) {
    }
}
