<?php

use App\Http\Controllers\Billing\PaymentCallbackController;
use App\Http\Controllers\Source\Vk\VkWebhookController;
use App\Http\Controllers\Telegram\TelegramWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('user/billing/payment-callback', PaymentCallbackController::class)
    ->name('user.billing.payment-callback');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('source/vk/webhook', VkWebhookController::class)
    ->name('source.vk.webhook');

Route::any('/telegram-webhook', TelegramWebhookController::class);
