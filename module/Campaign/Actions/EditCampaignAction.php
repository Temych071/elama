<?php

declare(strict_types=1);

namespace Module\Campaign\Actions;

use Module\Campaign\Models\Campaign;

final class EditCampaignAction
{
    public function execute(Campaign $campaign, array $data): void
    {
        $campaign->update($data);
    }
}
