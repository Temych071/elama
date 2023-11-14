<?php

namespace Reviews\Actions\Answers;

use Reviews\Jobs\Answers\DeleteReviewAnswerJob;
use Reviews\Models\Review;

class DeleteReviewAnswerAction extends AbstractReviewAnswerAction
{
    public function execute(Review $review): void
    {
        $this->check($review);

        dispatch(new DeleteReviewAnswerJob($review->answer));
    }
}
