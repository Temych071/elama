<?php

namespace Reviews\Actions\Answers;

use Reviews\Exceptions\ReviewSourceNotAllowedForAnswersException;
use Reviews\Jobs\Answers\UpdateReviewAnswerJob;
use Reviews\Models\Review;
use Reviews\Models\ReviewAnswer;

class UpdateReviewAnswerAction extends AbstractReviewAnswerAction
{
    /**
     * @throws ReviewSourceNotAllowedForAnswersException
     */
    public function execute(Review $review, string $updateText): ReviewAnswer
    {
        $this->check($review);

        $review->answer->update_text = $updateText;
        $review->answer->save();

        dispatch(new UpdateReviewAnswerJob($review->answer));

        return $review->answer;
    }
}
