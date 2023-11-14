<?php

use App\Http\Controllers\Admin\Billing\BillingSettingsController;
use App\Http\Controllers\Admin\Billing\DiscountCodes\CreateDiscountCodesFormController;
use App\Http\Controllers\Admin\Billing\DiscountCodes\EditDiscountCodeController;
use App\Http\Controllers\Admin\Billing\DiscountCodes\ShowDiscountCodesController;
use App\Http\Controllers\Admin\Billing\DiscountCodes\StoreDiscountCodesController;
use App\Http\Controllers\Admin\Billing\DiscountCodes\UpdateDiscountCodeController;
use App\Http\Controllers\Admin\Billing\Invoices\InvoicesConfirmController;
use App\Http\Controllers\Admin\Billing\Invoices\InvoicesListController;
use App\Http\Controllers\Admin\CampaignsListController;
use App\Http\Controllers\Admin\Plans\PlanSettingsController;
use App\Http\Controllers\Admin\Plans\PlansListController;
use App\Http\Controllers\Admin\SettingsRequestsController;
use App\Http\Controllers\Admin\Transactions\CreateTransactionController;
use App\Http\Controllers\Admin\Transactions\ShowTransactionListController;
use App\Http\Controllers\Admin\Users\LoginAsUserController;
use App\Http\Controllers\Admin\Users\UserCreateController;
use App\Http\Controllers\Admin\Users\UserEditController;
use App\Http\Controllers\Admin\Users\UsersListController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CampaignsListController::class, 'show'])
    ->name('campaigns');

Route::get('statistics', [CampaignsListController::class, 'show'])
    ->name('statistics');

Route::prefix('settings-requests')->group(static function () {
    Route::get('/', SettingsRequestsController::class)
        ->name('settingsRequests');

    Route::delete('/delete', [SettingsRequestsController::class, 'delete'])
        ->name('settingsRequests.delete');
});

Route::prefix('users')->as('users.')->group(static function () {
    Route::get('/', UsersListController::class)
        ->name('list');

    Route::post('login/{user}', LoginAsUserController::class)
        ->name('login');

    Route::get('/export', [UsersListController::class, 'export'])
        ->name('export');

    Route::prefix('edit/{user}')->as('edit.')->group(static function () {
        Route::get('/', [UserEditController::class, 'show'])
            ->name('show');

        Route::put('/', [UserEditController::class, 'store'])
            ->name('store');

        Route::delete('/', [UserEditController::class, 'delete'])
            ->name('delete');
    });

    Route::prefix('create')->as('create.')->group(static function () {
        Route::get('/', [UserCreateController::class, 'show'])
            ->name('show');
        Route::post('/store', [UserCreateController::class, 'store'])
            ->name('store');
    });
});

Route::prefix('billing/settings')->as('billing.settings.')->group(static function () {
    Route::get('/', [BillingSettingsController::class, 'show'])->name('show');
    Route::put('/', [BillingSettingsController::class, 'store'])->name('store');
});

Route::prefix('plans')->as('plans.')->group(static function () {
    Route::get('/', PlansListController::class)
        ->name('list');

    Route::post('/', [PlanSettingsController::class, 'create'])
        ->name('create');

    Route::get('/{plan}', [PlanSettingsController::class, 'updateForm'])
        ->name('update.form');

    Route::put('/{plan}', [PlanSettingsController::class, 'update'])
        ->name('update');

    Route::delete('/{plan}', [PlanSettingsController::class, 'delete'])
        ->name('delete');
});

Route::get('invoices', InvoicesListController::class)
    ->name('invoices.list');

Route::put('invoices', InvoicesConfirmController::class)
    ->name('invoices.confirm');

Route::get('transactions', ShowTransactionListController::class)
    ->name('transactions.list');

Route::post('transactions', CreateTransactionController::class)
    ->name('transactions.create');

Route::get('discount-codes', ShowDiscountCodesController::class)
    ->name('discount-codes.list');

Route::get('discount-codes/create', CreateDiscountCodesFormController::class)
    ->name('discount-codes.create');
Route::post('discount-codes', StoreDiscountCodesController::class)
    ->name('discount-codes.store');

Route::get('discount-codes/{code_id}/edit', EditDiscountCodeController::class)
    ->name('discount-codes.edit');
Route::put('discount-codes/{code_id}/edit', UpdateDiscountCodeController::class)
    ->name('discount-codes.update');
