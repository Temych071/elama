<?php

declare(strict_types=1);

namespace Reviews\Events;

use Reviews\Enums\ReviewSource;
use Reviews\Models\ReviewForm;

final class NewExternalReviewsEvent
{
    public function __construct(
        public readonly ReviewForm $reviewForm,
        public readonly ReviewSource $reviewSource,
        public readonly int $newReviewsCount = 0,
    ) {
    }
}
