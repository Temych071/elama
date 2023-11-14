<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Responsable;
use Inertia\Inertia;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

class CampaignsListController
{
    public function show(): Responsable
    {
        $users = User::all();

        $campaigns = Campaign::query()
            ->select(['id', 'name'])
            ->with(['users', 'sources'])
            ->withCount('users')
            ->latest()
            ->get();

        return Inertia::render('Admin/Campaigns', [
            'campaigns' => $campaigns,
            'users' => $users,
        ]);
    }
}
