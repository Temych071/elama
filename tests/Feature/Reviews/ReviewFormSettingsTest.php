<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Reviews\Actions\CreateReviewFormAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertNotNull;

uses()->group('reviews');

test('user can not see settings without subscription');

test('user can see settings without forms', function (): void {
    $user = actingAsUser();
    $project = $user->createCampaign('Test project');

    get(route('reviews.private.forms', ['campaign' => $project]))
        ->assertInertia(
            fn (Assert $page): Assert => $page
            ->component('Reviews/Private/Settings')
            ->has('project', fn (Assert $page): Assert => $page->where('id', $project->id)->etc())
            ->where('reviewForm', null)
            ->has('reviewForms', 0)
        )
        ->assertSuccessful();
});

test('user can see settings with forms', function (): void {
    $user = actingAsUser();
    $project = $user->createCampaign('Test project');
    $reviewForm = app(CreateReviewFormAction::class)->execute($project, 'Test form');

    get(route('reviews.private.forms.show', ['campaign' => $project, 'reviewForm' => $reviewForm]))
        ->assertInertia(
            fn (Assert $page): Assert => $page
            ->component('Reviews/Private/Settings')
            ->has('project', fn (Assert $page): Assert => $page->where('id', $project->id)->etc())
            ->has('reviewForm', fn (Assert $page): Assert => $page->where('id', $reviewForm->id)->etc())
            ->has('reviewForms', 1)
        )
        ->assertSuccessful();
});

test('user can create form', function (): void {
    $testFormName = 'Test form';

    $user = actingAsUser();
    $project = $user->createCampaign('Test project');

    $creationResponse = post(route('reviews.private.forms.init', ['campaign' => $project]), [
        'name' => $testFormName,
    ]);

    assertDatabaseHas('review_forms', [
        'name' => $testFormName,
    ]);

    $formId = DB::table('review_forms')->where('name', $testFormName)->first()->id;
    $creationResponse->assertRedirect(route('reviews.private.forms.show', [
        'campaign' => $project,
        'reviewForm' => $formId,
    ]));
});

test('user can update form', function (): void {
    $user = actingAsUser();
    $project = $user->createCampaign('Test project');
    $reviewForm = app(CreateReviewFormAction::class)->execute($project, 'Test form');

    put(route('reviews.private.forms.update', [
        'campaign' => $project,
        'reviewForm' => $reviewForm,
    ]), [
        ...$reviewForm->toArray(),
        'name' => 'New name',
    ])->assertValid()->assertRedirect();

    assertDatabaseMissing('review_forms', [
        'name' => 'Test form',
    ]);

    assertDatabaseHas('review_forms', [
        'name' => 'New name',
    ]);
});

test('user can delete form', function (): void {
    $user = actingAsUser();
    $project = $user->createCampaign('Test project');
    $reviewForm = app(CreateReviewFormAction::class)->execute($project, 'Test form');

    \Pest\Laravel\delete(route('reviews.private.forms.delete', [
        'campaign' => $project,
        'reviewForm' => $reviewForm,
    ]));

    $deletedForm = DB::table('review_forms')
        ->whereNotNull('deleted_at')
        ->where('name', 'Test form')
        ->first();

    assertNotNull($deletedForm);
});

test('user can upload logo and banner', function (): void {
    $user = actingAsUser();
    $project = $user->createCampaign('Test project');
    $reviewForm = app(CreateReviewFormAction::class)->execute($project, 'Test form');

    put(route('reviews.private.forms.update', [
        'campaign' => $project,
        'reviewForm' => $reviewForm,
    ]), [
        ...$reviewForm->toArray(),
        'logo' => UploadedFile::fake()->image('logo.png'),
        'banner' => UploadedFile::fake()->image('banner.png'),
    ])->assertValid()->assertRedirect();

    $reviewForm = $reviewForm->fresh();

    assertFileExists(Storage::disk('public')->path($reviewForm->logo_path));
    assertFileExists(Storage::disk('public')->path($reviewForm->banner_path));
});
