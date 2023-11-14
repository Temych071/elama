<?php

use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\SeoAudits\SeoAuditIndexController;
use App\Http\Controllers\SeoAudits\SeoAuditLinkCheckerController;
use App\Http\Controllers\SeoAudits\SeoAuditStartController;
use App\Http\Controllers\User\ReadNotificationsController;
use App\Http\Controllers\User\SettingsRequestController;
use App\Infrastructure\DateRange;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Reviews\Actions\FetchExternalStatsAction;
use Reviews\DTO\StatsSourceData;
use Reviews\Enums\ReviewSource;
use Reviews\Models\ReviewForm;
use Reviews\Parsers\Yandex\Services\YandexReviewsService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (config('app.debug', false)) {
    Route::get('/test', static function () {
        $placeId = '1010501395';

        $service = app(YandexReviewsService::class);

        return $service->getGeneralData($placeId);
    });
}

Route::get('/', DashboardRedirectController::class);

Route::get('/greeting', function () {
    return 'Hello World';
});



Route::middleware(['auth', 'hasPass', 'verified'])->group(function () {
    Route::prefix('user')
        ->as('user.')
        ->group(static function () {
            require __DIR__.'/modules/user.php';
        });

    Route::get('/dashboard', DashboardRedirectController::class)
        ->name('dashboard');

    // Seo
    Route::get('seo-audit', SeoAuditIndexController::class)
        ->name('seo-audit.index');

    Route::get('seo-audit/last', [SeoAuditLinkCheckerController::class, 'getLastAudit'])
        ->name('seo-audit.last');

    Route::get('seo-audit/{uuid}', [SeoAuditLinkCheckerController::class, 'auditByUuid'])
        ->name('seo-audit.item');

    Route::post('seo-audit', SeoAuditStartController::class)
        ->name('seo-audit.start');

    Route::get('seo-audit/export/{uuid}', [SeoAuditLinkCheckerController::class, 'exportSeoAudit'])
        ->name('seo-audit.export');

    // Help
    Route::get('/help', static fn() => Inertia::render('Dashboard'))
        ->name('help');

    Route::post('/read-notifications', [ReadNotificationsController::class, 'readNotifications'])
        ->name('read_notifications');

    Route::post('settings_request', SettingsRequestController::class)
        ->name('settingsRequest.store')
        ->middleware('throttle:3,1440,settingsRequest');
});

require __DIR__.'/auth.php';
