<?php

use App\Http\Controllers\User\ChangePassController;
use App\Http\Controllers\User\SettingsNotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('billing')
    ->as('billing.')
    ->group(static function () {
        require __DIR__ . '/billing.php';
    });

Route::prefix('settings-notifications')
    ->as('settings_notifications.')
    ->controller(SettingsNotificationController::class)
    ->group(static function () {
        Route::get('/', 'show')
            ->name('show');

        Route::post('/', 'setTelegramNotifications')
            ->name('set_telegram_notification');
        Route::delete('/unset-telegram', 'unsetTelegramNotifications')
            ->name('unset_telegram_notification');

        Route::post('/set-email', 'setEmailNotifications')
            ->name('set_email_notification');
        Route::delete('/unset-email', 'unsetEmailNotifications')
            ->name('unset_email_notification');
    });

Route::get('change-pass', [ChangePassController::class, 'show'])
    ->name('change-pass');

Route::post('change-pass', [ChangePassController::class, 'store'])
    ->name('change-pass.store');
