<?php

use SocialWidget\Http\Controllers\Callback\AmoCrmAuthCallbackController;

Route::prefix('crm')->as('crm.')->group(static function () {
    Route::get('amo/auth-redirect', AmoCrmAuthCallbackController::class)
//        ->middleware('auth')
        ->name('amo.auth-redirect');
});
