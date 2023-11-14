<?php

use App\Http\Controllers\Analytics\AnalyticsController;
use App\Http\Controllers\Analytics\AnalyticsSettingsController;
use App\Http\Controllers\Campaign\CampaignBalancesController;
use App\Http\Controllers\Campaign\CampaignController;
use App\Http\Controllers\Campaign\Checks\CampaignChecksController;
use App\Http\Controllers\Campaign\CreateCampaignController;
use App\Http\Controllers\Campaign\DeleteCampaignController;
use App\Http\Controllers\Campaign\DispatchCampaignUpdateController;
use App\Http\Controllers\Campaign\EditCampaignController;
use App\Http\Controllers\Campaign\PlanFact\PlanFactInitController;
use App\Http\Controllers\Campaign\PlanFact\PlanFactOrderController;
use App\Http\Controllers\Campaign\PlanFact\PlanFactSettingsController;
use App\Http\Controllers\Campaign\ProjectMembersController;
use App\Http\Controllers\Campaign\ProjectsListController;
use App\Http\Controllers\Campaign\RedirectProjectController;
use App\Http\Controllers\Campaign\SettingsCampaignController;
use App\Http\Controllers\Campaign\Subscriptions\ChooseSubscriptionController;
use App\Http\Controllers\Source\Ads\Settings\AdsenseSettingsController;
use App\Http\Controllers\Source\Analytics\AnalyticsSettingsController as SourceAnalyticsSettingsController;
use App\Http\Controllers\Source\Analytics\BrowseAnalyticsSummaryController;
use App\Http\Controllers\Source\BrowseSummaryController;
use App\Http\Controllers\Source\Metrika\BrowseMetrikaSummaryController;
use App\Http\Controllers\Source\Settings\MetrikaSettingsController;
use App\Http\Controllers\Source\SourcesController;
use App\Http\Controllers\Source\SourcesDeleteController;
use App\Http\Controllers\Source\Vk\Settings\ShowSettingsController as VkShowSettingsController;
use App\Http\Controllers\Source\Vk\Settings\StoreSettingsController as VkStoreSettingsController;
use App\Http\Controllers\Source\Vk\Settings\UpdateSettingsController as VkUpdateSettingsController;
use App\Http\Controllers\Source\Vk\Settings\VkSettingsController;
use App\Http\Controllers\Source\YandexDirect\YandexDirectSettingsController;
use App\Http\Middleware\HasCampaignAccessMiddleware;
use Illuminate\Support\Facades\Route;
use Module\Billing\Subscription\Enums\PlanFeature;
use Module\Source\Sources\Models\Source;

Route::get('settings', [CampaignController::class, 'settings'])
    ->name('settings');

Route::get('create', [CreateCampaignController::class, 'show'])
    ->name('create');
Route::post('create', [CreateCampaignController::class, 'store'])
    ->name('create.store');

Route::get('/', [ProjectsListController::class, 'show'])
    ->name('index');

Route::prefix('analytics')
    ->middleware('hasSource')
    ->middleware('hasFeature:' . PlanFeature::ANALYTICS->value)
    ->group(static function () {
        Route::get('data/projects', [AnalyticsController::class, 'loadManyProjects'])
            ->name('analytics-data.projects');

        Route::get('data/paths', [AnalyticsController::class, 'loadManyPaths'])
            ->name('analytics-data.paths');
    });

Route::get('balances', [CampaignBalancesController::class, 'forManyProjects'])
    ->name('balances.for-many');

Route::prefix('{campaign}')
    ->middleware(HasCampaignAccessMiddleware::class)
    ->group(function () {
        Route::get('/', RedirectProjectController::class)
            ->name('redirect');

        Route::prefix('settings')
            ->controller(SettingsCampaignController::class)
            ->group(static function () {
                Route::get('', 'show')
                    ->name('project_settings');

                Route::put('checks', 'storeChecks')
                    ->name('project_settings.store_checks');

                Route::put('notifications', 'storeCampaignNotifications')
                    ->name('project_settings.store_notifications');

                Route::put('analytics', 'storeAnalytics')
                    ->name('project_settings.store_analytics');
            });

        Route::get('delete', [DeleteCampaignController::class, 'show'])
            ->name('delete');
        Route::post('delete', DeleteCampaignController::class)
            ->name('delete.action');

        Route::get('edit', [EditCampaignController::class, 'show'])
            ->name('edit');
        Route::post('edit', [EditCampaignController::class, 'store'])
            ->name('edit.action');

        Route::post('members/add', [ProjectMembersController::class, 'add'])
            ->name('members.add');
        Route::delete('members/remove', [ProjectMembersController::class, 'remove'])
            ->name('members.delete');

        Route::middleware('hasSource')
            ->group(static function () {
                Route::prefix('analytics')
                    ->middleware('hasSource')
                    ->middleware('hasFeature:' . PlanFeature::ANALYTICS->value)
                    ->group(static function () {
                        Route::get('data', [AnalyticsController::class, 'load'])
                            ->name('analytics-data');

                        Route::get('settings', [AnalyticsSettingsController::class, 'load'])
                            ->name('analytics.settings.load');

                        Route::post('settings', [AnalyticsSettingsController::class, 'store'])
                            ->name('analytics.settings.store');

                        Route::get('/', [AnalyticsController::class, 'show'])
                            ->name('analytics');
                    });

                Route::prefix('plan-fact')
                    ->as('planfact.')
                    ->middleware('hasFeature:' . PlanFeature::PLANFACT->value)
                    ->middleware('hasSource')
                    ->group(function () {
                        Route::get('/add', [PlanFactSettingsController::class, 'showAddPlan'])
                            ->name('add.show');
                        Route::post('/add', [PlanFactSettingsController::class, 'addPlan'])
                            ->name('add.store');

                        Route::get('/edit/{planSettings}', [PlanFactSettingsController::class, 'showEditPlan'])
                            ->name('edit.show')
                            ->scopeBindings();
                        Route::put('/edit/{planSettings}', [PlanFactSettingsController::class, 'editPlan'])
                            ->name('edit.store')
                            ->scopeBindings();

                        Route::delete('/delete/{planSettings}', [PlanFactSettingsController::class, 'deletePlan'])
                            ->name('delete')
                            ->scopeBindings();

                        Route::put('/plans-order', [PlanFactSettingsController::class, 'updateOrder'])
                            ->name('plans-order.update');

                        Route::put('/order', [PlanFactOrderController::class, 'store'])
                            ->name('order.store');

                        Route::get('init', PlanFactInitController::class)
                            ->name('init');
                    });

                Route::middleware('hasFeature')
                    ->group(function () {
                        Route::get('browse', BrowseSummaryController::class)
                            ->name('browse');

                        Route::get('metrika/browse', BrowseMetrikaSummaryController::class)
                            ->name('browse.metrika');

                        Route::get('analytics/browse', BrowseAnalyticsSummaryController::class)
                            ->name('browse.analytics');
                    });

                Route::get('balances', CampaignBalancesController::class)
                    ->name('balances.get');

                Route::get('audit', [BrowseMetrikaSummaryController::class, 'getAudit'])
                    ->name('browse.audit');

                Route::controller(CampaignChecksController::class)
                    ->middleware('hasSource:' . implode('|', Source::CABINET_SOURCES))
                    ->middleware('hasFeature:' . PlanFeature::AUDIT->value)
                    ->group(function () {
                        Route::get('checks/export-source/{source?}', 'exportSource')
                            ->name('checks.export_source');
                        Route::get('checks/{source?}', 'show')
                            ->name('checks');
                        Route::post('checks/load', 'load')
                            ->name('checks.load');
                    });

                Route::post('fetch', DispatchCampaignUpdateController::class)
                    ->name('fetch');
            });

        Route::prefix('subscriptions')
            ->as('subscriptions.')
            ->group(static function () {
                Route::put('resume', [ChooseSubscriptionController::class, 'resume'])
                    ->name('resume');

                Route::get('choose', [ChooseSubscriptionController::class, 'show'])
                    ->name('choose.show');

                Route::post('choose', [ChooseSubscriptionController::class, 'store'])
                    ->name('choose.store');

                Route::delete('choose', [ChooseSubscriptionController::class, 'delete'])
                    ->name('choose.delete');
            });
    });

Route::prefix('{campaign}/source')
    ->middleware(HasCampaignAccessMiddleware::class)
    ->group(function () {
        Route::get('/', SourcesController::class)
            ->name('source');

        Route::delete('{source_type}', SourcesDeleteController::class)
            ->name('source.delete');

        // Yandex Metrika
        Route::get('/settings/yandex-metrika', [MetrikaSettingsController::class, 'show'])
            ->middleware('validToken:' . Source::TYPE_YANDEX_METRIKA)
            ->name('source.settings.yandex-metrika.show');
        Route::put('/settings/yandex-metrika', [MetrikaSettingsController::class, 'store'])
            ->name('source.settings.yandex-metrika.store');

        // Yandex Direct
        Route::get('/settings/yandex-direct', [YandexDirectSettingsController::class, 'show'])
            ->middleware('validToken:' . Source::TYPE_YANDEX_DIRECT)
            ->name('source.settings.yandex-direct.show');
        Route::put('/settings/yandex-direct', [YandexDirectSettingsController::class, 'store'])
            ->name('source.settings.yandex-direct.store');

        // Vk
        Route::get('/settings/vk', VkShowSettingsController::class)
            ->middleware('validToken:' . Source::TYPE_VK)
            ->name('source.settings.vk.show');
        Route::post('/settings/vk', VkStoreSettingsController::class)
            ->name('source.settings.vk.store');
        Route::put('/settings/vk', VkUpdateSettingsController::class)
            ->name('source.settings.vk.update');
        Route::get('/settings/vk/campaigns', [VkSettingsController::class, 'getCampaignList'])
            ->name('source.settings.vk.campaigns');
        Route::get('/settings/vk/clients', [VkSettingsController::class, 'getClientList'])
            ->name('source.settings.vk.clients');

        // Google Analytics
        Route::get('/settings/google-analytics', [SourceAnalyticsSettingsController::class, 'show'])
            ->middleware('validToken:' . Source::TYPE_GOOGLE_ANALYTICS)
            ->name('source.settings.google-analytics.show');
        Route::get('/settings/google-analytics/goals', [SourceAnalyticsSettingsController::class, 'getGoals'])
            ->name('source.settings.google-analytics.goals');
        Route::post('/settings/google-analytics', [SourceAnalyticsSettingsController::class, 'store'])
            ->name('source.settings.google-analytics.store');
        Route::put('/settings/google-analytics', [SourceAnalyticsSettingsController::class, 'update'])
            ->name('source.settings.google-analytics.update');

        // Google Ads
        Route::get('/settings/google-ads', [AdsenseSettingsController::class, 'show'])
            ->middleware('validToken:' . Source::TYPE_GOOGLE_ADS)
            ->name('source.settings.google-ads.show');
        Route::post('/settings/google-ads', [AdsenseSettingsController::class, 'store'])
            ->name('source.settings.google-ads.store');
        Route::get('/settings/google-ads/campaigns', [AdsenseSettingsController::class, 'campaigns'])
            ->name('source.settings.google-ads.campaigns');
    });
