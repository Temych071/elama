<?php

declare(strict_types=1);

namespace Loyalty;

use App;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Loyalty\Contracts\SmsSenderService;
use Loyalty\GoogleWallet\Services\LoyaltyToClassConverter;
use Loyalty\Http\Middleware\ForceAcceptJsonResponse;
use Loyalty\Models\Loyalty;
use Loyalty\Services\Sms\LogSmsService;
use Loyalty\Services\Sms\ProstoSmsService;

final class LoyaltyServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        if (config('app.debug')) {
            App::bind(SmsSenderService::class, LogSmsService::class);
        } else {
            App::bind(SmsSenderService::class, ProstoSmsService::class);
        }

        App::singleton(LoyaltyToClassConverter::class);

        Route::bind('loyalty',
            static fn($uuid, \Illuminate\Routing\Route $route) => $route
                ->parameters['campaign']
                ->loyalties()
                ->findOrFail($uuid));
        Route::bind('publicLoyalty', static fn(string $uuid) => Loyalty::query()->findOrFail($uuid));
        Route::bind('apiLoyalty',
            static fn(string $token) => Loyalty::query()
                ->where('api_token', $token)
                ->firstOrFail());

        $this->routes(function (): void {
            // api
            Route::prefix('loyalty/api/v1')
                ->as('loyalty.api.v1.')
                ->middleware([
                    'api',

                    // Насколько это костыль?))
                    ForceAcceptJsonResponse::class,
                ])
                ->namespace($this->namespace)
                ->group(self::routeFile('api'));

            // public
            Route::prefix('l')
                ->as('loyalty.public.')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(self::routeFile('public'));

            // private
            Route::prefix('c/{campaign}/loyalty')
                ->as('loyalty.private.')
                ->middleware([
                    'web',
                    App\Http\Middleware\Authenticate::class,
                    App\Http\Middleware\HasCampaignAccessMiddleware::class,
                ])
                ->namespace($this->namespace)
                ->group(self::routeFile('private'));
        });

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    private static function routeFile(string $name): string
    {
        return __DIR__."/../routes/$name.php";
    }
}
