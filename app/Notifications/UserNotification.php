<?php

namespace App\Notifications;

use App\Channels\TelegramChannel;
use App\Mail\MailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Module\Notification\Enums\NotificationSourceTypes;

class UserNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly array $data,

        // Нигде не юзается?)
        private $file = null,
    ) {
    }

    public function via(mixed $notifiable): array
    {
        $notifications = (array) $notifiable->notification_types;
        $via = [];

        foreach ($notifications as $type) {
            switch ($type) {
                case NotificationSourceTypes::Telegram->value:
                    $via[] = TelegramChannel::class;
                    break;
                case NotificationSourceTypes::Mail->value:
                    $via[] = 'mail';
                    break;
                case NotificationSourceTypes::Web->value:
                    $via[] = 'database';
                    break;
                default:
                    break;
            }
        }

        return $via;
    }

    public function toMail(mixed $notifiable): MailNotification
    {
        return (new MailNotification($this->toArray($notifiable)))
            ->to($notifiable->notification_email);
    }

    public function toArray(mixed $notifiable): array
    {
        return $this->data;
    }
}
