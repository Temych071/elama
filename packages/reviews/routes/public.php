<?php

use Illuminate\Support\Facades\Route;
use Reviews\Http\Controllers\ReviewPublicController;

Route::view('policy', 'reviews.policy')
    ->name('policy');

Route::get('{slug}', [ReviewPublicController::class, 'show'])
    ->name('show');

Route::post('{slug}', [ReviewPublicController::class, 'send'])
    ->name('store');

Route::get('{slug}/average-rating', [ReviewPublicController::class, 'loadAvgRate'])
    ->name('average-rating');

Route::get('{slug}/review-list', [ReviewPublicController::class, 'loadReviews'])
    ->name('review-list');
