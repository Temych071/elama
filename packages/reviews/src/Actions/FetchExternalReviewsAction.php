<?php

declare(strict_types=1);

namespace Reviews\Actions;

use App\Infrastructure\DateRange;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Reviews\DTO\ReviewSourceData;
use Reviews\Events\NewExternalReviewEvent;
use Reviews\Events\NewExternalReviewsEvent;
use Reviews\Models\Review;
use Reviews\Models\ReviewForm;
use Reviews\Parsers\Contracts\ReviewsService;
use Reviews\Parsers\Dto\ExternalReview;
use Reviews\Parsers\Dto\ExternalReviewAnswer;
use Throwable;

final class FetchExternalReviewsAction
{
    /**
     * @throws Throwable
     */
    public function execute(
        ReviewForm $reviewForm,
        ReviewSourceData $source,
        ?DateRange $dateRange,
        bool $notify = false,
    ): void {
        /** @var ReviewsService $service */
        $service = app($source->serviceClass);

        $reviews = $service->getReviews(
            $source->placeId,
            $dateRange,
        );

        $affectedRows = $reviewForm->reviews()->upsert(array_map(static fn(ExternalReview $review) => [
            'review_form_id' => $reviewForm->id,
            'source' => $source->source->value,
            'external_id' => $review->id,

            'name' => $review->name,
            'stars' => $review->rating,
            'comment' => $review->text,
            'updated_at' => $review->created_at,
            'created_at' => $review->created_at,
        ], $reviews), [
            'review_form_id',
            'source',
            'external_id',
        ], [
            'name',
            'stars',
            'comment',
            'updated_at',
        ]);

        $this->upsertAnswers($reviewForm, $reviews);

        if ($affectedRows && $notify) {
            $reviewForm->reviews()
                ->orderByDesc('created_at')
                ->limit($affectedRows)
                ->external()
                ->get()
                ->each(static fn(Review $review) => event(new NewExternalReviewEvent($review)));

            event(new NewExternalReviewsEvent($reviewForm, $source->source, $affectedRows));
        }
    }

    /**
     * @param  ReviewForm  $reviewForm
     * @param  ExternalReview[]  $reviews
     * @return void
     * @throws Throwable
     */
    public function upsertAnswers(ReviewForm $reviewForm, array $reviews): void
    {
        $answers = [];
        $reviewsForDelAnswer = [];
        foreach ($reviews as $review) {
            if ($review->answer !== null) {
                $answers[$review->id] = $review->answer;
            } else {
                $reviewsForDelAnswer[] = $review->id;
            }
        }

        $reviewIds = $reviewForm->reviews()
            ->external()
            ->whereIn('external_id', [...array_keys($answers), ...$reviewsForDelAnswer])
            ->select(['id', 'external_id'])
            ->pluck('id', 'external_id')
            ->toArray();

        DB::transaction(static function () use ($reviewIds, $answers, $reviewsForDelAnswer) {
            DB::table('review_reviews_answers')
                ->whereIn('review_id', $reviewsForDelAnswer)
                ->delete();

            DB::table('review_reviews_answers')
                ->upsert(Arr::map($answers, static fn(ExternalReviewAnswer $answer, string $externalId) => [
                    'review_id' => $reviewIds[$externalId],
                    'text' => $answer->text,
                    'created_at' => $answer->created_at,
                ]), [
                    'review_id'
                ], [
                    'text',
                    'created_at',
                ]);
        });
    }
}
