<?php

declare(strict_types=1);

namespace Reviews\DTO;

use Reviews\Enums\ReviewSource;
use Reviews\Parsers\Contracts\ReviewsService;

final class ReviewSourceData
{
    /**
     * @param  ReviewSource  $source
     * @param  string  $placeId
     * @param  class-string<ReviewsService>  $serviceClass
     */
    public function __construct(
        public readonly ReviewSource $source,
        public readonly string $placeId,
        public readonly string $serviceClass,
    ) {
    }

}
