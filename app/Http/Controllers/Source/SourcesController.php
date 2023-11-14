<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Enums\ProjectMemberRole;
use Module\Campaign\Models\Campaign;
use Module\User\Enums\UserRole;
use Module\User\Models\User;

final class SourcesController
{
    public function __invoke(Campaign $campaign): Response
    {
        /** @var array $allSources */
        $allSources = array_filter(config('sources.list'), static fn($source): bool => !($source['for_admin'] ?? false) || Auth::user()->role === UserRole::admin);

        $sources = $campaign
            ->sources()
            ->select(['id', 'settings_type', 'source_oauth_token_id'])
            ->with('authToken', static fn ($q) => $q->select(['id', 'invalid']))
            ->get()
            ->append('is_token_invalid')
            ->makeHidden(['source_oauth_token_id', 'authToken']);

        return Inertia::render('Sources/Sources', [
            'campaign' => $campaign->only(['id', 'name']),
            'campaign_sources' => $sources,
            'sources' => array_values(
                array_map(static fn ($source): array => [
                    'name' => $source['name'],
                    'type' => $source['type'],
                ], $allSources)
            ),
            'campaign_members' => $campaign->users->transform(static fn (User $member): array => [
                'email' => $member->email,
                'role' => $member->pivot->role,
                'comment' => $member->pivot->comment,
            ]),
            'roles' => array_map(static fn (ProjectMemberRole $role): string => $role->value, ProjectMemberRole::cases()),
        ]);
    }
}
