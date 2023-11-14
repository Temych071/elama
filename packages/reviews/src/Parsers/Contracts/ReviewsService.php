<?php

namespace Reviews\Parsers\Contracts;

use App\Infrastructure\DateRange;
use Reviews\Parsers\Dto\ExternalReview;
use Reviews\Parsers\Dto\GeneralSourceData;

interface ReviewsService
{
    /**
     * @param  string  $placeId
     * @param ?DateRange  $dateRange
     * @return ExternalReview[]
     */
    public function getReviews(string $placeId, ?DateRange $dateRange): array;

    /**
     * @param  string  $placeId
     */
    public function getGeneralData(string $placeId): GeneralSourceData;

//    public function getAnswerSender(): ?ReviewAnswerSender;
}
