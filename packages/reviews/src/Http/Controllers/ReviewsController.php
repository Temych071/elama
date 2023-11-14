<?php

namespace Reviews\Http\Controllers;

use App\Exceptions\ToastException;
use App\Infrastructure\DateRange;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Reviews\Actions\Answers\DeleteReviewAnswerAction;
use Reviews\Actions\Answers\SendReviewAnswerAction;
use Reviews\Actions\Answers\UpdateReviewAnswerAction;
use Reviews\Enums\AnswerFilter;
use Reviews\Enums\RatingFilter;
use Reviews\Enums\ReviewSource;
use Reviews\Enums\ReviewStatus;
use Reviews\Exceptions\ReviewAnswerAlreadyExistsException;
use Reviews\Exceptions\ReviewSourceNotAllowedForAnswersException;
use Reviews\Models\Review;
use Reviews\Resources\ReviewFilterResource;
use Reviews\Services\ReviewAnswersService;

final class ReviewsController
{
    /**
     * @throws ValidationException
     */
    public function show(Request $request, Campaign $campaign): Response
    {
        $filters = $request->validate([
            'rating' => ['nullable', new Enum(RatingFilter::class)],
            'branches' => ['nullable', 'exists:review_forms,id'],
            'answer' => ['nullable', new Enum(AnswerFilter::class)]
        ]);
        $filters['rating'] ??= null;
        $filters['branches'] ??= null;
        $filters['answer'] ??= null;
        $filters['date-range'] ??= null;

        $sources = explode(',', $request->input('sources', 'yandex,double-gis'));
        $selectedTag = $request->input('selectedTag', null);
        $dateRange = $request->input('date-range', null);
        if ($dateRange !== null) {
            $dateRange = DateRange::make($dateRange);
        }

        /** @var LengthAwarePaginator $reviews */
        $reviews = Review::query()
            ->whereIn('review_form_id', $campaign->reviewForms->pluck('id'))
            ->when(!empty($selectedTag), static fn($q) => $q
                ->whereHas('tags', static fn($q) => $q->where('tag', $selectedTag)))
            ->when(!empty($filters['branches']), static fn($q) => $q->where('review_form_id', $filters['branches']))
            ->when(!empty($dateRange), static fn($q) => $q->whereInDateRange($dateRange, 'created_at'))
            ->when(!empty($sources), static fn($q) => $q->whereIn('source', $sources))
            ->with(['reviewForm', 'comments.author', 'tags', 'answer'])
            ->filter($filters)
            ->paginate(20)
            ->withQueryString();

        $answersService = app(ReviewAnswersService::class);

        $reviews->each(function (Review $review) use ($answersService) {
            $review->reviewForm->makeVisible('yandex_company_id');
            $review->setAttribute('answers_allowed', $answersService->isAnswersAllowed($review));

            return $review;
        });

        return Inertia::render('Reviews/Private/Reviews', [
            'filters' => $filters,
            'filtersValue' => ReviewFilterResource::make($campaign)->toArray($request),
            'reviews' => $reviews,
            'campaign' => $campaign,
            'dateRange' => $dateRange,
            'tags' => $campaign->reviewTags()
                ->distinct()
                ->pluck('tag'),
            'selectedTag' => $selectedTag,
            'sources' => $sources,
        ]);
    }

    /**
     * @throws ToastException
     */
    public function sendAnswer(Request $request, Campaign $campaign, Review $review): RedirectResponse
    {
        $data = $request->validate([
            'text' => ['required', 'string'],
        ]);

        try {
            app(SendReviewAnswerAction::class)->execute($review, $data['text'], $request->user());
        } catch (ReviewSourceNotAllowedForAnswersException) {
            throw new ToastException('Невозможно отправить ответ на этот отзыв');
        } catch (ReviewAnswerAlreadyExistsException) {
            throw new ToastException('Ответ на этот отзыв уже был отправлен');
        }

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Ответ на отзыв сохранён и будет опубликован в ближайшее время.',
        ]);
    }

    /**
     * @throws ToastException
     */
    public function updateAnswer(Request $request, Campaign $campaign, Review $review): RedirectResponse
    {
        $data = $request->validate([
            'text' => ['required', 'string'],
        ]);

        try {
            app(UpdateReviewAnswerAction::class)->execute($review, $data['text']);
        } catch (ReviewSourceNotAllowedForAnswersException) {
            throw new ToastException('Невозможно обновить ответ на этот отзыв');
        }

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Ответ на отзыв будет обновлён в ближайшее время.',
        ]);
    }

    /**
     * @throws ToastException
     */
    public function deleteAnswer(Request $request, Campaign $campaign, Review $review): RedirectResponse
    {
        try {
            app(DeleteReviewAnswerAction::class)->execute($review);
        } catch (ReviewSourceNotAllowedForAnswersException) {
            throw new ToastException('Невозможно удалить ответ на этот отзыв');
        }

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Ответ на отзыв будет удалён в ближайшее время',
        ]);
    }

    public function addComment(Request $request, Campaign $campaign): RedirectResponse
    {
        $validatedData = $request->validate([
            'comment' => ['required', 'max:1000'],
            'review_form_id' => ['required', 'numeric'],
            'review_id' => ['required', 'numeric'],
        ]);

        $reviewForm = $campaign->reviewForms()->findOrFail($validatedData['review_form_id']);
        $reviewForm->review($validatedData['review_id'])->comments()->create([
            'user_id' => $request->user()->id,
            'review_id' => $validatedData['review_id'],
            'text' => $validatedData['comment']
        ]);

        return redirect()->back();
    }

    public function addTag(Request $request, Campaign $campaign, Review $review): RedirectResponse
    {
        $reviewFormProjectId = $review->reviewForm->project_id;
        abort_if($reviewFormProjectId !== $campaign->id, 404);

        $res = $review->addTag($request->validate([
            'tag' => ['required', 'string', 'max:32'],
        ])['tag']);

        return redirect()->back()->with('toast', $res ? [
            'type' => 'success',
            'message' => 'Тег отзыва успешно добавлен',
        ] : [
            'type' => 'info',
            'message' => 'Такой тег уже был добавлен ранее',
        ]);
    }

    public function removeTag(Request $request, Campaign $campaign, Review $review): RedirectResponse
    {
        $reviewFormProjectId = $review->reviewForm->project_id;
        abort_if($reviewFormProjectId !== $campaign->id, 404);

        $review->removeTag($request->validate([
            'tag' => ['required', 'string', 'max:32'],
        ])['tag']);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Тег отзыва успешно удалён',
        ]);
    }

    public function delete(Request $request, Campaign $campaign, Review $review): RedirectResponse
    {
        $reviewFormProjectId = $review->reviewForm->project_id;
        abort_if($reviewFormProjectId !== $campaign->id, 404);
        abort_if($review->source !== ReviewSource::DAILY_GROW, 400);

        $review->delete();

        return redirect()->back();
    }

    public function updateStatus(Request $request, Campaign $campaign, Review $review): RedirectResponse
    {
        $reviewFormProjectId = $review->reviewForm->project_id;
        abort_if($reviewFormProjectId !== $campaign->id, 404);

        $status = $request->validate([
            'status' => ['required', new Enum(ReviewStatus::class)],
        ])['status'];

        $review->status = $status;
        $review->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Статус отзыва обновлён.',
        ]);
    }

    public function getRatingWidget(Request $request, Campaign $campaign): array
    {
        $reviewFormId = $request->input('reviewFormId', null);

        $sources = explode(',', $request->input('sources', 'yandex,double-gis'));

        $dateRange = $request->input('dateRange', null);
        if ($dateRange !== null) {
            $dateRange = DateRange::make($dateRange);
        }

        $date = $request->boolean('compare')
            ? $dateRange?->getFrom()
            : $dateRange?->getTo();

        return DB::table('review_reviews')
            ->whereIn('review_form_id', $campaign->reviewForms->pluck('id'))
            ->leftJoin('review_reviews_answers', 'review_id', '=', 'review_reviews.id')
            ->when(!empty($sources), static fn($q) => $q->whereIn('review_reviews.source', $sources))
            ->when(!empty($dateRange), static fn($q) => $q->whereDate('review_reviews.created_at', '<=', $date))
            ->when(!empty($reviewFormId), static fn($q) => $q->where('review_reviews.review_form_id', $reviewFormId))
            ->selectRaw('
                SUM(IF(source = \'daily-grow\', 1, NULL)) as marksCount,
                SUM(IF(source = \'daily-grow\', NULL, 1)) as reviewsCount,
                COUNT(review_reviews_answers.text) as answersCount,
                AVG(IF(source = \'daily-grow\', NULL, stars)) as totalRating,
                AVG(IF(source = \'yandex\', stars, NULL)) as yandexRating,
                AVG(IF(source = \'double-gis\', stars, NULL)) as doubleGisRating,
                SUM(IF(stars = 5, 1, NULL)) as fiveStars,
                SUM(IF(stars = 4, 1, NULL)) as fourStars,
                SUM(IF(stars = 3, 1, NULL)) as threeStars,
                SUM(IF(stars = 2, 1, NULL)) as twoStars,
                SUM(IF(stars = 1, 1, NULL)) as oneStar
            ') // Это вообще норм так со звёздами?
            ->get()
            ->transform(static fn($data) => [
                'totalRating' => (float) $data->totalRating,
                'reviewsCount' => (int) $data->reviewsCount,
                'marksCount' => (int) $data->marksCount,
                'answersCount' => (int) $data->answersCount,
                'withoutAnswerCount' => (int) $data->reviewsCount - (int) $data->answersCount,
                'sourceRatings' => [
                    ReviewSource::DOUBLE_GIS->value => (float) $data->doubleGisRating,
                    ReviewSource::YANDEX->value => (float) $data->yandexRating,
                ],
                'stars' => [
                    1 => (int) $data->oneStar,
                    2 => (int) $data->twoStars,
                    3 => (int) $data->threeStars,
                    4 => (int) $data->fourStars,
                    5 => (int) $data->fiveStars,
                ],
            ])
            ->first();
    }
}
