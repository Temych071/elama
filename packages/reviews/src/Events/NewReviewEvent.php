<?php

declare(strict_types=1);

namespace Reviews\Events;

use Reviews\Models\Review;

final class NewReviewEvent
{
    public function __construct(
        public readonly Review $review,
    ) {
    }
}
