<?php

declare(strict_types=1);

namespace Reviews\Jobs;

use App\Infrastructure\DateRange;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Reviews\Actions\FetchExternalGeneralDataAction;
use Reviews\Actions\FetchExternalReviewsAction;
use Reviews\DTO\ReviewSourceData;
use Reviews\Models\ReviewForm;

final class FetchExternalGeneralDataJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $uniqueFor = 3600;

    public function __construct(
        public int $reviewFormId,
        public ReviewSourceData $source,
    ) {
        $this->onQueue("reviews.external.{$this->source->source->value}");
    }

    public function handle(): void
    {
        /** @var ReviewForm $reviewForm */
        $reviewForm = ReviewForm::query()->findOrFail($this->reviewFormId);

        app(FetchExternalGeneralDataAction::class)
            ->execute($reviewForm, $this->source);
    }

    public function uniqueId(): string
    {
        return "reviews.form-$this->reviewFormId.external.source-{$this->source->source->value}.general-data";
    }
}
