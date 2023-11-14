<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Plans;

use App\Http\Requests\Admin\PlanRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Module\Billing\Subscription\Enums\PlanFeature;
use Module\Billing\Subscription\Enums\PlanStatus;
use Module\Billing\Subscription\Models\Plan;
use Throwable;

final class PlanSettingsController
{
    public function create(PlanRequest $request): RedirectResponse
    {
        Plan::query()->create($request->validated());

        return redirect()->route('admin.plans.list')->with('toast', [
            'type' => 'success',
            'message' => trans('toasts.admin.plans.created'),
        ]);
    }

    public function updateForm(Plan $plan): Response
    {
        return Inertia::render('Admin/Plans/Edit', [
            'plan' => $plan,
            'statuses' => PlanStatus::cases(),
            'features' => PlanFeature::cases(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(PlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->updateOrFail($request->validated());

        return redirect()->route('admin.plans.list')->with('toast', [
            'type' => 'success',
            'message' => trans('toasts.admin.plans.updated'),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function delete(Plan $plan): RedirectResponse
    {
        $plan->deleteOrFail();

        return redirect()->route('admin.plans.list')->with('toast', [
            'type' => 'success',
            'message' => trans('toasts.admin.plans.deleted'),
        ]);
    }
}
