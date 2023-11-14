<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Billing;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Module\Admin\Settings\BillingSettings;
use Module\Billing\Subscription\Models\Plan;

final class BillingSettingsController
{
    public function show(BillingSettings $settings): Response
    {
        return Inertia::render('Admin/Billing/Settings', [
            'settings' => $settings,
            'plans' => Plan::all(),
        ]);
    }

    public function store(Request $request, BillingSettings $settings): RedirectResponse
    {
        $data = $request->validate([
            'trial_balance' => ['required', 'numeric', 'min:0'],
            'initial_plan_id' => ['nullable', 'numeric', 'exists:billing_plans,id'],
        ]);

        $settings->fill($data)->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройки сохранены успешно.',
        ]);
    }
}
