<?php

declare(strict_types=1);

namespace Reviews\Actions\Answers;

use Reviews\Exceptions\ReviewSourceNotAllowedForAnswersException;
use Reviews\Models\Review;
use Reviews\Services\ReviewAnswersService;

abstract class AbstractReviewAnswerAction
{
    public function __construct(
        protected readonly ReviewAnswersService $service,
    ) {
    }

    /**
     * @throws ReviewSourceNotAllowedForAnswersException
     */
    public function check(Review $review): void
    {
        if (!$this->service->isAnswersAllowed($review)) {
            throw new ReviewSourceNotAllowedForAnswersException();
        }
    }
}
