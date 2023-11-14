<?php

namespace Module\Billing\Subscription\Services;

use Illuminate\Support\ServiceProvider;

class SubscriptionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SubscriptionService::class);
    }

    public function boot(): void
    {
        //
    }
}
