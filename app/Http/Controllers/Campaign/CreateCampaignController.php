<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use App\Exceptions\BusinessException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Module\Campaign\Actions\CreateCampaignAction;
use Module\User\Models\User;

final class CreateCampaignController
{
    /**
     * @throws BusinessException
     */
    public function store(Request $request, CreateCampaignAction $createCampaign): RedirectResponse
    {
        $fields = $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:90'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $limit = $user->getMaxCampaignsCount();
        if ($user->ownCampaigns()->count() >= $limit) {
            throw new BusinessException("Can't create campaign. The limit ($limit) has been reached.");
        }

        $campaign = $createCampaign->execute($user, $fields['name']);
        return redirect()->route('campaign.source', $campaign);
    }

    public function show(Request $request): Responsable
    {
        /** @var User $user */
        $user = $request->user();

        $limit = $user->getMaxCampaignsCount();
        $count = $user->ownCampaigns()->count();
        if ($count >= $limit) {
            return Inertia::render('Campaign/CreateLimit', [
                'count' => $count,
                'limit' => $limit,
            ]);
        }

        return Inertia::render('Campaign/Create');
    }
}
