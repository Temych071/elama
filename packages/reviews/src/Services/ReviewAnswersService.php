<?php

declare(strict_types=1);

namespace Reviews\Services;

use Reviews\Enums\ReviewSource;
use Reviews\Models\Review;
use Reviews\Models\ReviewAnswer;
use Reviews\Parsers\Contracts\ReviewAnswerService;
use Reviews\Parsers\Gis\Services\DoubleGisReviewAnswerService;
use Reviews\Parsers\Yandex\Services\YandexReviewAnswerService;

final class ReviewAnswersService
{
    public function send(ReviewAnswer $answer): bool
    {
        $res = $this->resolveAnswerService($answer)
            ?->sendAnswer($answer) ?? false;

        if ($res) {
            $answer->published_at = now();
            $answer->save();
        }

        return $res;
    }

    public function update(ReviewAnswer $answer): bool
    {
        if ($answer->update_text === null) {
            $res = true;
        } else {
            $res = $this->resolveAnswerService($answer)
                ?->updateAnswer($answer) ?? false;
        }

        if ($res) {
            $answer->text = $answer->update_text;
            $answer->update_text = null;
            $answer->published_at = now();
            $answer->save();
        }

        return $res;
    }

    public function delete(ReviewAnswer $answer): bool
    {
        if ($answer->published_at || $answer->author_id === null) {
            $res = $this->resolveAnswerService($answer)
                ?->deleteAnswer($answer) ?? false;
        } else {
            $res = true;
        }

        if ($res) {
            $answer->delete();
        }

        return $res;
    }

    public function isAnswersAllowed(Review $review): bool
    {
        if (
            $review->source === ReviewSource::DOUBLE_GIS
            && !$review->reviewForm->widget_2gis_access
        ) {
            return false;
        }

//        return $this->resolveAnswerService($review) !== null;
        return $review->source !== ReviewSource::DAILY_GROW;
    }

    private function resolveAnswerService(ReviewAnswer|Review $review): ?ReviewAnswerService
    {
        if ($review instanceof ReviewAnswer) {
            $review = $review->review;
        }

        return match ($review->source) {
            ReviewSource::DAILY_GROW => null,
            ReviewSource::YANDEX => app(YandexReviewAnswerService::class),
            ReviewSource::DOUBLE_GIS => app(DoubleGisReviewAnswerService::class),
        };
    }
}
