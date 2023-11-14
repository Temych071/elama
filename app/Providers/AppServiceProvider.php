<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\Notification;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Module\Campaign\Models\Campaign;
use Module\Source\Vk\Services\VkService;
use Module\User\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VkService::class, function ($app): \Module\Source\Vk\Services\VkService {
            /** @var Campaign $campaign */
            $campaign = request('campaign') ?: request()?->route('campaign');

            return new VkService($campaign?->vkSource?->authToken);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'user' => User::class,
            'notification' => Notification::class,
        ]);

        if (env('APP_URL_FORCE_HTTPS', false)) {
            app(UrlGenerator::class)->forceScheme('https');
        }
    }
}
