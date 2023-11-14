<?php

use Illuminate\Support\Facades\Route;
use Reviews\Http\Controllers\AnswerTemplatesController;
use Reviews\Http\Controllers\ReviewFormSettingsController;
use Reviews\Http\Controllers\ReviewsController;
use Reviews\Http\Controllers\ReviewStatsController;

Route::get('2gisWidget', [ReviewStatsController::class, 'widget2Gis'])
    ->name('2gis.widget');

Route::controller(ReviewsController::class)->group(static function () {
    Route::get('list', 'show')
        ->name('list');

    Route::get('rating-widget', 'getRatingWidget')
        ->name('reviews.rating-widget');

    Route::post('add-comment', 'addComment')
        ->name('add-comment');

    Route::put('{review}/status', 'updateStatus')
        ->name('update-status');

    Route::post('{review}/add-tag', 'addTag')
        ->name('reviews.add-tag');

    Route::post('{review}/remove-tag', 'removeTag')
        ->name('reviews.remove-tag');

    Route::post('{review}/send-answer', 'sendAnswer')
        ->name('reviews.send-answer');

    Route::put('{review}/update-answer', 'updateAnswer')
        ->name('reviews.update-answer');

    Route::delete('{review}/delete-answer', 'deleteAnswer')
        ->name('reviews.delete-answer');

    Route::delete('{review}', 'delete')
        ->name('delete');
});

Route::prefix('answer-templates')->group(static function () {
    Route::get('/', [AnswerTemplatesController::class, 'get'])
        ->name('answer-templates.get');
    Route::post('/', [AnswerTemplatesController::class, 'createTemplate'])
        ->name('answer-templates.create');
    Route::put('/{reviewAnswerTemplate}', [AnswerTemplatesController::class, 'updateTemplate'])
        ->name('answer-templates.update');
    Route::delete('/{reviewAnswerTemplate}', [AnswerTemplatesController::class, 'deleteTemplate'])
        ->name('answer-templates.delete');
});

Route::controller(ReviewStatsController::class)
    ->as('stats.')
    ->prefix('stats')
    ->group(static function () {
//        Route::get('/', 'show')
//            ->name('show');
        Route::get('first', 'first')
            ->name('first');
        Route::get('{reviewForm?}', 'show')
            ->name('show');

        Route::get('{reviewForm}/widgets', 'widgets')
            ->name('widgets');
    });

Route::prefix('forms')->group(static function () {
    Route::get('/', [ReviewFormSettingsController::class, 'show'])
        ->name('forms');
    Route::post('/', [ReviewFormSettingsController::class, 'init'])
        ->name('forms.init');

    Route::prefix('{reviewForm}')
        ->group(static function () {
            Route::get('/', [ReviewFormSettingsController::class, 'show'])
                ->name('forms.show');

            Route::put('/', [ReviewFormSettingsController::class, 'update'])
                ->name('forms.update');

            Route::delete('/', [ReviewFormSettingsController::class, 'delete'])
                ->name('forms.delete');

            Route::post('/copy', [ReviewFormSettingsController::class, 'copy'])
                ->name('forms.copy');

            Route::post('/update-2gis-access', [ReviewFormSettingsController::class, 'updateDoubleGisAccess'])
                ->name('forms.update-2gis-access');
        });
});
