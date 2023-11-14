<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use Illuminate\Http\RedirectResponse;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Campaign\Models\Campaign;

final class RedirectProjectController
{
    public function __invoke(Campaign $campaign): RedirectResponse
    {
        $hasActiveSub = $campaign
            ->activeSubscription()
            ->where('status', SubscriptionStatus::active)
            ->exists();

        if (!$hasActiveSub) {
            return redirect()->route('social-widget.private.index', $campaign);
        }

        if ($campaign->sources()->exists()) {
            return redirect()->route('campaign.browse', $campaign);
        }

        if ($campaign->socialWidgets()->exists()) {
            return redirect()->route('social-widget.private.index', $campaign);
        }

        if ($campaign->reviewForms()->exists()) {
            return redirect()->route('reviews.private.stats.first', $campaign);
        }

        return redirect()->route('campaign.source', $campaign);
    }
}
