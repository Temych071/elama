<?php

declare(strict_types=1);

namespace Reviews\Console\Commands;

use Illuminate\Console\Command;
use Reviews\Actions\DispatchExternalReviewsFetchingAction;
use Reviews\Models\ReviewForm;

final class FetchExternalReviewsCommand extends Command
{
    protected $signature = 'reviews:fetch-external {--silent} {--ignore-daterange}';
    protected $description = 'Fetch external reviews (Yandex.Maps, 2GIS, etc.)';

    public function handle(): void
    {
        foreach (ReviewForm::all() as $reviewForm) {
            if (!empty($reviewForm->getReviewSources())) {
                app(DispatchExternalReviewsFetchingAction::class)
                    ->execute(
                        reviewForm: $reviewForm,
                        notify: !$this->option('silent', false),
                        ignoreDateRange: $this->option('ignore-daterange', false)
                    );
            }
        }
    }
}
