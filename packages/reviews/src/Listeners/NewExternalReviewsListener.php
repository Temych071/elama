<?php

declare(strict_types=1);

namespace Reviews\Listeners;

use Module\Notification\Enums\NotificationStatuses;
use Module\Notification\Enums\NotificationTypes;
use Module\Notification\NotificationService;
use Reviews\Enums\RatingFilter;
use Reviews\Enums\ReviewSource;
use Reviews\Events\NewExternalReviewsEvent;
use Reviews\Models\ReviewForm;

final class NewExternalReviewsListener
{
    public function handle(NewExternalReviewsEvent $event): void
    {
        $notificationService = new NotificationService($event->reviewForm->project->owner->first());
        $notificationService->sendSimpleNotification(
            NotificationTypes::Review,
            NotificationStatuses::EnabledInSettings,
            $this->createTitle($event->reviewForm, $event->reviewSource),
            $this->createMessage($event->reviewForm, $event->reviewSource),
            withoutTextHtml: true,
            tgBoldTitle: true,
        );
    }

    private function createTitle(ReviewForm $reviewForm, ReviewSource $source): string
    {
        return "Отзыв о $reviewForm->name на площадке '".$this->formatSource($source)."'";
    }

    private function formatSource(ReviewSource $source): string
    {
        return [
            ReviewSource::DOUBLE_GIS->value => '2GIS',
            ReviewSource::YANDEX->value => 'Яндекс.Карты',
        ][$source->value];
    }

    private function createMessage(ReviewForm $reviewForm, ReviewSource $source): string
    {
        return '<br>Новые отзывы на площадке \''.$this->formatSource($source).'\' -'
            .'<a href="'.route('reviews.public.show', ['slug' => $reviewForm->slug]).'">'.$reviewForm->name.'</a><br>'
            .'<a href="'.route('reviews.private.list', [
                'campaign' => $reviewForm->project,
                'source' => $reviewForm->project,
                'rating' => RatingFilter::new->value,
            ]).'">Перейти к отзывам</a>';
    }
}
