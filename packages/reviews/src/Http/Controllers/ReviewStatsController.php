<?php

declare(strict_types=1);

namespace Reviews\Http\Controllers;

use App\Infrastructure\DateRange;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Reviews\DTO\ReviewSourceData;
use Reviews\Enums\ChartGroupType;
use Reviews\Enums\ReviewSource;
use Reviews\Models\Review;
use Reviews\Models\ReviewForm;
use Reviews\Models\ReviewFormStats;
use Reviews\Parsers\Dto\ReviewsList;
use Reviews\Parsers\Gis\Services\DoubleGisReviewsService;

final class ReviewStatsController
{
    public function show(Request $request, Campaign $campaign, ReviewForm $reviewForm): Response
    {
        $dateRange = DateRange::make($request->input('dateRange', '30days'));
        $chartGroupType = ChartGroupType::tryFrom(
            $request->input('chartGroupType', '3days')
        ) ?? ChartGroupType::SEVEN_DAYS;

        $ratingSource = ReviewSource::from($request->input('ratingSource', 'yandex'));
        $compareAllStats = $request->boolean('compareAllStats', true);
        $compareFormsStats = $request->boolean('compareFormsStats', true);

        return Inertia::render('Reviews/Private/Stats', [
            'reviewForms' => $campaign->reviewForms()->select([
                'id', 'name', 'slug', 'widget_yamaps', 'widget_2gis',
                'project_id', 'phrases',
            ])->get(),
            'reviewForm' => $reviewForm->exists ? $reviewForm : null,

            'dateRange' => $dateRange->fromAlias ?? (string) $dateRange,
            'chartGroupType' => $chartGroupType,
//            'dateRangeComparing' => (string)$dateRange->getPrev(),

            'summary' => $this->getSummary($campaign, $reviewForm, $dateRange),
            'summaryComparing' => $this->getSummary($campaign, $reviewForm, $dateRange->getPrev()),

            'charts' => [
                'views' => $this->getViewsChart($campaign, $reviewForm, $dateRange, $chartGroupType),
                'reviews' => $this->getReviewsChart($campaign, $reviewForm, $dateRange, $chartGroupType),
                'external_reviews' => $this->getExternalReviewsChart($campaign, $reviewForm, $dateRange,
                    $chartGroupType),
            ],

//            'formsStats' => $this->getFormsStats($campaign, $dateRange),
            'compareFormsStats' => $compareFormsStats,

            'ratingChart' => $this->getRatingChart($campaign, $dateRange, $ratingSource, $chartGroupType),
            'ratingSource' => $ratingSource->value,

            'allStats' => $this->getAllStats($campaign, $dateRange),
            'compareAllStats' => $compareAllStats,
            'allStatsCompare' => $compareAllStats
                ? Arr::keyBy($this->getAllStats($campaign, $dateRange->getPrev()), 'name')
                : [],
        ]);
    }

    public function first(Campaign $campaign): RedirectResponse
    {
        return redirect()->route('reviews.private.stats.show', [$campaign, $campaign->reviewForms[0] ?? null]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function widgets(Campaign $campaign, ReviewForm $reviewForm): array
    {
        $sources = array_map(
            static fn(ReviewSourceData $source) => $source->source->value,
            $reviewForm->getReviewSources()
        );

        if (empty($sources)) {
            return [];
        }

        $q = $reviewForm
            ->reviews()
            ->whereIn('source', $sources)
            ->toBase();

        $summary = $q->clone()
            ->groupBy('source')
            ->selectRaw('source, AVG(stars) as rating, COUNT(*) as totalCount')
            ->get()
            ->map(static fn($data) => (array) $data)
            ->keyBy('source')
            ->toArray();

        $rq = $q->clone()
            ->selectRaw('source, stars, external_id, name, comment, created_at')
            ->latest()
            ->limit(4);

//        $generalDataProvider = app(GeneralSourceDataProvider::class);

        // Как-то сложно одним запросом. Пусть так пока будет...
        $generalDataQuery = DB::table('review_external_rating')
            ->where('review_form_id', $reviewForm->id)
            ->latest('date')
            ->selectRaw('rating, reviewsCount AS totalReviewsCount');

        $widgets = [];
        foreach ($reviewForm->getReviewSources() as $reviewSource) {
            $widget = $summary[$reviewSource->source->value] ?? [];
            $generalData = $generalDataQuery->clone()
                ->where('source', $reviewSource->source->value)
                ->first();

            if ($generalData !== null) {
                $widget['rating'] = $generalData->rating;
                $widget['totalCount'] = $generalData->totalReviewsCount;
            }

            $widget['placeId'] = $reviewSource->placeId;
            $widget['reviews'] = $rq->clone()
                ->where('source', $reviewSource->source->value)
                ->get()
                ->map(static fn($review) => new \Reviews\Parsers\Dto\Review(
                    id: $review->external_id,
                    text: $review->comment,
                    name: $review->name,
                    date: $review->created_at,
                    rating: $review->stars,
                ))->toArray();

            $widgets[] = $widget;
        }

        return array_map(static fn($list) => ReviewsList::from($list), $widgets);
    }

    public function widget2Gis(Request $request)
    {
        return app(DoubleGisReviewsService::class)
            ->getInfo($request->input('place_id'));
    }

    public function getRatingChart(
        Campaign $campaign,
        DateRange $dateRange,
        ?ReviewSource $ratingSource = null,
        ChartGroupType $chartGroupType = ChartGroupType::ONE_DAY,
    ): array {
        $formNames = $campaign->reviewForms->pluck('name', 'id');

        $ratings = DB::table('review_reviews')
            ->groupBy('review_form_id')
            ->whereDate('created_at', '<', $dateRange->getFrom())
            ->whereIn('review_form_id', $campaign->reviewForms->pluck('id'))
            ->when($ratingSource !== null, fn($q) => $q->where('source', $ratingSource->value))
            ->selectRaw('review_form_id, SUM(stars) as stars, COUNT(stars) as count')
            ->get()
            ->keyBy('review_form_id')
            ->transform(static fn($data) => [
                'stars' => (int) $data->stars,
                'count' => (int) $data->count,
            ])
            ->toArray();

        $chartDays = $dateRange->getDaysWithFormat();

        $generalDataRatings = DB::table('review_external_rating')
            ->whereIn('review_form_id', $campaign->reviewForms->pluck('id'))
            ->whereInDateRange($dateRange)
            ->when($ratingSource !== null, fn($q) => $q->where('source', $ratingSource->value))
            ->groupBy(['review_form_id', 'date'])
            ->selectRaw('review_form_id, date, AVG(rating) AS rating')
            ->get()
            ->groupBy('review_form_id')
            ->transform(static fn($formData) => $formData->pluck('rating', 'date'));

        $data = DB::table(
            DB::table('review_reviews')
                ->groupBy('review_form_id', 'date')
                ->whereInDateRange($dateRange, 'created_at')
                ->whereIn('review_form_id', $campaign->reviewForms->pluck('id'))
                ->when($ratingSource !== null, fn($q) => $q->where('source', $ratingSource->value))
                ->selectRaw('review_form_id, DATE(created_at) as date, SUM(stars) as stars, COUNT(stars) as count')
        )
            ->leftJoin('review_forms', 'id', '=', 'review_form_id')
            ->oldest('date')
            ->select(['review_form_id', 'name', 'stars', 'date', 'count'])
            ->get()
            ->groupBy('name')
            ->transform(static fn(Collection $days) => $days->keyBy('date')
                ->transform(static fn($data) => [
                    'stars' => (int) $data->stars,
                    'count' => (int) $data->count,
                ]))
            ->toArray();

        $charts = [];
        foreach ($formNames as $formId => $formName) {
            $charts[$formName] = [];
            $latest = $ratings[$formId] ?? [
                'stars' => 0,
                'count' => 0,
            ];

            $lastGeneral = null;
            foreach ($chartDays as $date) {
                $lastGeneral = $generalDataRatings[$formId][$date] ?? $lastGeneral;
                if ($lastGeneral !== null) {
                    $charts[$formName][$date] = (float) $lastGeneral;
                    continue;
                }

                $value = $data[$formName][$date] ?? [
                    'stars' => 0,
                    'count' => 0,
                ];
                if (!empty($value)) {
                    $latest['stars'] += $value['stars'];
                    $latest['count'] += $value['count'];
                }

                if ($latest['count'] <= 0) {
                    $charts[$formName][$date] = 0;
                } else {
                    $charts[$formName][$date] = round($latest['stars'] / $latest['count'], 2);
                }
            }

            $charts[$formName] = $this->groupChart(
                $charts[$formName], $dateRange, $chartGroupType,
                static fn(array $group) => Arr::last($group)['value'],
            );
        }

        return $charts;
    }

    private function getAllStats(Campaign $campaign, DateRange $dateRange): array
    {
        $formIds = $campaign->reviewForms->pluck('id');
        $toDate = $dateRange->getTo();

        $oldRatings = DB::table('review_reviews')
            ->whereDate('created_at', '<=', $toDate)
            ->groupBy(['review_form_id', 'source'])
            ->selectRaw('review_form_id, source, AVG(stars) as rating')
            ->get()
            ->groupBy('review_form_id')
            ->transform(static fn($item) => $item->pluck('rating', 'source'));

        return DB::table('review_forms')
            ->whereIn('id', $campaign->reviewForms->pluck('id'))
            ->leftJoinSub(DB::table(DB::table('review_external_rating', 'r')
                ->whereIn('r.review_form_id', $formIds)
                ->rightJoinSub(DB::table('review_external_rating')
                    ->selectRaw('
                        MAX(date) as maxDate,
                        source,
                        review_form_id
                    ')
                    ->groupBy(['review_form_id', 'source'])
                    ->whereDate('date', '<=', $toDate),
                'm',
                    static fn(JoinClause $j) => $j
                        ->on('r.date', 'm.maxDate')
                        ->on('r.source', 'm.source')
                        ->on('r.review_form_id', 'm.review_form_id')
                )
                ->selectRaw('
                    r.review_form_id,
                    case when r.source = "yandex" then r.rating end AS ratingYandex,
                    case when r.source = "double-gis" then r.rating end AS rating2gis
                '))
                ->groupBy('review_form_id')
                ->selectRaw('
                    review_form_id,
                    SUM(ratingYandex) AS ratingYandex,
                    SUM(rating2gis) AS rating2gis,
                    (SUM(ratingYandex) + SUM(rating2gis)) / 2 as rating
                '),
                'ratings', 'ratings.review_form_id', '=', 'review_forms.id'
            )
            ->leftJoinSub(
                DB::table('review_reviews')
                    ->leftJoin('review_reviews_answers', 'review_id', '=', 'review_reviews.id')
                    ->groupBy('review_form_id')
                    ->whereDate('review_reviews.created_at', '<=', $toDate)
                    ->whereIn('review_form_id', $formIds)
                    ->selectRaw('
                        review_form_id,
                        SUM(IF(source = \'daily-grow\', 1, 0)) as marksCount,
                        SUM(IF(source != \'daily-grow\', 1, 0)) as reviewsCount,
                        COUNT(review_reviews_answers.text) as answersCount
                    '),
                'reviews', 'reviews.review_form_id', '=', 'review_forms.id'
            )
            ->select([
                'review_forms.id',
                'name',
                'phrases',
                'ratingYandex',
                'rating2gis',
                'reviewsCount',
                'marksCount',
                'answersCount',
            ])
            ->orderByDesc('ratings.rating')
            ->get()
            ->transform(static fn($item, $key) => [
                'rank' => $key + 1,
                'name' => (string) $item->name,
                'address' => (string) (json_decode($item->phrases)?->text_address ?? '-'),
                'reviewsCount' => (int) $item->reviewsCount,
                'marksCount' => (int) $item->marksCount,
                'rating2gis' => (float) (empty($item->rating2gis) ? ($oldRatings[$item->id]['double-gis'] ?? 0) : $item->rating2gis),
                'ratingYandex' => (float) (empty($item->ratingYandex) ? ($oldRatings[$item->id]['yandex'] ?? 0) : $item->ratingYandex),
                'answersCount' => (int) $item->answersCount,
            ])
            ->toArray();
    }

    private function getFormsStats(Campaign $campaign, DateRange $dateRange): array
    {
        return DB::table(
            DB::table('review_external_stats')
                ->groupBy('review_form_id')
                ->whereInDateRange($dateRange)
                ->whereIn('review_form_id', $campaign->reviewForms->pluck('id'))
                ->selectRaw('review_form_id, SUM(views) as views, SUM(calls) as calls, SUM(site) as site, SUM(routes) as routes'),
            'stats'
        )
            ->leftJoin('review_forms', 'review_forms.id', '=', 'stats.review_form_id')
            ->leftJoinSub(
                DB::table('review_reviews')
                    ->groupBy('review_form_id')
                    ->whereIn('review_form_id', $campaign->reviewForms->pluck('id'))
                    ->selectRaw('review_form_id, AVG(stars) as rating'),
                'ratings', 'ratings.review_form_id', '=', 'stats.review_form_id'
            )
            ->select(['name', 'views', 'calls', 'routes', 'site', 'rating'])
            ->orderByDesc('rating')
            ->get()
            ->transform(static fn($item) => [
                'name' => (string) $item->name,
                'views' => (int) $item->views,
                'calls' => (int) $item->calls,
                'routes' => (int) $item->routes,
                'site' => (int) $item->site,
                'rating' => (float) $item->rating,
            ])
            ->toArray();
    }

    /**
     * @return array{rating: mixed, reviews: mixed, views: mixed, conversion: int|float}
     */
    private function getSummary(Campaign $campaign, ReviewForm $reviewForm, DateRange $dateRange): array
    {
        if ($reviewForm->exists) {
            $summary = [
                'rating' => $reviewForm
                    ->reviews()
                    ->whereDate('created_at', '<=', $dateRange->getTo())
                    ->external()
                    ->avg('stars'),
                'reviews' => $reviewForm
                    ->reviews()
                    ->whereInDateRange($dateRange, 'created_at')
                    ->internal()
                    ->count(),
                'external_reviews' => $reviewForm
                    ->reviews()
                    ->whereInDateRange($dateRange, 'created_at')
                    ->external()
                    ->count(),
                'views' => $reviewForm
                    ->stats()
                    ->whereInDateRange($dateRange)
                    ->sum('views'),
            ];
        } else {
            $reviewFormIds = $campaign->reviewForms()->select(['id']);

            $summary = [
                'rating' => Review::query()
                    ->whereIn('review_form_id', $reviewFormIds)
                    ->whereDate('created_at', '<=', $dateRange->getTo())
                    ->external()
                    ->avg('stars'),
                'reviews' => Review::query()
                    ->whereIn('review_form_id', $reviewFormIds)
                    ->whereInDateRange($dateRange, 'created_at')
                    ->internal()
                    ->count(),
                'external_reviews' => Review::query()
                    ->whereIn('review_form_id', $reviewFormIds)
                    ->whereInDateRange($dateRange, 'created_at')
                    ->external()
                    ->count(),
                'views' => ReviewFormStats::query()
                    ->whereIn('review_form_id', $reviewFormIds)
                    ->whereInDateRange($dateRange)
                    ->sum('views'),
            ];
        }

        if (empty($summary['reviews'])) {
            $summary['conversion'] = 0.0;
        } elseif (empty($summary['views'])) {
            $summary['conversion'] = 100.0;
        } else {
            $summary['conversion'] = $summary['external_reviews'] / $summary['reviews'] * 100;
        }

        return $summary;
    }

    private function getViewsChart(
        Campaign $campaign,
        ReviewForm $reviewForm,
        DateRange $dateRange,
        ChartGroupType $groupType
    ): array {
        $q = ReviewFormStats::query()
            ->selectRaw('SUM(views) as value, date')
            ->groupBy('date')
            ->whereInDateRange($dateRange);

        if ($reviewForm->exists) {
            $q->where('review_form_id', $reviewForm->id);
        } else {
            $q->whereIn('review_form_id', $campaign->reviewForms()->pluck('id'));
        }

        return $this->groupChart($q->pluck('value', 'date'), $dateRange, $groupType);
    }

    private function getReviewsChart(
        Campaign $campaign,
        ReviewForm $reviewForm,
        DateRange $dateRange,
        ChartGroupType $groupType
    ): array {
        $q = Review::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as value')
            ->groupBy('date')
            ->orderBy('date')
            ->internal()
            ->whereInDateRange($dateRange, 'created_at');

        if ($reviewForm->exists) {
            $q->where('review_form_id', $reviewForm->id);
        } else {
            $q->whereIn('review_form_id', $campaign->reviewForms()->pluck('id'));
        }

        return $this->groupChart($q->pluck('value', 'date'), $dateRange, $groupType);
    }

    private function getExternalReviewsChart(
        Campaign $campaign,
        ReviewForm $reviewForm,
        DateRange $dateRange,
        ChartGroupType $groupType
    ): array {
        $q = Review::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as value')
            ->groupBy('date')
            ->orderBy('date')
            ->external()
            ->whereInDateRange($dateRange, 'created_at');

        if ($reviewForm->exists) {
            $q->where('review_form_id', $reviewForm->id);
        } else {
            $q->whereIn('review_form_id', $campaign->reviewForms()->pluck('id'));
        }

        return $this->groupChart($q->pluck('value', 'date'), $dateRange, $groupType);
    }

    private function groupChart(
        Collection|array $data,
        DateRange $dateRange,
        ChartGroupType $groupType,
        ?callable $cb = null,
    ): array {
        $count = match ($groupType) {
            ChartGroupType::ONE_DAY => 1,
            ChartGroupType::THREE_DAYS => 3,
            ChartGroupType::SEVEN_DAYS => 7,
        };

        $days = $dateRange->getDaysWithFormat();
        $data = collect($data)->mapWithKeys(static fn($value, $date): array => [
            Carbon::parse($date)->toDateString() => $value,
        ]);

        $stats = collect();
        foreach ($days as $day) {
            $stats->push([
                'date' => $day,
                'value' => $data[$day] ?? null,
            ]);
        }

        return $stats->chunk($count)->mapWithKeys(static function (Collection $chunk) use ($cb): array {
            $dateRange = (string) DateRange::fromArray([
                $chunk->first()['date'],
                $chunk->last()['date'],
            ]);

            if ($cb === null) {
                $value = $chunk->sum('value');
            } else {
                $value = $cb($chunk->toArray());
            }

            return [$dateRange => $value];
        })->toArray();
    }
}
