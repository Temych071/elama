<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Module\Campaign\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Module\User\Models\User;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    final public const HOME = '/c';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';
    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        
        $this->configureRateLimiting();
        Route::model('campaign', Campaign::class);
		
        $this->routes(function (): void {
            Route::prefix('api')
                ->middleware(['api'])
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware(['web'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth', 'verified'])
                ->namespace($this->namespace)
                ->group(base_path('routes/dashboard.php'));

            Route::middleware(['web', 'auth', 'verified'])
                ->namespace($this->namespace)
                ->prefix('c')
                ->as('campaign.')
                ->group(base_path('routes/modules/campaign.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn(Request $request): \Illuminate\Cache\RateLimiting\Limit => Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip()));
    }
}
