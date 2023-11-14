<?php

namespace Reviews\Parsers\Yandex\Actions;

use Reviews\Models\Review;
use Reviews\Parsers\Yandex\Exceptions\NotFoundReviewOnPageForAnswerException;
use Reviews\Parsers\Yandex\Services\SendAnswerService;

class SendReviewAnswerByPredictedPageAction
{
    public function __construct(protected SendAnswerService $service)
    {
    }

    public function execute(Review $review, string $placeId, string $reviewId, string $answerText): bool
    {
        $predictedPage = app(PredictReviewPageAction::class)->execute($review);

        try {
            return $this->service->send($placeId, $reviewId, $answerText, $predictedPage);
        } catch (NotFoundReviewOnPageForAnswerException) {
            // Если вдруг предикт промахнулся,
            // то попробовать ещё след страницу
            return $this->service->send($placeId, $reviewId, $answerText, $predictedPage + 1);
        }
    }
}
