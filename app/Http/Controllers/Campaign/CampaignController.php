<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;
use Module\Campaign\Actions\CreateCampaignAction;
use Module\User\Models\User;

final class CampaignController
{
    public function settings(): Response|ResponseFactory
    {
        /** @var array $allSources */
        $allSources = config('sources.list');

        return inertia('Campaign/CampaignSettings', [
            'sources' => array_values(array_map(static fn ($source): array => [
                'name' => $source['name'],
                'type' => $source['type'],
            ], $allSources)),
        ]);
    }

    public function store(Request $request, CreateCampaignAction $createCampaign): RedirectResponse
    {
        $fields = $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:90'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $createCampaign->execute($user, $fields['name']);

        return back();
    }
}
