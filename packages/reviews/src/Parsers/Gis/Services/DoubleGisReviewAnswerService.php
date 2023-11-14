<?php

declare(strict_types=1);

namespace Reviews\Parsers\Gis\Services;

use Illuminate\Support\Carbon;
use Reviews\Models\ReviewAnswer;
use Reviews\Parsers\Contracts\ReviewAnswerService;

final class DoubleGisReviewAnswerService implements ReviewAnswerService
{
    public function __construct(
        private readonly DoubleGisAnswerApiService $service,
    ) {
    }

    public function sendAnswer(ReviewAnswer $answer): bool
    {
        return $this->service->send(
            $answer->review->reviewForm->widget_2gis,
            $answer->review->external_id,
            $answer->text,
        );
    }

    public function deleteAnswer(ReviewAnswer $answer): bool
    {
        $res = $this->service->delete(
            $answer->review->reviewForm->widget_2gis,
            $answer->review->external_id,
        );

        if (!$res) {
            return false;
        }

        $newMainAnswer = $this->service->getMainAnswer($answer->review->external_id);
        if ($newMainAnswer !== null) {
            $answer->text = $newMainAnswer['text'];
            $answer->created_at = Carbon::parse($newMainAnswer['dateCreated']);
            $answer->author_id = null;
            $answer->save();

            // Чтобы универсальный сервис не удалил изменённый ответ
            // А новый ответ создать не получиться из-за 1к1 связи отзыв-ответ)
            return false;
        }

        return true;
    }

    public function updateAnswer(ReviewAnswer $answer): bool
    {
        return $this->service->edit(
            $answer->review->reviewForm->widget_2gis,
            $answer->review->external_id,
            $answer->update_text,
        );
    }
}
