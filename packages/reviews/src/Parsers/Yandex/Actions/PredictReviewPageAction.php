<?php

namespace Reviews\Parsers\Yandex\Actions;

use Reviews\Enums\ReviewSource;
use Reviews\Models\Review;

class PredictReviewPageAction
{
    protected const REVIEWS_PER_PAGE = 20;
    public function execute(Review $review): int
    {
        $reviewExternalIds = $review->reviewForm
            ->reviews()
            ->selectRaw('external_id')
            ->where('source', ReviewSource::YANDEX)
            ->latest()
            ->pluck('external_id');

        $reviewNumber = 0;
        foreach ($reviewExternalIds as $index => $externalId) {
            if ($externalId === $review->external_id) {
                $reviewNumber = $index;
            }
        }
        // Пытался сразу запросом вытащить через ROW_NUMBER,
        // но чёт не совсем понял как оно работает)

        return ceil(($reviewNumber + 1) / self::REVIEWS_PER_PAGE);
    }
}
