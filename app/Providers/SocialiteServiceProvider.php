<?php

namespace App\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Yandex\Provider as YandexProvider;

class SocialiteServiceProvider extends ServiceProvider
{
    protected $listen = [
        SocialiteWasCalled::class => [
        ],
    ];

    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $socialite = $this->app->make(Factory::class);
/**/
        $this->regSourceDriver($socialite, 'source_google', GoogleProvider::class);
        $this->regSourceDriver($socialite, 'source_yandex', YandexProvider::class);
        $this->regSourceDriver($socialite, 'source_facebook', FacebookProvider::class);

        
    }

    private function regSourceDriver($socialite, string $name, string $provider): void
    {
        $socialite->extend($name, function () use ($socialite, $name, $provider) {
            $config = config("services.$name");

            return $socialite->buildProvider($provider, $config);
        });
    }
}
