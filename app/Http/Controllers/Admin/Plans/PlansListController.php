<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Plans;

use Inertia\Inertia;
use Inertia\Response;
use Module\Billing\Subscription\Enums\PlanFeature;
use Module\Billing\Subscription\Enums\PlanStatus;
use Module\Billing\Subscription\Models\Plan;

final class PlansListController
{
    public function __invoke(): Response
    {
//        dd(Plan::all());
        return Inertia::render('Admin/Plans/List', [
            'plans' => Plan::all(),
            'statuses' => PlanStatus::cases(),
            'features' => PlanFeature::cases(),
        ]);
    }
}
