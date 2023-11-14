<?php

declare(strict_types=1);

namespace Reviews\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Reviews\DTO\ReviewSourceData;
use Reviews\Models\ReviewForm;
use Reviews\Parsers\Contracts\ReviewsService;

final class FetchExternalGeneralDataAction
{
    public function execute(
        ReviewForm $reviewForm,
        ReviewSourceData $source,
    ): void {
        /** @var ReviewsService $service */
        $service = app($source->serviceClass);

        $generalData = $service->getGeneralData($source->placeId);
//        dump($generalData);
        DB::table('review_external_rating')
            ->upsert([
                'review_form_id' => $reviewForm->id,
                'source' => $source->source->value,
                'date' => $this->getCorrectDate(now()),
                'rating' => $generalData->rating,
                'reviewsCount' => $generalData->totalReviewsCount,
            ],
                ['review_form_id', 'source', 'date'],
                ['rating', 'reviewsCount']);
    }

    private function getCorrectDate(Carbon $date): Carbon
    {
        if ($date->hour < 5) {
            return $date->subDay();
        }

        return $date;
    }
}
