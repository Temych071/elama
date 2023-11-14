<?php

use App\Http\Controllers\Source\Ads\AdsAuthCallbackController;
use App\Http\Controllers\Source\Analytics\Auth\AnalyticsAuthCallbackController;
use App\Http\Controllers\Source\Auth\AddSourceController;
use App\Http\Controllers\Source\Auth\SourceAuthCallbackController;
use App\Http\Controllers\Source\Avito\Auth\AvitoAuthCallbackController;
use App\Http\Controllers\Source\Vk\VkAuthCallbackController;

Route::post('source/add', AddSourceController::class)
    ->name('source.add');

Route::get('source/add/callback', SourceAuthCallbackController::class)
    ->name('source.add.callback');

Route::get('source/add/analytics/auth', [AnalyticsAuthCallbackController::class, 'authCallback'])
    ->name('source.add.analytics.auth-callback');

Route::get('source/add/ads/auth', AdsAuthCallbackController::class)
    ->name('source.add.ads.auth-callback');

Route::get('source/add/vk/auth', VkAuthCallbackController::class)
    ->name('source.add.vk.auth-callback');

Route::get('source/add/vk/auth', VkAuthCallbackController::class)
    ->name('source.add.vk.auth-callback');

Route::get('source/add/avito/auth', AvitoAuthCallbackController::class)
    ->name('source.add.avito.auth-callback');
