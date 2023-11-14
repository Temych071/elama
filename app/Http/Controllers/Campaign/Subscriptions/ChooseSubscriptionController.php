<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign\Subscriptions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Module\Billing\Subscription\Enums\PlanStatus;
use Module\Billing\Subscription\Exceptions\SubscriptionNotExistsException;
use Module\Billing\Subscription\Models\Plan;
use Module\Billing\Subscription\Services\SubscriptionService;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;
use Throwable;

final class ChooseSubscriptionController
{
    public function show(Campaign $campaign): Response
    {
        return Inertia::render('Campaign/Subscriptions/Choose', [
            'plans' => Plan::query()->where('status', PlanStatus::active)->get(),
            'subscription' => $campaign->activeSubscription,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request, Campaign $campaign, SubscriptionService $service): RedirectResponse
    {
        $data = $request->validate([
            'plan_id' => [
                'required',
                'numeric',
                Rule::exists('billing_plans', 'id')
                    ->where('status', PlanStatus::active->value),
            ]
        ]);

        /** @var User $user */
        $user = $request->user();

        /** @var Plan $plan */
        $plan = Plan::query()->findOrFail($data['plan_id']);

        $service->replace($campaign, $user, $plan);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Подписка успешно оформлена.',
        ]);
    }

    public function delete(Campaign $campaign, SubscriptionService $service): RedirectResponse
    {
        $service->end($campaign);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Подписка успешно отменена.',
        ]);
    }

    /**
     * @throws Throwable
     * @throws SubscriptionNotExistsException
     */
    public function resume(Campaign $campaign, SubscriptionService $service): RedirectResponse
    {
        return redirect()->back()->with(
            'toast',
            $service->resume($campaign) ? [
                'type' => 'success',
                'message' => 'Подписка успешно возобновлена.',
            ] : [
                'type' => 'error',
                'message' => 'Недостаточно средств для возобновления подписки.',
            ]
        );
    }
}
