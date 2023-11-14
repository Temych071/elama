<?php

declare(strict_types=1);

namespace Reviews\Listeners;

use Illuminate\Support\Carbon;
use Module\Notification\Enums\NotificationStatuses;
use Module\Notification\Enums\NotificationTypes;
use Module\Notification\NotificationService;
use Reviews\Enums\RatingFilter;
use Reviews\Enums\ReviewSource;
use Reviews\Events\NewExternalReviewEvent;
use Reviews\Models\ReviewForm;

final class NewExternalReviewListener
{
    private const SEND_NOTIFS_AT = '10:00';
    private const SEND_NOTIFS_AT_TZ = 'Europe/Moscow';

    public function handle(NewExternalReviewEvent $event): void
    {
        $review = $event->review;
        $reviewForm = $review->reviewForm;

        $notificationService = new NotificationService($reviewForm->project->owner->first());

        $starsChars = str_repeat('★ ', $review->stars) . str_repeat('☆ ', 5 - $review->stars);
        $reviewSourceStr = $this->formatSource($review->source);
        $dateStr = $review->created_at->format('d.m.y');

        $reviewsUrl = route('reviews.private.list', [
            'campaign' => $reviewForm->project_id,
            'branches' => $reviewForm->id,
            'sources' => $review->source,
            'rating' => RatingFilter::new,
        ]);

        $at = explode(':', self::SEND_NOTIFS_AT);
        $at = today(self::SEND_NOTIFS_AT_TZ)->setTime((int) $at[0] ?? 0, (int) $at[1] ?? 0);
        if ($at->isBefore(now())) {
            $at = $at->addDay();
        }

        $notificationService->sendSimpleNotificationAt(
            NotificationTypes::Review,
            "Отзыв $review->stars* в $reviewForm->name",
            <<<EOF
            $starsChars $reviewSourceStr - $dateStr

            <b>$review->name</b><br/>$review->comment<br/><a href="$reviewsUrl">Перейти к отзыву</a>
            EOF,
            $at
        );
    }

    private function formatSource(ReviewSource $source): string
    {
        return match ($source) {
            ReviewSource::DAILY_GROW => 'DailyGrow',
            ReviewSource::YANDEX => 'Яндекс Картах',
            ReviewSource::DOUBLE_GIS => '2ГИС',
        };
    }
}
