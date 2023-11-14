<?php

declare(strict_types=1);

namespace Reviews\Parsers\Yandex\Services;

use Reviews\Models\ReviewAnswer;
use Reviews\Parsers\Contracts\ReviewAnswerService;
use Reviews\Parsers\Yandex\Actions\SendReviewAnswerByPredictedPageAction;

final class YandexReviewAnswerService implements ReviewAnswerService
{
    public function sendAnswer(ReviewAnswer $answer): bool
    {
        return app(SendReviewAnswerByPredictedPageAction::class)->execute(
            $answer->review,
            (string) $answer->review->reviewForm->yandex_company_id,
            $answer->review->external_id,
            $answer->text,
        );
    }

    public function deleteAnswer(ReviewAnswer $answer): bool
    {
        return app(SendReviewAnswerByPredictedPageAction::class)->execute(
            $answer->review,
            (string) $answer->review->reviewForm->yandex_company_id,
            $answer->review->external_id,
            '',
        );
    }

    public function updateAnswer(ReviewAnswer $answer): bool
    {
        return app(SendReviewAnswerByPredictedPageAction::class)->execute(
            $answer->review,
            (string) $answer->review->reviewForm->yandex_company_id,
            $answer->review->external_id,
            $answer->update_text,
        );
    }
}
