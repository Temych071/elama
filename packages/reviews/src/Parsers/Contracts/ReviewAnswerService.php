<?php

namespace Reviews\Parsers\Contracts;

use Reviews\Models\ReviewAnswer;

interface ReviewAnswerService
{
    public function sendAnswer(ReviewAnswer $answer): bool;

    public function deleteAnswer(ReviewAnswer $answer): bool;

    public function updateAnswer(ReviewAnswer $answer): bool;
}
