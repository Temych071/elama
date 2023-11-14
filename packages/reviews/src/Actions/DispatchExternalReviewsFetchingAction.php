<?php

declare(strict_types=1);

namespace Reviews\Actions;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Reviews\Jobs\FetchExternalGeneralDataJob;
use Reviews\Jobs\FetchExternalReviewsJob;
use Reviews\Jobs\FetchExternalStatsJob;
use Reviews\Models\ReviewForm;

final class DispatchExternalReviewsFetchingAction
{
    public function execute(ReviewForm $reviewForm, bool $notify = false, bool $ignoreDateRange = false): void
    {
        $this->dispatchReviewsFetching($reviewForm, $notify, $ignoreDateRange);
        $this->dispatchStatsFetching($reviewForm, $ignoreDateRange);
        $this->dispatchGeneralDataFetching($reviewForm);
    }

    protected function dispatchGeneralDataFetching(ReviewForm $reviewForm): void
    {
        $sources = $reviewForm->getReviewSources();
        foreach ($sources as $source) {
            dispatch(new FetchExternalGeneralDataJob(
                reviewFormId: $reviewForm->id,
                source: $source,
            ));
        }
    }

    protected function dispatchReviewsFetching(
        ReviewForm $reviewForm,
        bool $notify = false,
        bool $ignoreDateRange = false
    ): void {
        $sources = $reviewForm->getReviewSources();
        $dateRanges = $ignoreDateRange ? null : $reviewForm->reviews()
            ->selectRaw('source, MAX(created_at) as date')
            ->groupBy('source')
            ->pluck('date', 'source')
            ->mapWithKeys(static fn(string $date, string $source) => [
                $source => DateRange::fromArray([
                    Carbon::parse($date)->subWeek(),
                    now(),
                ])
            ])
            ->all();

        // Есть проблемка... Из-за того, что берутся только новые дни
        // ответы не будут обновляться
        // Надо либо всегда тянуть последние три страницы, либо я хз)
        foreach ($sources as $source) {
            dispatch(new FetchExternalReviewsJob(
                reviewFormId: $reviewForm->id,
                source: $source,
                dateRange: $dateRanges[$source->source->value] ?? null,
                notify: $notify,
            ));
        }
    }

    protected function dispatchStatsFetching(ReviewForm $reviewForm, bool $ignoreDateRange = false): void
    {
        $sources = $reviewForm->getStatsSources();
        $dateRanges = $ignoreDateRange ? null : DB::table('review_external_stats')
            ->where('review_form_id', $reviewForm->id)
            ->selectRaw('source, MAX(date) as date')
            ->groupBy('source')
            ->pluck('date', 'source')
            ->mapWithKeys(static fn(string $date, string $source) => [
                $source => DateRange::fromArray([
                    Carbon::parse($date)->subWeek(),
                    now(),
                ])
            ])
            ->all();

        foreach ($sources as $source) {
            dispatch(new FetchExternalStatsJob(
                reviewFormId: $reviewForm->id,
                source: $source,
                dateRange: $dateRanges[$source->source->value] ?? DateRange::parse('90days'),
            ));
        }
    }
}
