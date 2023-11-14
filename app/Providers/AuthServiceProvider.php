<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Module\Billing\Subscription\Enums\PlanFeature;
use Module\Campaign\Models\Campaign;
use Module\User\Enums\UserRole;
use Module\User\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'Module\User\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(static function (User $user, $ability) {
            if ($user->role === UserRole::admin) {
                return true;
            }
        });

        Gate::define(
            PlanFeature::PLANFACT_MORE_CABINETS->value,
            static fn(User $user, Campaign $campaign, array $data): bool => $campaign->hasFeature(PlanFeature::PLANFACT_MORE_CABINETS)
            || count($data['sources'] ?? []) <= 1
        );
    }
}
