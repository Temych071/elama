<?php

use Database\Factories\ReviewFactory;
use Database\Factories\ReviewFormFactory;
use Inertia\Testing\AssertableInertia as Assert;
use Reviews\Models\Review;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses()->group('reviews');

test('a user can see list of reviews', function (): void {
    $user = actingAsUser();
    $project = $user->createCampaign('Test project');

    // без отзывов
    get(route('reviews.private.list', ['campaign' => $project]))
        ->assertInertia(
            fn (Assert $page): Assert => $page
            ->component('Reviews/Private/Reviews')
            ->has('campaign', fn (Assert $page): Assert => $page->where('id', $project->id)->etc())
            ->where('reviews.total', 0)
            ->has('filtersValue', 3)
        )
        ->assertSuccessful();

    ReviewFormFactory::times(10)->create();
    ReviewFactory::times(25)->create();

    get(route('reviews.private.list', ['campaign' => $project]))
        ->assertInertia(
            fn (Assert $page): Assert => $page
            ->component('Reviews/Private/Reviews')
            ->has('campaign', fn (Assert $page): Assert => $page->where('id', $project->id)->etc())
            ->where('reviews.total', 25)
            ->has('filtersValue', 3)
        )
        ->assertSuccessful();
});

test('a user can delete a review about his campaign', function (): void {
    $user = actingAsUser();
    $project = $user->createCampaign('Test project');

    ReviewFormFactory::times(10)->create();
    ReviewFactory::times(25)->create();

    $reviewOnDelete = Review::query()->inRandomOrder()->pluck('id')->first();

    delete(
        route(
        'reviews.private.delete',
        ['review' => $reviewOnDelete, 'campaign' => $project]
    )
    )
        ->assertRedirect()
        ->assertStatus(302);

    assertDatabaseMissing('review_reviews', ['id' => $reviewOnDelete]);
});

test('a user can write a comment to a review about his campaign', function (): void {
    $user = actingAsUser();
    $project = $user->createCampaign('Test project');
    $textComment = 'test comment';

    ReviewFormFactory::times(10)->create();
    ReviewFactory::times(25)->create();

    $review = Review::query()->inRandomOrder()->first();
    $reviewForm = $review->reviewForm;

    post(
        route('reviews.private.add-comment', ['campaign' => $project]),
        [
            'comment' => $textComment,
            'review_form_id' => $reviewForm->id,
            'review_id' => $review->id,
        ]
    )
        ->assertRedirect()
        ->assertStatus(302);

    assertDatabaseHas(
        'review_comments',
        [
            'review_id' => $review->id,
            'user_id' => $user->id,
            'text' => $textComment,
        ]
    );
});
