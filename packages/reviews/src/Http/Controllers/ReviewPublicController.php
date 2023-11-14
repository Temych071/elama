<?php

declare(strict_types=1);

namespace Reviews\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Reviews\Actions\TrackReviewFormViewAction;
use Reviews\Events\NewReviewEvent;
use Reviews\Http\Requests\ReviewRequest;
use Reviews\Models\Review;
use Reviews\Models\ReviewForm;

final class ReviewPublicController
{
    public function show(string $slug): Response
    {
        /** @var ReviewForm $reviewForm */
        $reviewForm = ReviewForm::query()
            ->where('slug', $slug)
            ->firstOrFail();

        app(TrackReviewFormViewAction::class)->execute($reviewForm);

        return Inertia::render('Reviews/Public/Main', [
            'reviewFormSettings' => $reviewForm,
        ]);
    }

    public function send(ReviewRequest $request, string $slug): bool
    {
        /** @var ReviewForm $reviewForm */
        $reviewForm = ReviewForm::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->only(['comment', 'name', 'stars', 'contact']);
        $data['comment'] ??= ' ';

        /** @var Review $review */
        $review = $reviewForm
            ->reviews()
            ->create($data);

        event(new NewReviewEvent($review));

        return true;
    }

    public function loadAvgRate(string $slug): float
    {
        /** @var ReviewForm $reviewForm */
        $reviewForm = ReviewForm::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return (float)$reviewForm->reviews()->accepted()->avg('stars') ?: 4.5;
    }

    public function loadReviews(string $slug): \Illuminate\Database\Eloquent\Collection
    {
        /** @var ReviewForm $reviewForm */
        $reviewForm = ReviewForm::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return $reviewForm
            ->reviews()
            ->latest()
            ->accepted()
            ->whereNotNull(['comment', 'name'])
            ->whereNot('comment', ' ') // Костыль)
            ->limit(50)
            ->get();
    }
}
