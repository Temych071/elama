<?php

declare(strict_types=1);

namespace App\Http\Controllers\Analytics;

use App\Infrastructure\DateRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\ItemData;
use Module\Source\Analytics\Exceptions\EmptyPathItemException;
use Module\Source\Analytics\Exceptions\UndefinedPathItemTypeException;
use Module\Source\Analytics\Implementations\Root\RootRouter;
use Module\Source\Analytics\Services\AnalyticsPath;
use Module\User\Models\User;

final class AnalyticsController
{
    private const CACHE_TTL_MINUTES = 60 * 24;
//    public const CACHE_ALLOWED_DATERANGE_ALIASES = [
    //        '7days',
    //        '30days',
    //    ];
    /**
     * @throws UndefinedPathItemTypeException
     * @throws EmptyPathItemException
     * @return array{items: mixed[]}
     */
    public function load(Request $request, Campaign $campaign): array
    {
        // TODO: Валидация path и dateRange

        $dateRange = DateRange::make($request->input('dateRange', '7days'));
        $path = AnalyticsPath::make($request->input('path'));

        return [
            'items' => $this->getCachedProjectAnalytics($campaign, $dateRange, $path),
        ];
    }

    /**
     * @throws UndefinedPathItemTypeException
     * @throws EmptyPathItemException
     */
    public function loadManyPaths(Request $request, Campaign $project): array
    {
        // Это для графиков
        // TODO: Валидация path и dateRange

        $dateRange = DateRange::make($request->input('dateRange', '7days'));
        $paths = array_map(static fn (string $path): AnalyticsPath => AnalyticsPath::make($path), $request->input('paths', []));

        $res = [];
        foreach ($paths as $path) {
            $res[$path] = $this->getCachedProjectAnalytics($project, $dateRange, $path);
        }

        return $res;
    }

    /**
     * @throws UndefinedPathItemTypeException
     * @throws EmptyPathItemException
     */
    public function loadManyProjects(Request $request): array
    {
        // А это для списка проектов

        $data = $request->validate([
            'project_ids' => ['required', 'array'],
            'project_ids.*' => ['required', 'integer'],

            'dateRange' => ['nullable'],
            'path' => ['nullable'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $projects = $user->campaigns->whereIn('id', $data['project_ids']);

        $dateRange = DateRange::make($data['dateRange'] ?? '7days');
        $path = AnalyticsPath::make($data['path'] ?? null);

        $res = [];
        foreach ($projects as $project) {
            $res[$project->id] = $this->getCachedProjectAnalytics($project, $dateRange, $path);
        }

        return $res;
    }

    public function show(Request $request, Campaign $campaign): Response
    {
        $dateRange = DateRange::make($request->input('dateRange', '7days'));

        return Inertia::render('Campaign/Analytics/Show', [
            'dateRange' => $dateRange,
            'tableFieldsSettings' => $campaign->analytics_parameters,
        ]);
    }

    /**
     * @throws UndefinedPathItemTypeException
     * @throws EmptyPathItemException
     */
    private function getCachedProjectAnalytics(Campaign $project, DateRange $dateRange, AnalyticsPath $path): array
    {
        if (
            $path->isEmpty()
//            && in_array($dateRange->fromAlias, self::CACHE_ALLOWED_DATERANGE_ALIASES, true)
        ) {
            return Cache::tags(['project:' . $project->id, 'analytics', 'analytics-root'])->remember(
//            return Cache::remember(
                'analytics-root:' . $project->id . '|' . $dateRange,
                self::CACHE_TTL_MINUTES * 60,
                fn (): array => $this->getProjectAnalytics($project, $dateRange, $path),
            );
        }

        return $this->getProjectAnalytics($project, $dateRange, $path);
    }

    /**
     * @throws UndefinedPathItemTypeException
     * @throws EmptyPathItemException
     */
    private function getProjectAnalytics(Campaign $project, DateRange $dateRange, AnalyticsPath $path): array
    {
        return array_map(
            static fn (ItemData $it): array => $it->toArray(),
            app(RootRouter::class)->getData($project, $dateRange, $path) ?? [],
        );
    }
}
