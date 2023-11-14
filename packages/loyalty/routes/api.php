<?php

use Loyalty\Http\Controllers\Api\ApproveCardsApiController;
use Loyalty\Http\Controllers\Api\UploadCardsApiController;
use Loyalty\Http\Controllers\Api\DownloadCardsApiController;
use Loyalty\Http\Controllers\Api\TransactionsApiController;

Route::prefix('{apiLoyalty}')->group(static function () {
    Route::get('/', static fn() => 'DailyGrow Loyalty API')
        ->name('root');

    Route::post('cards', UploadCardsApiController::class);
    Route::get('cards', DownloadCardsApiController::class);
    Route::put('cards/approve', ApproveCardsApiController::class);
    Route::post('transactions', TransactionsApiController::class);
});
