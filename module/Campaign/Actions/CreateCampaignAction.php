<?php

declare(strict_types=1);

namespace Module\Campaign\Actions;

use Module\Billing\Subscription\Models\Plan;
use Module\Campaign\Events\CreateProjectEvent;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

final class CreateCampaignAction
{
    public function execute(User $user, string $name, Plan|false|null $plan = false): Campaign
    {
        $campaign = $user->createCampaign($name);
        event(new CreateProjectEvent($campaign, $plan));
        return $campaign;
    }
}
