<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ElamaAuthController;
use App\Http\Controllers\Admin\Users\ElamaUsersListController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/register-finish', [GoogleAuthController::class, 'registerFinishShow'])
    ->middleware('auth')
    ->name('register-finish');

Route::post('/register-finish', [GoogleAuthController::class, 'registerFinish'])
    ->middleware('auth')
    ->name('register-finish.send');

/** Google oAuth */
Route::prefix('auth/google')->middleware('guest')->as('auth.google.')->group(function () {
    Route::get('redirect', [GoogleAuthController::class, 'googleRedirect'])
        ->name('redirect');

    Route::get('callback', [GoogleAuthController::class, 'googleCallback'])
        ->name('callback');
});


/** Elama oAuth */

Route::get('/register-finish-elama', [ElamaAuthController::class, 'registerFinishShow'])
    ->middleware('auth')
    ->name('register-finish-elama');

Route::post('/register-finish-elama', [ElamaAuthController::class, 'registerFinish'])
    ->middleware('auth')
    ->name('register-finish-elama.send');

Route::prefix('auth/elama')->middleware('guest')->as('auth.elama.')->group(function () {
    Route::get('redirect', [ElamaAuthController::class, 'elamaRedirect'])
        ->name('redirect');

    Route::get('callback', [ElamaAuthController::class, 'elamaCallback'])
        ->name('callback');

    Route::get('export', [ElamaUsersListController::class, 'export'])
        ->name('export');    
});

