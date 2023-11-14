<?php

declare(strict_types=1);

namespace Reviews\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Reviews\Jobs\Answers\SendReviewAnswerJob;
use Reviews\Jobs\Answers\UpdateReviewAnswerJob;
use Reviews\Models\ReviewAnswer;
use Reviews\Parsers\Dto\Review;
use Reviews\Parsers\Yandex\Services\YandexSessionService;

final class ReDispatchReviewAnswersCommand extends Command
{
    protected $signature = 'reviews:redispatch-answers';
    protected $description = 'Dispatch not published answers again';

    public function handle(): void
    {
        $count = ReviewAnswer::query()
            ->whereNotNull('author_id')
            ->whereNull('published_at')
            ->get()
            ->each(fn(ReviewAnswer $answer) => dispatch(new SendReviewAnswerJob($answer)))
            ->count();
        $this->info("$count answers send");

        $count = ReviewAnswer::query()
            ->whereNotNull('update_text')
            ->get()
            ->each(fn(ReviewAnswer $answer) => dispatch(new UpdateReviewAnswerJob($answer)))
            ->count();
        $this->info("$count answers update");
    }
}
