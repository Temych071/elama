<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

final class ProjectsListController
{
    public function show(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        return Inertia::render('Campaign/ProjectsList', [
            'projects' => $user->campaigns->transform(static fn (Campaign $campaign): array => $campaign->only(['id', 'name', 'sources'])),
        ]);
    }
}
