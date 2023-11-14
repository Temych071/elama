<?php

declare(strict_types=1);

namespace Module\Campaign\Events;

use Illuminate\Queue\SerializesModels;
use Module\Billing\Subscription\Models\Plan;
use Module\Campaign\Models\Campaign;

final class CreateProjectEvent
{
    use SerializesModels;

    public function __construct(
        public Campaign $campaign,
        public Plan|false|null $plan = null,
    ) {
    }
}
