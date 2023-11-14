<?php

use Loyalty\Http\Controllers\Public\LoyaltyCardController;
use Loyalty\Http\Controllers\Public\LoyaltyClientLogout;
use Loyalty\Http\Controllers\Public\LoyaltyLoginController;
use Loyalty\Http\Controllers\Public\LoyaltyPhoneVerifyController;
use Loyalty\Http\Controllers\Public\LoyaltyFormController;
use Loyalty\Http\Middleware\LoyaltyClientAuth;
use Loyalty\Http\Middleware\LoyaltyClientFormFilled;
use Loyalty\Http\Middleware\LoyaltyClientFormNotFilled;
use Loyalty\Http\Middleware\LoyaltyClientGuest;

Route::prefix('{publicLoyalty}')->group(static function () {
    Route::middleware(LoyaltyClientGuest::class)->group(static function () {
        Route::get('/', [LoyaltyLoginController::class, 'show'])
            ->name('login.show');
        Route::post('/', [LoyaltyLoginController::class, 'login'])
            ->name('login.send');

        Route::get('phone', [LoyaltyPhoneVerifyController::class, 'show'])
            ->name('phone-verification.show');
        Route::post('phone', [LoyaltyPhoneVerifyController::class, 'send'])
            ->name('phone-verification.send');
        Route::post('phone/resend-code', [LoyaltyPhoneVerifyController::class, 'resendCode'])
            ->name('phone-verification.resend-code');
    });

    Route::middleware([
        LoyaltyClientAuth::class,
//        LoyaltyClientVerifiedPhone::class,
    ])->group(static function () {
        Route::delete('logout', LoyaltyClientLogout::class)
            ->name('logout');

        Route::middleware(LoyaltyClientFormNotFilled::class)->group(static function () {
            Route::get('/form', [LoyaltyFormController::class, 'show'])
                ->name('form.show');
            Route::post('/form', [LoyaltyFormController::class, 'send'])
                ->name('form.send');
        });

        Route::middleware(LoyaltyClientFormFilled::class)->group(static function () {
            Route::get('/card', [LoyaltyCardController::class, 'show'])
                ->name('card.show');

            Route::post('/card/add/google', [LoyaltyCardController::class, 'redirectToGoogleWallet'])
                ->name('card.add-google');

            Route::get('/card/add/apple', [LoyaltyCardController::class, 'getAppleWalletPass'])
                ->name('card.add-apple');
        });
    });
});
