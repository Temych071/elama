<?php

declare(strict_types=1);

namespace Reviews\Jobs\Answers;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Reviews\Models\ReviewAnswer;
use Reviews\Services\ReviewAnswersService;

final class SendReviewAnswerJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $uniqueFor = 3600;

    public function __construct(
        public ReviewAnswer $reviewAnswer,
    ) {
        $this->onQueue("reviews.external.{$this->reviewAnswer->review->source->value}");
    }

    public function handle(): void
    {
        app(ReviewAnswersService::class)
            ->send($this->reviewAnswer);
    }

    public function uniqueId(): string
    {
        return "reviews.review-answer-{$this->reviewAnswer->id}.send";
    }
}
