<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Module\Campaign\Models\Campaign;
use Module\Source\Actions\GetCabinetBalancesAction;
use Module\User\Models\User;

final class CampaignBalancesController
{
    private const CACHE_TTL_MINUTES = 30;

    public function __invoke(Campaign $campaign): array
    {
        return Cache::remember(
            "balances:$campaign->id",
            self::CACHE_TTL_MINUTES * 60,
            static fn () => app(GetCabinetBalancesAction::class)->execute($campaign),
        );
    }

    public function forManyProjects(Request $request): array
    {
        $projectIds = $request->validate([
            'project_ids' => ['required', 'array'],
            'project_ids.*' => ['required', 'numeric'],
        ])['project_ids'];

        /** @var User $user */
        $user = $request->user();

        $projects = $user->campaigns->whereIn('id', $projectIds);

        $res = [];
        foreach ($projects as $project) {
            $res[$project->id] = $this($project);
        }

        return $res;
    }
}
