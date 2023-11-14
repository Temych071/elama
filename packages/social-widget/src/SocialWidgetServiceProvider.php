<?php

declare(strict_types=1);

namespace SocialWidget;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\HasCampaignAccessMiddleware;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;

final class SocialWidgetServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        Route::middlewareGroup('social-widget.public-api', [
            'throttle:api',
            SubstituteBindings::class,

        ]);

        Route::bind('socialWidget', static fn($uuid, \Illuminate\Routing\Route $route) => $route->parameters['campaign']->socialWidgets()->findOrFail($uuid));

        $this->routes(function (): void {
            // public-api
            Route::prefix('sw/api/v1')
                ->as('social-widget.public-api.v1.')
//                ->middleware('api')
                ->middleware('social-widget.public-api')
                ->namespace($this->namespace)
                ->group(self::routeFile('public-api'));

            // callback
            Route::prefix('sw')
                ->as('social-widget.callback.')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(self::routeFile('callback'));

            // private
            Route::prefix('c/{campaign}/sw')
                ->as('social-widget.private.')
                ->middleware([
                    'web',
                    Authenticate::class,
                    HasCampaignAccessMiddleware::class,
                ])
                ->namespace($this->namespace)
                ->group(self::routeFile('private'));
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    private static function routeFile(string $name): string
    {
        return __DIR__ . "/../routes/$name.php";
    }
}
