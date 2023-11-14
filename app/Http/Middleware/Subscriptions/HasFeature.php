<?php

declare(strict_types=1);

namespace App\Http\Middleware\Subscriptions;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Billing\Subscription\Models\Subscription;
use Module\User\Enums\UserRole;

final class HasFeature
{
    public function handle(Request $request, Closure $next, ?string $feature = null): mixed
    {
        if ($request->user()->role === UserRole::admin) {
            return $next($request);
        }

        $campaign = $request->route('campaign');
        if (!$campaign instanceof \Module\Campaign\Models\Campaign) {
            return $next($request);
        }

        /** @var ?Subscription $sub */
        $sub = $campaign
            ->subscriptions
            ->first(static fn (Subscription $sub): bool => $sub->status !== SubscriptionStatus::ended);

        if (is_null($sub)) {
            return redirect()
                ->route('campaign.subscriptions.choose.show', $campaign)
                ->with('toast', [
                    'type' => 'warning',
                    'message' => 'У проекта нет активного тарифа.',
                ]);
        }

        if ($sub->status === SubscriptionStatus::paused) {
            return redirect()
                ->route('campaign.subscriptions.choose.show', $campaign)
                ->with('toast', [
                    'type' => 'warning',
                    'message' => 'Тариф проекта приостановлен.',
                ]);
        }

        if ($sub->status === SubscriptionStatus::noCharged) {
            return redirect()
                ->route('user.billing.new-payment.show')
                ->with('toast', [
                    'type' => 'warning',
                    'message' => 'Проект приостановлен из-за нехватки средств для оплаты тарифа.',
                ]);
        }


        $features = array_map(
            static fn ($item): string => trim((string) $item),
            array_filter(
                explode(',', $feature ?? ''),
                static fn ($item): bool => !empty($item),
            ),
        );

        if ($features === []) {
            return $next($request);
        }

        foreach (Arr::wrap($features) as $f) {
            if (!in_array($f, $sub->plan->features, true)) {
                return redirect()
                    ->route('campaign.subscriptions.choose.show', $campaign)
                    ->with('toast', [
                        'type' => 'warning',
                        'message' => 'В текущем тарифе недоступна данная функция.',
                    ]);
            }
        }

        return $next($request);
    }
}
