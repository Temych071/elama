<?php

namespace Reviews\Actions\Answers;

use Module\User\Models\User;
use Reviews\Exceptions\ReviewAnswerAlreadyExistsException;
use Reviews\Exceptions\ReviewSourceNotAllowedForAnswersException;
use Reviews\Jobs\Answers\SendReviewAnswerJob;
use Reviews\Models\Review;
use Reviews\Models\ReviewAnswer;

class SendReviewAnswerAction extends AbstractReviewAnswerAction
{
    /**
     * @throws ReviewAnswerAlreadyExistsException
     * @throws ReviewSourceNotAllowedForAnswersException
     */
    public function execute(Review $review, string $answerText, User $author): ReviewAnswer
    {
        $this->check($review);

        if ($review->answer()->exists()) {
            throw new ReviewAnswerAlreadyExistsException();
        }

        $answer = $review->answer()->create([
            'text' => $answerText,
            'author_id' => $author->getKey(),
        ]);

        // Надо ещё что-то делать в случае отвала джобы
        // Мб раз в сутки брать из БД все неопубликованные ответы
        // и диспатчить для них эту джобу?
        dispatch(new SendReviewAnswerJob($answer));

        return $answer;
    }
}
