<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Module\Campaign\Actions\EditCampaignAction;
use Module\Campaign\Models\Campaign;

final class EditCampaignController
{
    public function show(Campaign $campaign): Responsable
    {
        return Inertia::render('Campaign/Edit', [
            'campaign' => $campaign->only(['name']),
        ]);
    }

    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:90'],
        ]);
        app(EditCampaignAction::class)->execute($campaign, $data);

        return redirect()->route('campaign.settings');
    }
}
