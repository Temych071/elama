<?php

namespace App\Listeners;

use Module\Notification\Enums\NotificationStatuses;
use Module\Notification\Enums\NotificationTypes;
use Module\Notification\NotificationService;
use Reviews\Events\NewReviewEvent;
use Reviews\Models\Review;

class NewReviewListener
{
    private const SEND_NOTIFS_AT = '10:00';
    private const SEND_NOTIFS_AT_TZ = 'Europe/Moscow';

    public function handle(NewReviewEvent $event): void
    {
        if (!$this->checkEnableNotification($event->review)) {
            return;
        }

        $at = explode(':', self::SEND_NOTIFS_AT);
        $at = today(self::SEND_NOTIFS_AT_TZ)->setTime((int) $at[0] ?? 0, (int) $at[1] ?? 0);
        if ($at->isBefore(now())) {
            $at = $at->addDay();
        }

        $notificationService = new NotificationService($event->review->reviewForm->project->owner->first());
        $notificationService->sendSimpleNotificationAt(
            NotificationTypes::Review,
            $this->createTitle($event->review),
            $this->createMessage($event->review),
            $at
        );
    }

    private function checkEnableNotification(Review $review): bool
    {
        $stars = $review->stars;
        $enableStarsReview = $review->reviewForm->max_stars_for_notification;
        return $stars <= $enableStarsReview;
    }

    private function createTitle(Review $review): string
    {
        // Сюда
        return 'Отзыв о '.$review->reviewForm->name.' с оценкой '.$review->stars;
    }

    private function createMessage(Review $review): string
    {
        $formUrl = route('reviews.public.show', ['slug' => $review->reviewForm->slug]);
        $Url = route('reviews.private.list', $review->reviewForm->project);
        $formName = $review->reviewForm->name;
        $authorName = $review->name ?? '';
        $authorContact = $review->contact ?? '';
        $comment = $review->comment ?? '';

        return <<<EOF
            Новый отзыв: <a href="$formUrl">$formName</a>

            $authorName<br>Оценка: $review->stars ★<br>Email/Телефон: $authorContact

            $comment

            <a href="$reviewsUrl">Перейти к отзыву</a>
            EOF;
    }
}
