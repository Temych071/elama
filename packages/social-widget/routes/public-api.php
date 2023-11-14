<?php

use SocialWidget\Http\Controllers\PublicApi\TrackActionsController;
use SocialWidget\Http\Controllers\PublicApi\WidgetController;

Route::get('assets', [WidgetController::class, 'assets'])
    ->name('assets');

Route::post('{uuid}/track/view', [TrackActionsController::class, 'trackView'])
    ->name('track.view');

//Route::post('{uuid}/track/click', [TrackActionsController::class, 'trackClick'])
//    ->name('track.click');

Route::get('{uuid}', [WidgetController::class, 'load'])
    ->name('load-widget');

Route::get('{uuid}/redirect', [WidgetController::class, 'redirect'])
    ->name('redirect');
