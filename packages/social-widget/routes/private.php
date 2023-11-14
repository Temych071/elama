<?php

use SocialWidget\Http\Controllers\Private\IntegrationsSettingsWidgetController;
use SocialWidget\Http\Controllers\Private\MessengersSettingsWidgetController;
use SocialWidget\Http\Controllers\Private\ViewSettingsWidgetController;
use SocialWidget\Http\Controllers\Private\WidgetsController;
use SocialWidget\Http\Controllers\Private\WidgetStatsController;

Route::get('/', [WidgetsController::class, 'root']);

Route::get('widgets', WidgetsController::class)
    ->name('index');

Route::post('/widgets', [WidgetsController::class, 'createWidget'])
    ->name('create');

Route::delete('{socialWidget}', [WidgetsController::class, 'deleteWidget'])
    ->name('delete');


Route::get('{socialWidget}/stats', WidgetStatsController::class)
    ->name('stats');

Route::prefix('{socialWidget}/settings')->as('settings.')->group(static function () {
    Route::get('channels', MessengersSettingsWidgetController::class)
        ->name('channels');

    Route::put('channels', [MessengersSettingsWidgetController::class, 'save'])
        ->name('channels.save');

    Route::post('channels/send-code', [MessengersSettingsWidgetController::class, 'sendCode'])
        ->name('channels.send-code');

    Route::get('view', ViewSettingsWidgetController::class)
        ->name('view');

    Route::put('view', [ViewSettingsWidgetController::class, 'save'])
        ->name('view.save');

    Route::get('integrations', IntegrationsSettingsWidgetController::class)
        ->name('integrations');

    Route::put('integrations/stats', [IntegrationsSettingsWidgetController::class, 'saveStats'])
        ->name('integrations.saveStats');

    Route::put('integrations/crm', [IntegrationsSettingsWidgetController::class, 'saveCrm'])
        ->name('integrations.saveCrm');

    Route::get('integrations/amo-auth', [IntegrationsSettingsWidgetController::class, 'amoAuthRedirect'])
        ->name('integrations.amo-auth');
});
