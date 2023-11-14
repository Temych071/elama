<?php

declare(strict_types=1);

namespace Module\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;
use Module\User\Models\SettingsRequest;

final class NewSettingsRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

//    public string $queueName = 'mail';

    public function __construct(
        protected SettingsRequest $settingsRequest
    ) {
//        $this->onQueue($this->queueName);
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * @return array{mail: string}
     */
    #[ArrayShape(['mail' => "string"])]
    public function viaQueues(): array
    {
        return ['mail' => 'mail'];
    }

    private const MAIL_TITLE = 'Новая заявка на настройку проекта';

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(self::MAIL_TITLE)
            ->greeting(self::MAIL_TITLE)
            ->lines($this->getRequestInfoLines())
            ->action('Перейти к настраиваемому проекту', route('campaign.browse', $this->settingsRequest->campaign_id));
//            ->action('Посмотреть все заявки', route('admin.settingsRequests'));
    }

    private function getRequestInfoLines(): array
    {
        return [
            "Имя: {$this->settingsRequest->name}\n",
            "Телефон: +{$this->settingsRequest->phone}\n",
            "Проект: {$this->settingsRequest->campaign->name} (#{$this->settingsRequest->campaign->id})\n",
            "Пользователь: {$this->settingsRequest->user->name} (#{$this->settingsRequest->user->id})\n",
        ];
    }
}
