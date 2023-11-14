<?php

declare(strict_types=1);

namespace Module\Campaign\Actions;

use Module\Campaign\Models\Campaign;

final class DeleteCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        $campaign->delete();
    }
}
