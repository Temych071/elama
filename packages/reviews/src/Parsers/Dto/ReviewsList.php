<?php

namespace Reviews\Parsers\Dto;

use Reviews\Enums\ReviewSource;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class ReviewsList extends Data
{
    public function __construct(
        public float $rating,
        public int $totalCount,
        #[DataCollectionOf(Review::class)]
        public DataCollection $reviews,
        public ?ReviewSource $source = null,
        public ?string $placeId = null,
    ) {
    }
}
