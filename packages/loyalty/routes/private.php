<?php

use Loyalty\Http\Controllers\Private\LoyaltyAnalyticsPrivateController;
use Loyalty\Http\Controllers\Private\LoyaltyCardSettingsPrivateController;
use Loyalty\Http\Controllers\Private\LoyaltyFormSettingsPrivateController;
use Loyalty\Http\Controllers\Private\LoyaltyIndexController;
use Loyalty\Http\Controllers\Private\LoyaltyIntegrationSettingsController;
use Loyalty\Http\Middleware\OnlyForProjectOwner;

Route::get('/', LoyaltyIndexController::class)
    ->name('loyalty.index');

Route::post('/', [LoyaltyIndexController::class, 'create'])
    ->name('loyalty.create');

Route::prefix('{loyalty}')->group(static function (): void {
    Route::get('/', [LoyaltyIndexController::class, 'showLoyalty'])
        ->name('loyalty.show');

    Route::get('/analytics', [LoyaltyAnalyticsPrivateController::class, 'show'])
        ->name('loyalty.analytics.show');

    Route::get('/form-settings', [LoyaltyFormSettingsPrivateController::class, 'show'])
        ->name('loyalty.form-settings.show');
    Route::put('/form-settings', [LoyaltyFormSettingsPrivateController::class, 'save'])
        ->name('loyalty.form-settings.save');

    Route::get('/card-settings', [LoyaltyCardSettingsPrivateController::class, 'show'])
        ->name('loyalty.card-settings.show');
    Route::put('/card-settings', [LoyaltyCardSettingsPrivateController::class, 'save'])
        ->name('loyalty.card-settings.save');

    Route::get('/integration-settings', [LoyaltyIntegrationSettingsController::class, 'show'])
        ->middleware(OnlyForProjectOwner::class)
        ->name('loyalty.integration-settings.show');

    Route::delete('/form-settings', [LoyaltyIndexController::class, 'remove'])
        ->name('loyalty.remove');
});

