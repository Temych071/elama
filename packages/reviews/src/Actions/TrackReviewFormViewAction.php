<?php

declare(strict_types=1);

namespace Reviews\Actions;

use DB;
use Illuminate\Support\Carbon;
use Reviews\Models\ReviewForm;

final class TrackReviewFormViewAction
{
    public function execute(ReviewForm $reviewForm): void
    {
        $reviewFormId = $reviewForm->id;
        $date = Carbon::now()->toDateString();

        DB::insert(
            "INSERT INTO review_form_stats (review_form_id, date) VALUES (?, ?) ON DUPLICATE KEY UPDATE views = views + 1;",
            [
                $reviewFormId,
                $date,
            ]
        );
    }
}
