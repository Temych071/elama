<?php

declare(strict_types=1);

namespace Module\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Module\User\Models\User;

final class NewUserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected User $user
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * @return array{mail: string}
     */
    public function viaQueues(): array
    {
        return ['mail' => 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Новый пользователь в Daily Grow')
            ->greeting('Новый пользователь в системе')
            ->lines($this->getUserLines());
    }

    private function getUserLines(): array
    {
//         возможно не работает campaigns.id...
//        $campaigns = $this->user->campaigns()
//            ->get(['campaigns.id', 'campaigns.name'])
//            ->map(fn($it) => "{$it->name} ({$it->id})")
//            ->implode(', ');

        return [
            "Имя: {$this->user->name} ({$this->user->id})\n",
            "Телефон: +{$this->user->phone}\n",
            "Email: {$this->user->email}\n",
//            "Проект: {$campaigns}\n",
        ];
    }
}
