<?php

namespace Module\Campaign\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Module\Campaign\Models\Campaign;

trait HasCampaign
{
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_user')
            ->as('members');
    }

    public function ownCampaigns(): BelongsToMany
    {
        return $this->campaigns()
            ->wherePivot('role', Campaign::ROLE_OWNER);
    }

    public function createCampaign(string $name): Campaign
    {
        $campaign = new Campaign();
        $campaign->name = $name;
        $this->campaigns()->save($campaign, ['role' => Campaign::ROLE_OWNER]);
        return $campaign;
    }
}
