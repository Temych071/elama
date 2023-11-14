<?php

namespace App\Providers;

use App\Events\RegistrationFinishedEvent;
use App\Listeners\AnalyticsClearOnSourceUpdateListener;
use App\Listeners\LowBalanceListener;
use App\Listeners\NegativeBalanceListener;
use App\Listeners\NewReviewListener;
use App\Listeners\NewSubscriptionListener;
use App\Listeners\NewTransactionListener;
use App\Listeners\UserRegisteredListener;
use App\Listeners\UserVerifiedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Module\Billing\Events\LowBalanceEvent;
use Module\Billing\Events\NegativeBalanceEvent;
use Module\Billing\Events\NewSubscriptionEvent;
use Module\Billing\Events\NewTransactionEvent;
use Module\Billing\Payments\Listeners\AutoRefillNewTransactionListener;
use Module\Billing\Subscription\Listeners\AddSubscriptionForNewProjectListener;
use Module\Campaign\Events\CreateProjectEvent;
use Module\Source\Sources\Events\SourceUpdateFinishedEvent;
use Reviews\Events\NewReviewEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RegistrationFinishedEvent::class => [
            UserRegisteredListener::class,
        ],
        Verified::class => [
            UserVerifiedListener::class,
        ],

        LowBalanceEvent::class => [
            LowBalanceListener::class,
        ],
        NegativeBalanceEvent::class => [
            NegativeBalanceListener::class,
        ],
        NewTransactionEvent::class => [
            NewTransactionListener::class,
            AutoRefillNewTransactionListener::class,
        ],
        NewSubscriptionEvent::class => [
            NewSubscriptionListener::class
        ],

        CreateProjectEvent::class => [
            AddSubscriptionForNewProjectListener::class,
        ],

        SourceUpdateFinishedEvent::class => [
            AnalyticsClearOnSourceUpdateListener::class,
        ],

        NewReviewEvent::class => [
            NewReviewListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
