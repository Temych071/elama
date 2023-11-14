<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Module\Campaign\Actions\DeleteCampaignAction;
use Module\Campaign\Models\Campaign;

final class DeleteCampaignController
{
    public function __invoke(Campaign $campaign): RedirectResponse
    {
        app(DeleteCampaignAction::class)->execute($campaign);
        return redirect()->route('campaign.settings');
    }

    public function show(Campaign $campaign): Responsable
    {
        return Inertia::render('Campaign/Delete', [
            'campaign' => $campaign->only(['name']),
        ]);
    }
}
