<?php

declare(strict_types=1);

namespace SocialWidget\Http\Controllers\Private;

use App\Infrastructure\DateRange;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use SocialWidget\Enums\ChartGroupType;
use SocialWidget\Models\SocialWidget;
use SocialWidget\Models\SocialWidgetStats;

final class WidgetStatsController
{
    public function __invoke(Request $request, Campaign $project, SocialWidget $widget): Response
    {
        $dateRange = DateRange::make($request->input('dateRange', '7days'));
        $chartGroupType = ChartGroupType::tryFrom($request->input('chartGroupType', '1day')) ?? ChartGroupType::ONE_DAY;

        $this->getSummary($project, $widget, $dateRange->getPrev());

        return Inertia::render('SocialWidget/Stats', [
            'project' => $project,
            'widgets' => $project->socialWidgets()->orderBy('created_at')->get(),
            'selectedWidget' => $widget,

            'dateRange' => $dateRange->fromAlias ?? (string)$dateRange,
            'chartGroupType' => $chartGroupType,

            'summary' => $this->getSummary($project, $widget, $dateRange),
            'summaryComparing' => $this->getSummary($project, $widget, $dateRange->getPrev()),

            'charts' => [
                'views' => $this->getStatsChart($project, $widget, $dateRange, 'views', $chartGroupType),
                'clicks' => $this->getStatsChart($project, $widget, $dateRange, 'clicks', $chartGroupType),
            ],
        ]);
    }

    /**
     * @return array{rating: mixed, reviews: mixed, views: mixed, conversion: int|float}
     */
    private function getSummary(Campaign $campaign, SocialWidget $widget, DateRange $dateRange): array
    {
        if ($widget->exists) {
            $summary = $widget
                ->stats()
                ->whereInDateRange($dateRange)
                ->selectRaw('SUM(views) AS views, SUM(clicks) AS clicks')
                ->first()
                ->toArray();
        } else {
            $widgetIds = $campaign->socialWidgets()->select(['id']);

            $summary = $widget
                ->stats()
                ->whereInDateRange($dateRange)
                ->whereIn('widget_id', $widgetIds)
                ->selectRaw('SUM(views) AS views, SUM(clicks) AS clicks')
                ->first()
                ->toArray();
        }

        $summary = array_map(static fn($val) => (int)$val, $summary);

        if (empty($summary['clicks'])) {
            $summary['conversion'] = 0.0;
        } elseif (empty($summary['views'])) {
            $summary['conversion'] = 100.0;
        } else {
            $summary['conversion'] = $summary['clicks'] / $summary['views'] * 100;
        }

        return $summary;
    }

    private function getStatsChart(Campaign $campaign, SocialWidget $widget, DateRange $dateRange, string $statsKey, ChartGroupType $groupType): array
    {
        $q = SocialWidgetStats::query()
            ->selectRaw("SUM($statsKey) as value, date")
            ->groupBy('date')
            ->whereInDateRange($dateRange);

        if ($widget->exists) {
            $q->where('widget_id', $widget->id);
        } else {
            $q->whereIn('widget_id', $campaign->socialWidgets()->select(['id']));
        }

        return $this->groupChart($q->get(), $dateRange, $groupType)->toArray();
    }

    /** @noinspection DuplicatedCode */
    private function groupChart(Collection $data, DateRange $dateRange, ChartGroupType $groupType): Collection
    {
        $count = match ($groupType) {
            ChartGroupType::ONE_DAY => 1,
            ChartGroupType::THREE_DAYS => 3,
            ChartGroupType::SEVEN_DAYS => 7,
        };

        $days = $dateRange->getDaysWithFormat();
        $data = $data->mapWithKeys(static fn ($item): array => [Carbon::parse($item->date)->toDateString() => $item->value]);

        $stats = collect();
        foreach ($days as $day) {
            $stats->push([
                'date' => $day,
                'value' => (int)($data[$day] ?? 0),
            ]);
        }

        return $stats->chunk($count)->mapWithKeys(static function (Collection $chunk): array {
            $dateRange = (string)DateRange::fromArray([
                $chunk->first()['date'],
                $chunk->last()['date'],
            ]);

            $value = $chunk->sum('value');

            return [$dateRange => $value];
        });
    }
}
