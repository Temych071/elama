<?php

declare(strict_types=1);

namespace Reviews\Events;

use Error;
use Reviews\Enums\ReviewSource;
use Reviews\Models\Review;

final class NewExternalReviewEvent
{
    public function __construct(
        public readonly Review $review,
    ) {
        if ($this->review->source === ReviewSource::DAILY_GROW) {
            throw new Error('$review must contain an external review.');
        }
    }
}
