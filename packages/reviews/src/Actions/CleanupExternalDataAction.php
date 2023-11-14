<?php

declare(strict_types=1);

namespace Reviews\Actions;

use Illuminate\Support\Facades\DB;
use Reviews\Enums\ReviewSource;
use Reviews\Models\ReviewForm;

final class CleanupExternalDataAction
{
    public function execute(ReviewForm $reviewForm, ReviewSource $source): void
    {
        if ($source === ReviewSource::DAILY_GROW) {
            return;
        }
 
        DB::table('review_reviews')
            ->where('review_form_id', $reviewForm->id)
            ->where('source', $source->value)
            ->delete();

        DB::table('review_external_stats')
            ->where('review_form_id', $reviewForm->id)
            ->where('source', $source->value)
            ->delete();

        DB::table('review_external_rating')
            ->where('review_form_id', $reviewForm->id)
            ->where('source', $source->value)
            ->delete();
    }
}
