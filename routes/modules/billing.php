<?php

use App\Http\Controllers\Billing\AutoRefillSettingsController;
use App\Http\Controllers\Billing\DaysLeftController;
use App\Http\Controllers\Billing\NewPaymentController;
use App\Http\Controllers\Billing\PaymentRedirectController;
use Illuminate\Support\Facades\Route;

Route::get('days-left', DaysLeftController::class)
    ->name('days-left.get');

Route::get('payment-redirect/success', [PaymentRedirectController::class, 'success']);
Route::get('payment-redirect/fail', [PaymentRedirectController::class, 'fail']);

Route::get('new-payment', NewPaymentController::class)
    ->name('new-payment.show');
Route::post('new-payment', [NewPaymentController::class, 'create'])
    ->name('new-payment.create');
Route::post('new-payment/invoice', [NewPaymentController::class, 'createInvoice'])
    ->name('new-payment.create-invoice');

Route::put('new-payment/update-plan', [NewPaymentController::class, 'updateSubscription'])
    ->name('new-payment.update-plan');

Route::post('new-payment/discount-code', [NewPaymentController::class, 'applyDiscountCode'])
    ->name('new-payment.discount-code.apply');

Route::prefix('auto-refill-settings')->as('auto-refill-settings.')->group(static function () {
    Route::get('', [AutoRefillSettingsController::class, 'getSettings'])
        ->name('get');

    Route::put('', [AutoRefillSettingsController::class, 'saveSettings'])
        ->name('save');

    Route::get('payment-methods', [AutoRefillSettingsController::class, 'getPaymentMethods'])
        ->name('get-payment-methods');

    Route::delete('delete-payment-method', [AutoRefillSettingsController::class, 'deletePaymentMethod'])
        ->name('delete-payment-method');
});

